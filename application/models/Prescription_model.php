<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_model extends CI_Model {

    protected $table = 'prescriptions';

    public function __construct() {
        parent::__construct();
    }

    public function get_by_user($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->order_by('id', 'DESC')
                        ->get($this->table)
                        ->result_array();
    }

    public function get($id) {
        return $this->db->where('id', $id)->get($this->table)->row_array();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function delete($id) {
        return $this->db->where('id', $id)->delete($this->table);
    }
}
