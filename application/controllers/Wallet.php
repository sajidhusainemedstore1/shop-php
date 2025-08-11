<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('admin_id')) {
            redirect('admin/login');
        }
    }

    public function history() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) redirect('user/login');

        $data['transactions'] = $this->wallet_model->get_transactions($user_id);
        $data['user'] = $this->user_model->get_user($user_id);
        $this->load->view('wallet/history', $data);
    }

    public function recharge() {
        $user_id = $this->session->userdata('user_id');
        $amount = $this->input->post('amount');

        if (!$user_id || $amount <= 0) {
            $this->session->set_flashdata('error', 'Invalid amount.');
            redirect('user/home');
        }

        $this->wallet_model->add_balance($user_id, $amount, 'Wallet Recharge');
        $this->session->set_flashdata('success', 'Wallet recharged successfully!');
        redirect('user/home');
    }

    public function wallet_dashboard() {
        $this->load->database();
        $this->db->select('id, fullname, wallet_balance, email');
        $query = $this->db->get('user');
        $data['users'] = $query->result_array();

        $wallet_percentage = $this->db
            ->where('name', 'Wallet Percentage')
            ->get('settings')
            ->row('value');
            
        $data['wallet_percentage'] = $wallet_percentage;

        $this->load->view('wallet/wallet_dashboard', $data);

        if (!$this->session->userdata('admin_id')) {
            redirect('admin/login');
        }
    }
    
    public function view($user_id) {
        $data['user'] = $this->user_model->get_user($user_id);
        $data['transactions'] = $this->wallet_model->get_transactions($user_id);
        $this->load->view('wallet/view_user', $data);
    }

    public function edit($user_id) {
        $data['user'] = $this->user_model->get_user($user_id);
        $this->load->view('wallet/edit_user', $data);
    }

    public function update_balance($user_id) {
        $amount = (float)$this->input->post('amount');
        $type = $this->input->post('type');
        $description = $this->input->post('description') ?: ucfirst($type) . ' by Admin';
        
        if ($amount <= 0 || !in_array($type, ['credit', 'debit'])) {
            $this->session->set_flashdata('error', 'Invalid input.');
            redirect('wallet/edit/' . $user_id);
            return;
        }
    
        $user = $this->db->get_where('user', ['id' => $user_id])->row_array();
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('wallet/wallet_dashboard');
            return;
        }
    
        $new_balance = ($type === 'credit') 
            ? $user['wallet_balance'] + $amount 
            : $user['wallet_balance'] - $amount;
    
        if ($new_balance < 0) {
            $this->session->set_flashdata('error', 'Insufficient wallet balance for debit.');
            redirect('wallet/edit/' . $user_id);
            return;
        }

        $this->db->where('id', $user_id);
        $this->db->update('user', ['wallet_balance' => $new_balance]);
    
        $this->db->insert('wallet_transactions', [
            'user_id' => $user_id,
            'amount' => ($type === 'credit') ? $amount : -$amount,
            'description' => $description,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Wallet updated successfully!');
        redirect('wallet/wallet_dashboard');
    }
 
    public function logout() {
        $this->session->unset_userdata('admin_id');
        $this->session->sess_destroy();
        redirect('admin/login');
    }

    public function update_wallet_percentage() {
        $this->load->database();
        $percentage = (int)$this->input->post('wallet_percentage');

        if ($percentage < 0 || $percentage > 40) {
            $this->session->set_flashdata('error', 'Invalid percentage value.');
            redirect('wallet/wallet_dashboard');
            return;
        }

        $exists = $this->db->get_where('settings', ['name' => 'Wallet Percentage'])->row();

        if ($exists) {
            $this->db->where('name', 'Wallet Percentage')->update('settings', ['value' => $percentage]);
        } else {
            $this->db->insert('settings', [
                'name' => 'Wallet Percentage',
                'value' => $percentage
            ]);
        }

        $this->session->set_flashdata('success', 'Wallet percentage updated successfully.');
        redirect('wallet/wallet_dashboard');
    }

}
