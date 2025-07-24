<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function check_admin($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('admin');
        return $query->row_array();
    }

    public function is_email_exists($email) {
        $query = $this->db->get_where('user', ['email' => $email]);
        return $query->num_rows() > 0;
    }

    public function insert_user($data) {
        return $this->db->insert('user', $data);
    }

    public function get_all_user() {
        $query = $this->db->get('user'); 
        return $query->result_array();
    }   

    public function get_user_by_id($id) {
        return $this->db->get_where('user', ['id' => $id])->row_array();
    }

    public function update_user($id, $data) {
        return $this->db->where('id', $id)->update('user', $data);
    }

    public function delete_user($id) {
        return $this->db->where('id', $id)->delete('user');
    }

    public function get_all_orders() {
        $this->db->select('o.*, u.fullname');
        $this->db->from('orders o');
        $this->db->join('user u', 'u.id = o.user_id', 'left');
        $this->db->order_by('o.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_order_by_id($order_id) {
        $this->db->select('o.id as order_id, o.created_at, o.total, u.fullname');
        $this->db->from('orders o');
        $this->db->join('user u', 'u.id = o.user_id', 'left');
        $this->db->where('o.id', $order_id);
        return $this->db->get()->row_array();
    }
    
    public function get_order_items($order_id) {
        $this->db->select('oi.*, p.name');
        $this->db->from('order_items oi');
        $this->db->join('products p', 'p.id = oi.product_id', 'left');
        $this->db->where('oi.order_id', $order_id);
        return $this->db->get()->result_array();
    }
}
