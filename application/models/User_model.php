<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function check_user($username, $password) {
        $this->db->where('fullname', $username);
        $this->db->where('password', $password);
        $query = $this->db->get('user');
        return $query->row_array();
    }

    public function is_email_exists($email) {
        $query = $this->db->get_where('user', ['email' => $email]);
        return $query->num_rows_array() > 0;
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

    public function get_user_orders($user_id) {
        return $this->db->order_by('created_at', 'DESC')
                        ->get_where('orders', ['user_id' => $user_id])
                        ->result_array();
    }

    public function get_order_items($order_id) {
        $this->db->select('oi.*, p.name');
        $this->db->from('order_items oi');
        $this->db->join('products p', 'oi.product_id = p.id', 'left');
        $this->db->where('oi.order_id', $order_id);
        return $this->db->get()->result_array();
    }

    public function get_order_by_id($order_id) {
        $this->db->select('orders.*, user.fullname');
        $this->db->from('orders');
        $this->db->join('user', 'user.id = orders.user_id');
        $this->db->where('orders.id', $order_id);
        return $this->db->get()->row_array();
    }

    public function get_user($user_id) {
        return $this->db->get_where('user', ['id' => $user_id])->row_array();
    }

}

?>