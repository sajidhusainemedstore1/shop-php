<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Coupon_model extends CI_Model {
    public function validate_coupon($code) {
        $this->db->where('code', $code);
        $this->db->where('active', 1);
        $this->db->where('expiry_date >=', date('Y-m-d'));
        return $this->db->get('coupons')->row_array();
    }

    public function add_coupon($data) {
        return $this->db->insert('coupons', $data);
    }

    public function get_all_coupons() {
        $this->db->select('id, code, type, value, number_of_users, redemption_limit, start_date,start_time, expiry_date, expiry_time, min_purchase_amount, max_discount_amount, dis_amount, custom_redemption_limit, active');
        $query = $this->db->get('coupons');
        return $query->result_array();
    }

    public function edit_coupons($code,$data) {
        return $this->db->where('code',$code)->update('coupons',$data);
    }

    public function apply_coupon_to_total($code, $total_amount, $user_id) {
        $coupon = $this->validate_coupon($code);
        if (!$coupon) {
            return [
                'success' => false,
                'message' => 'Invalid or expired coupon code.'
            ];
        }
    
        if (isset($coupon['min_purchase_amount']) && $total_amount < $coupon['min_purchase_amount']) {
            return [
                'success' => false,
                'message' => 'Minimum purchase amount not met for this coupon.'
            ];
        }

        if ($this->has_user_used_coupon($user_id, $code)) {
            return [
                'success' => false,
                'message' => 'You have already used this coupon.'
            ];
        }

        if (!empty($coupon['number_of_users'])) {
            $used_count = $this->get_total_coupon_users($code);
            if ($used_count >= $coupon['number_of_users']) {
                return [
                    'success' => false,
                    'message' => 'Coupon usage limit reached.'
                ];
            }
        }

        $discount = 0;
        if ($coupon['type'] === 'percentage') {
            $discount = ($total_amount * floatval($coupon['value'])) / 100;
            if (!empty($coupon['max_discount_amount']) && $discount > $coupon['max_discount_amount']) {
                $discount = $coupon['max_discount_amount'];
            }
        } elseif ($coupon['type'] === 'fixed') {
            $discount = floatval($coupon['dis_amount']);
            if ($discount > $total_amount) {
                $discount = $total_amount;
            }
        }

        $final_total = $total_amount - $discount;
        if ($final_total < 0) {
            $final_total = 0;
        }

        $this->log_coupon_usage($user_id, $code);

        return [
            'success' => true,
            'coupon' => $coupon,
            'discount' => round($discount, 2),
            'final_total' => round($final_total, 2)
        ];
    }

    public function has_user_used_coupon($user_id, $coupon_code) {
        return $this->db->where('user_id', $user_id)
                        ->where('coupon_code', $coupon_code)
                        ->from('coupon_usages')
                        ->count_all_results() > 0;
    }

    public function get_total_coupon_users($coupon_code) {
        return $this->db->select('user_id')
                        ->distinct()
                        ->where('coupon_code', $coupon_code)
                        ->get('coupon_usages')
                        ->num_rows();
    }

    public function log_coupon_usage($user_id, $coupon_code) {
        return $this->db->insert('coupon_usages', [
            'user_id' => $user_id,
            'coupon_code' => $coupon_code
        ]);
    }

}

?>


