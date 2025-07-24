<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OrderController extends CI_Controller {

    public function buy() {
        $user_id = $this->session->userdata('user_id');
        $cart_items = $this->Cart_model->get_cart_items($user_id);

        $coupon_code = $this->input->post('coupon_code');

        $dis_amount = 0;

        if (!empty($coupon_code)) {
            $coupon = $this->db->get_where('coupons', [
                'code'   => $coupon_code,
                'active' => 1
            ])->row_array();

            if ($coupon) {
                $total = 0;
                foreach ($cart_items as $item) {
                    $total += $item['qty'] * $item['price'];
                }

                if ($coupon['type'] === 'percentage') {
                    $dis_amount = min($total * ($coupon['value'] / 100), $coupon['max_discount_amount']);
                } else {
                    $dis_amount = $coupon['value'];
                }
            } else {
                $coupon_code = null;
            }
        }

        $order_id = $this->Cart_model->create_order($user_id, $cart_items, $coupon_code, $dis_amount);

        if ($order_id) {
            $this->Cart_model->clear_cart($user_id);

            redirect('order/details/' . $order_id);
        } else {
            $this->session->set_flashdata('error', 'Order failed. Please try again.');
            redirect('cart');
        }
    }
    
}
