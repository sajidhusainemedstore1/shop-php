<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['products'] = $this->product_model->get_all_products();
        $this->load->view('admin/product_view', $data);
    }

    public function cart() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('user/login');
        }

        $cart_items = $this->cart_model->get_cart_items($user_id);
        $data['cart_items'] = $cart_items;

        $coupon = $this->session->userdata('coupon');
        $data['coupon'] = $coupon;

        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['subtotal'];
        }

        if ($coupon) {
            $discount = isset($coupon['calculated_discount']) ? $coupon['calculated_discount'] : 0;
            $final_total = max($total - $discount, 0);
        } else {
            $discount = 0;
            $final_total = $total;
        }

        $data['discount'] = $discount;
        $data['final_total'] = $final_total;

        $user = $this->wallet_model->get_user($user_id);
        $data['user'] = $user;

        $wallet_use_amount = 0;
        $wallet_percentage = 0;

        if (!empty($user['wallet_balance']) && $user['wallet_balance'] > 0) {
            $wallet_setting = $this->db->where('name', 'Wallet Percentage')->get('settings')->row();
            if ($wallet_setting) {
                $wallet_percentage = (float)$wallet_setting->value;
            }

            $wallet_use_amount = round(($wallet_percentage / 100) * $final_total, 2);
            $wallet_use_amount = min($wallet_use_amount, $user['wallet_balance']);
        }

        $data['wallet_use_amount'] = $wallet_use_amount;

        $this->load->view('shop/cart', $data);
    }

    public function add_to_cart() {
        if (!$this->session->userdata('user_logged_in')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'error' => 'User not logged in']);
                return;
            } else {
                $this->session->set_userdata('redirect_after_login', 'user/home');
                redirect('user/login');
                return;
            }
        }

        $product_id = $this->input->post('product_id');
        $user_id = $this->session->userdata('user_id');

        if (!$product_id) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'error' => 'Product ID is required']);
                return;
            } else {
                show_error('Product ID is required.', 400);
            }
        }

        $product = $this->product_model->get_product($product_id);

        if (!$product) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'error' => 'Product not found']);
                return;
            } else {
                show_error('Product not found.', 404);
            }
        }

        $existing = $this->cart_model->get_cart_item($user_id, $product_id);

        if ($existing) {
            $this->cart_model->increment_qty($user_id, $product_id);
        } else {
            $success = $this->cart_model->add_to_cart($user_id, $product['id'], 1, $product['price']);
            if (!$success) {
                log_message('error', 'Add to cart failed: ' . print_r($this->db->error(), true));
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'error' => 'Failed to add product to cart']);
                    return;
                } else {
                    show_error('Failed to add product to cart.', 500);
                }
            }
        }

        if ($this->input->is_ajax_request()) {
            $cart_count = $this->cart_model->count_items($user_id);
            echo json_encode(['success' => true, 'message' => 'Item added to cart', 'cart_count' => $cart_count]);
        }
    }

    public function update_cart_ajax() {
        $item_id = $this->input->post('item_id');
        $qty = (int)$this->input->post('qty');
        $user_id = $this->session->userdata('user_id');

        if (!$item_id || !$qty || !$user_id) {
            echo json_encode(['success' => false, 'error' => 'Missing data']);
            return;
        }

        $cart_item = $this->db->get_where('cart', ['id' => $item_id, 'user_id' => $user_id])->row_array();

        if ($cart_item) {
            $subtotal = $qty * $cart_item['price'];

            $this->db->where('id', $item_id);
            $this->db->where('user_id', $user_id);
            $this->db->update('cart', [
                'qty' => $qty,
                'subtotal' => $subtotal
            ]);

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Cart item not found']);
        }
    }

    public function remove_item($id) {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('user/login');
        }

        $this->db->where('id', $id);
        $this->db->where('user_id', $user_id);
        $this->db->delete('cart');

        $this->session->set_flashdata('success', 'Item removed from cart.');
        redirect('shop/cart');
    }

    public function clear_cart() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('user/login');
        }

        $this->db->delete('cart', ['user_id' => $user_id]);

        $this->session->set_flashdata('success', 'Cart cleared.');
        redirect('shop/cart');
    }

    public function buy() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('user/login');
        }

        $use_wallet = $this->session->userdata('use_wallet') ? true : false;

        $cart_items = $this->cart_model->get_cart_items($user_id);
        if (empty($cart_items)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            redirect('shop/cart');
        }

        $total = array_sum(array_column($cart_items, 'subtotal'));

        $coupon = $this->session->userdata('coupon');
        $discount = $coupon['calculated_discount'] ?? 0;
        $final_total = max($total - $discount, 0);

        $user = $this->db->get_where('user', ['id' => $user_id])->row_array();
        $wallet_balance = $user['wallet_balance'] ?? 0;

        $wallet_used = 0;

        if ($use_wallet) {
            $wallet_percentage_row = $this->db
                ->where('name', 'Wallet Percentage')
                ->get('settings')
                ->row();

            $wallet_percentage = $wallet_percentage_row ? (float)$wallet_percentage_row->value : 0;

            $max_wallet_use = ($wallet_percentage / 100) * $final_total;

            $wallet_used = min($wallet_balance, $max_wallet_use);
            $final_total -= $wallet_used;

            if ($wallet_used > 0) {
                $this->db->where('id', $user_id)->update('user', [
                    'wallet_balance' => $wallet_balance - $wallet_used
                ]);
            }
        }

        $payment_method = $this->input->post('payment_method');

        if (!$payment_method) {
            $payment_method = 'COD';
        }

        $order_data = [
            'user_id'        => $user_id,
            'total'          => $total,
            'dis_amount'     => $discount,
            'wallet_used'    => $wallet_used,
            'paid_amount'    => $final_total,
            'payment_method' => ($final_total == 0 ? 'wallet' : $payment_method),
            'coupon_code'    => $coupon['code'] ?? NULL,
            'status'         => ($final_total == 0 ? 'paid' : 'pending'),
            'created_at'     => date('Y-m-d H:i:s')
        ];

        $this->db->insert('orders', $order_data);
        $order_id = $this->db->insert_id();

        foreach ($cart_items as $item) {
            $this->db->insert('order_items', [
                'order_id' => $order_id,
                'product_id' => $item['products_id'],
                'qty' => $item['qty'],
                'price' => $item['price']
            ]);
        }

        if (!empty($coupon['code'])) {
            $exists = $this->db->get_where('coupon_usages', [
                'coupon_code' => $coupon['code'],
                'user_id' => $user_id
            ])->row();
            
            if (!$exists) {
                $this->db->insert('coupon_usages', [
                    'coupon_code' => $coupon['code'],
                    'user_id' => $user_id,
                    'used_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->cart_model->clear_cart($user_id);
        $this->session->unset_userdata('coupon');

        $msg = 'Order placed successfully!';
        if ($wallet_used > 0) {
            $this->db->insert('wallet_transactions', [
                'user_id' => $user_id,
                'amount' => -$wallet_used,
                'description' => 'Order No.' . $order_id,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        if ($wallet_used > 0) {
            $msg .= ' ₹' . number_format($wallet_used, 2) . ' used from wallet.';
        }
        if ($final_total > 0) {
            $msg .= ' Remaining to pay: ₹' . number_format($final_total, 2);
        }

        $this->session->set_flashdata('success', $msg);
        redirect('user/my_orders');
    }

    public function apply_coupon() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('user/login');

        $coupon_code = $this->input->post('coupon_code', true);
        $now = date('Y-m-d H:i:s');

        $this->db->where('code', $coupon_code);
        $this->db->where('active', 1);
        $this->db->where('expiry_date >=', date('Y-m-d'));
        $coupon = $this->db->get('coupons')->row_array();

        if (!$coupon) {
            $this->session->set_flashdata('error', 'Invalid or expired coupon code.');
            redirect('shop/cart');
        }

        $start_datetime = $coupon['start_date'] . ' ' . ($coupon['start_time'] ?? '00:00:00');
        $expiry_datetime = $coupon['expiry_date'] . ' ' . ($coupon['expiry_time'] ?? '23:59:59');

        if ($now < $start_datetime) {
            $this->session->set_flashdata('error', 'Coupon not active yet.');
            redirect('shop/cart');
        }

        if ($now > $expiry_datetime) {
            $this->session->set_flashdata('error', 'Coupon expired.');
            redirect('shop/cart');
        }

        if (!empty($coupon['number_of_users'])) {
            $this->db->select('COUNT(DISTINCT user_id) AS used_count');
            $this->db->where('coupon_code', $coupon_code);
            $usage_result = $this->db->get('coupon_usages')->row();
            if ($usage_result && $usage_result->used_count >= $coupon['number_of_users']) {
                $this->session->set_flashdata('error', 'This coupon has reached its usage limit.');
                redirect('shop/cart');
            }
        }

        if ($coupon['customer_filter'] === 'registered_date') {
            $user = $this->db->get_where('user', ['id' => $user_id])->row_array();
            if (isset($user['created_at']) && strtotime($user['created_at']) > strtotime('-7 days')) {
                $this->session->set_flashdata('error', 'This coupon is not available for your account yet.');
                redirect('shop/cart');
            }
        }

        $cart_items = $this->cart_model->get_cart_items($user_id);
        $cart_total = array_sum(array_column($cart_items, 'subtotal'));

        if (!empty($coupon['min_purchase_amount']) && $cart_total < $coupon['min_purchase_amount']) {
            $this->session->set_flashdata('error', 'Minimum purchase amount not met.');
            redirect('shop/cart');
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
        redirect('shop/cart');
    }

    public function remove_coupon() {
        $this->session->unset_userdata('coupon');
        $this->session->set_flashdata('success', 'Coupon removed.');
        redirect('shop/cart');
    }

    public function get_cart_count() {
    $user_id = $this->session->userdata('user_id');

    // If not logged in → cart count = 0
    $count = $user_id ? $this->cart_model->count_items($user_id) : 0;

    $this->output
         ->set_content_type('application/json')
         ->set_output(json_encode(['count' => $count]));
}

}
