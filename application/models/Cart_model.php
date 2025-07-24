<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model {

    public function get_cart_items($user_id) {
        $this->db->select('c.*, p.name, (c.qty * c.price) AS subtotal');
        $this->db->from('cart c');
        $this->db->join('products p', 'c.products_id = p.id');
        $this->db->where('c.user_id', $user_id);
        return $this->db->get()->result_array();
    }

    public function get_cart_item($user_id, $product_id) {
        return $this->db->get_where('cart', [
            'user_id'     => $user_id,
            'products_id' => $product_id
        ])->row_array();
    }

    public function add_to_cart($user_id, $product_id, $qty, $price) {
        return $this->db->insert('cart', [

            'user_id'     => $user_id,
            'products_id' => $product_id,
            'qty'         => $qty,
            'price'       => $price
        ]);
    }

    public function update_qty($user_id, $product_id, $qty) {
        $this->db->where('user_id', $user_id);
        $this->db->where('products_id', $product_id);
        return $this->db->update('cart', ['qty' => $qty]);
    }

    public function increment_qty($user_id, $product_id, $increment = 1) {
        $this->db->set('qty', 'qty + ' . (int)$increment, false);
        $this->db->where('user_id', $user_id);
        $this->db->where('products_id', $product_id);
        return $this->db->update('cart');
    }

    public function remove_item($user_id, $cart_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('id', $cart_id);
        return $this->db->delete('cart');
    }

    public function clear_cart($user_id) {
        return $this->db->delete('cart', ['user_id' => $user_id]);
    }

    public function create_order($user_id, $cart_items, $applied_coupon_code = null, $dis_amount = 0) {
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['qty'] * $item['price'];
        }
    
        $this->db->insert('orders', [
            'user_id'     => $user_id,
            'total'       => $total,
            'coupon_code' => $applied_coupon_code,
            'dis_amount'  => $dis_amount,
            'created_at'  => date('Y-m-d H:i:s')
        ]);
    
        $order_id = $this->db->insert_id();
    
        foreach ($cart_items as $item) {
            $this->db->insert('order_items', [
                'order_id'   => $order_id,
                'product_id' => $item['products_id'],
                'qty'        => $item['qty'],
                'price'      => $item['price'],
            ]);
        }
    
        return $order_id;
    }

    public function get_all_cart_items() {
        $this->db->select('c.*, p.name as product_name, u.fullname, (c.qty * c.price) AS subtotal');
        $this->db->from('cart c');
        $this->db->join('products p', 'c.products_id = p.id', 'left');
        $this->db->join('user u', 'c.user_id = u.id', 'left');
        return $this->db->get()->result_array();
    } 
    
    public function count_items($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('cart');
    }

    public function get_cart_total($user_id) {
        $this->db->select('SUM(qty * price) AS cart_total', false);
        $this->db->from('cart');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->row()->cart_total ?? 0;
    }

}
