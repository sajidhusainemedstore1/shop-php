<?php
class Wallet_model extends CI_Model {

    public function get_transactions($user_id) {
        return $this->db->where('user_id', $user_id)
                        ->order_by('created_at', 'DESC')
                        ->get('wallet_transactions')
                        ->result_array();
    }

    public function add_balance($user_id, $amount, $description = '') {
        $this->db->set('wallet_balance', 'wallet_balance + ' . floatval($amount), FALSE)
                 ->where('id', $user_id)
                 ->update('user');

        $this->db->insert('wallet_transactions', [
            'user_id' => $user_id,
            'type' => 'credit',
            'amount' => $amount,
            'description' => $description
        ]);
    }

    public function deduct_balance($user_id, $amount, $description = '') {
        $this->db->set('wallet_balance', 'wallet_balance - ' . floatval($amount), FALSE)
                 ->where('id', $user_id)
                 ->update('user');

        $this->db->insert('wallet_transactions', [
            'user_id' => $user_id,
            'type' => 'debit',
            'amount' => $amount,
            'description' => $description
        ]);
    }

    public function get_user($user_id) {
        return $this->db->get_where('user', ['id' => $user_id])->row_array();
    }

    public function get_balance($user_id) {
        $this->db->select('wallet_balance');
        $this->db->from('user');
        $this->db->where('id', $user_id);
        $result = $this->db->get()->row_array();
        return $result ? floatval($result['wallet_balance']) : 0.00;
    }

}
