<?php

class Checkout extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $user_id = $this->session->userdata('user_id'); 
        $user = $this->wallet_model->get_user($user_id);
        $addresses = $this->address_model->get_addresses($user_id);
        $address   = $this->address_model->get_default_address($user_id);
        $cart      = $this->cart_model->get_cart_items($user_id);
        
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('checkout');
        }
    
        $sub_total = array_sum(array_column($cart, 'subtotal'));
        $wallet_balance = $user['wallet_balance'] ?? 0;
        $wallet_percentage_row = $this->db->get_where('settings', ['name' => 'Wallet Percentage'])->row_array();
        $wallet_percentage = isset($wallet_percentage_row['value']) ? floatval($wallet_percentage_row['value']) : 0;

        $wallet_amount = min(round($sub_total * ($wallet_percentage / 100), 2), $wallet_balance);
        $final_total = $sub_total - $wallet_amount;
    
        $data = [
            'user'          => $user,
            'addresses'     => $addresses,
            'address'       => $address,
            'cart'          => $cart,
            'sub_total'     => $sub_total,
            'wallet_amount' => $wallet_amount,
            'final_total'   => $final_total,
        ];
    
        $this->load->view('user/checkout', $data);
    }

    // public function place_order() {
    //     // You can handle payment logic here
    //     // Save order to `orders` table and redirect to success
    // }

    public function apply_coupon() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('login');

        $coupon_code = $this->input->post('coupon_code', true);
        $now = date('Y-m-d H:i:s');

        $this->db->where('code', $coupon_code);
        $this->db->where('active', 1);
        $this->db->where('expiry_date >=', date('Y-m-d'));
        $coupon = $this->db->get('coupons')->row_array();

        if (!$coupon) {
            $this->session->set_flashdata('error', 'Invalid or expired coupon code.');
            redirect('checkout');
        }

        $start_datetime = $coupon['start_date'] . ' ' . ($coupon['start_time'] ?? '00:00:00');
        $expiry_datetime = $coupon['expiry_date'] . ' ' . ($coupon['expiry_time'] ?? '23:59:59');

        if ($now < $start_datetime) {
            $this->session->set_flashdata('error', 'Coupon not active yet.');
            redirect('checkout');
        }

        if ($now > $expiry_datetime) {
            $this->session->set_flashdata('error', 'Coupon expired.');
            redirect('checkout');
        }

        if (!empty($coupon['number_of_users'])) {
            $this->db->select('COUNT(DISTINCT user_id) AS used_count');
            $this->db->where('coupon_code', $coupon_code);
            $usage_result = $this->db->get('coupon_usages')->row();
            if ($usage_result && $usage_result->used_count >= $coupon['number_of_users']) {
                $this->session->set_flashdata('error', 'This coupon has reached its usage limit.');
                redirect('checkout');
            }
        }

        if ($coupon['customer_filter'] === 'registered_date') {
            $user = $this->db->get_where('user', ['id' => $user_id])->row_array();
            if (isset($user['created_at']) && strtotime($user['created_at']) > strtotime('-7 days')) {
                $this->session->set_flashdata('error', 'This coupon is not available for your account yet.');
                redirect('checkout');
            }
        }

        $cart_items = $this->cart_model->get_cart_items($user_id);
        $cart_total = array_sum(array_column($cart_items, 'subtotal'));

        if (!empty($coupon['min_purchase_amount']) && $cart_total < $coupon['min_purchase_amount']) {
            $this->session->set_flashdata('error', 'Minimum purchase amount not met.');
            redirect('checkout');
        }

        if ($coupon['type'] === 'fixed') {
            $discount = floatval($coupon['dis_amount']);
        } elseif ($coupon['type'] === 'percentage') {
            $percentage = floatval($coupon['value']);
            if ($percentage <= 0 && isset($coupon['dis_amount']) && $coupon['dis_amount'] > 0) {
                $discount = floatval($coupon['dis_amount']);
            } else {
                $discount = $cart_total * ($percentage / 100);
                if (!empty($coupon['max_discount_amount'])) {
                    $discount = min($discount, floatval($coupon['max_discount_amount']));
                }
            }
        } else {
            $discount = 0;
        }

        $discount = min($discount, $cart_total);

        $coupon['calculated_discount'] = $discount;
        $this->session->set_userdata('coupon', $coupon);

        $this->session->set_flashdata('success', 'Coupon applied successfully! You saved ₹' . number_format($discount, 2));
        redirect('checkout');
    }

    public function remove_coupon() {
        $this->session->unset_userdata('coupon');
        $this->session->set_flashdata('success', 'Coupon removed.');
        redirect('checkout');
    }

    public function preview_order() {
        $user_id = $this->session->userdata('user_id');
        
        if (!$user_id) {
            redirect('user/login');
        }
    
        $user = $this->user_model->get_user($user_id);
        $cart_items = $this->cart_model->get_cart_items($user_id);
    
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('shop/cart');
        }
    
        $subtotal = array_sum(array_column($cart_items, 'subtotal'));
    
        $coupon = $this->session->userdata('coupon');
        $discount = isset($coupon['calculated_discount']) ? $coupon['calculated_discount'] : 0;
        $final_total = max($subtotal - $discount, 0);
    
        $wallet_balance = $user['wallet_balance'] ?? 0;
        $wallet_amount = 0;
    
        $use_wallet = $this->session->userdata('use_wallet');
        if ($use_wallet && $wallet_balance > 0) {
            $wallet_percentage_row = $this->db->get_where('settings', ['name' => 'Wallet Percentage'])->row();
            $wallet_percentage = $wallet_percentage_row ? (float)$wallet_percentage_row->value : 0;
        
            $max_wallet_use = ($wallet_percentage / 100) * $final_total;
            $wallet_amount = min($wallet_balance, $max_wallet_use);
            $final_total -= $wallet_amount;
        }
    
        $delivery_charge = 10;
        $final_total += $delivery_charge;
    
        $address_id = $this->input->post('address_id');
    
        if ($address_id) {
            $address = $this->address_model->get_user_address($address_id);
        } else {
            $address = $this->address_model->get_default_address($user_id);
        }
    
        $data = [
            'user'            => $user,
            'cart_items'      => $cart_items,
            'subtotal'        => $subtotal,
            'discount'        => $discount,
            'wallet_amount'   => $wallet_amount,
            'delivery_charge' => $delivery_charge,
            'final_total'     => $final_total,
            'address'         => $address
        ];
    
        $this->load->view('user/preview_order', $data);
    }

    public function confirm_preview() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('user/login');
        
        $use_wallet = $this->input->post('use_wallet') ? true : false;
        
        if ($use_wallet) {
            $this->session->set_userdata('use_wallet', true);
        } else {
            $this->session->unset_userdata('use_wallet');
        }
    
        redirect('checkout');
    }

}

