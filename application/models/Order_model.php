<?php
class Order_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_order_by_id($order_id) {
        $this->db->select('o.id, o.total, o.created_at, o.coupon_code, o.dis_amount, o.return_status, o.wallet_used, u.fullname');
        $this->db->from('orders o');
        $this->db->join('user u', 'o.user_id = u.id');
        $this->db->where('o.id', $order_id);
        $query = $this->db->get();
        return $query->row_array(); 
    }

    public function get_order_items($order_id) {
        $this->db->select('p.name, od.qty, od.price, od.return_status');
        $this->db->from('order_items od');
        $this->db->join('products p', 'od.product_id = p.id');
        $this->db->where('od.order_id', $order_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function create($data) {
        $this->db->insert('orders', $data);
        return $this->db->insert_id(); 
    }

    public function mark_as_returned($item_id) {
        $this->db->where('id', $item_id);
        $this->db->update('order_items', ['return_status' => 1]);
    }
    
    public function update_return_status_by_order($order_id, $status) {
        $this->db->where('order_id', $order_id);
        $this->db->where('return_status', 1);
        $this->db->update('order_items', ['return_status' => $status]);
    }

}
