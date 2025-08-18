<?php

class Address_model extends CI_Model {

    public function get_addresses($user_id) {
        return $this->db->where('user_id', $user_id)->order_by('is_default', 'DESC')->get('addresses')->result_array();
    }

    public function get_default_address($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->where('is_default', 1)
                        ->get('addresses')
                        ->row_array();
    }

    public function get_all_addresses($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->order_by('is_default', 'DESC')
                        ->get('addresses')
                        ->result_array();
    }

    public function get_user_address($address_id) {
        return $this->db->get_where('addresses', ['id' => $address_id])->row_array();
    }

    public function get_address_by_id($id) {
        return $this->db->where('id', $id)
                    ->get('addresses')
                    ->row_array();
    }

    public function delete_address($id) {
        return $this->db->where('id', $id)->delete('addresses');
    }
}
?>