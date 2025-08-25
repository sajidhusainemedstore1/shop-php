<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class User extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $user = $this->user_model->check_user($username, $password);

        if ($user) {
            $this->session->set_userdata([
                'user_logged_in' => true,
                'user_id' => $user['id'],
                'user_email' => $user['email']
            ]);

            $redirect_url = $this->session->userdata('redirect_after_login');
            $this->session->unset_userdata('redirect_after_login');
            if ($redirect_url) {
                redirect($redirect_url);
            } else {
                redirect('user/home');
            }

        } else {
            $data['error'] = 'Invalid login credentials';
            $this->load->view('user/login', $data);
        }
    }

    public function login() {
        $this->load->view('user/login');
    }

    public function logout() {
        if ($this->session->userdata('user_logged_in')) {
            $this->session->unset_userdata('user_logged_in');
            redirect('user/home');
        }
    }

    public function signup() {

        $this->form_validation->set_rules('fullname', 'Fullname', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('Con_Password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $this->load->view('user/signup', $data);
        } else {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $data['error'] = $this->upload->display_errors();
                $this->load->view('user/signup', $data);
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];

                $fullname = $this->input->post('fullname');
                $email = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $password = $this->input->post('password');

                $this->db->insert('user', [
                    'fullname' => $fullname,
                    'email' => $email,
                    'mobile' => $mobile,
                    'password' => $password,
                    'image' => $image_name
                ]);

                redirect('user/login');
            }
        }
        $recaptchaResponse = $this->input->post('g-recaptcha-response');
        $secretKey = "6LezkawrAAAAAHFiXnzh07vcwapa3Ei3MvbUEtO4";
        $userIp = $this->input->ip_address();
        
        $verifyResponse = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret='
            . $secretKey . '&response=' . $recaptchaResponse . '&remoteip=' . $userIp
        );
    
        $responseData = json_decode($verifyResponse);
    
    }

    public function home() {
        $user_id = $this->session->userdata('user_id');
        $data['products'] = $this->product_model->get_all_products();
        $data['user_logged_in'] = $this->session->userdata('user_logged_in') ? true : false;
        $data['admin_logged_in'] = $this->session->userdata('admin_logged_in') ? true : false;
        $data['user'] = $this->user_model->get_user_by_id($user_id);

        $this->load->view('user/home', $data);
    }

    public function user_profile() {
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            redirect('user/login');
        }

        $data['user_info'] = $this->user_model->get_user_by_id($user_id);
        $this->load->view('user/profile', $data);
    }

    public function my_orders() {
        if (!$this->session->userdata('user_logged_in')) {
            $this->session->set_userdata('redirect_after_login', 'user/my_orders');
            redirect('user/login');
        }

        $user_id = $this->session->userdata('user_id');
        $data['orders'] = $this->user_model->get_user_orders($user_id);
        $this->load->view('user/my_orders', $data);
    }

    public function view_order($order_id) {
        if (!$this->session->userdata('user_logged_in')) {
            $this->session->set_userdata('redirect_after_login', 'user/view_order/' . $order_id);
            redirect('user/login');
        }
        $order = $this->user_model->get_order_by_id($order_id);
        $items = $this->user_model->get_order_items($order_id);
    
        if (!$order) {
            show_error('Order not found');
        }
    
        $coupons = ['dis_amount' => 0];
    
        if (!empty($order['coupon_code'])) {
            $coupon = $this->db->get_where('coupons', ['code' => $order['coupon_code'], 'active' => 1])->row_array();
        
            if ($coupon) {
                if ($coupon['type'] === 'fixed') {
                    $coupons['dis_amount'] = (float) $coupon['value'];
                } elseif ($coupon['type'] === 'percentage') {
                    $total = array_sum(array_map(function ($item) {
                        return $item['qty'] * $item['price'];
                    }, $items));
                
                    $calculated_discount = $total * ($coupon['value'] / 100);
                    $max_discount = $coupon['max_discount_amount'] ?? PHP_FLOAT_MAX;
                    $coupons['dis_amount'] = min($calculated_discount, $max_discount);
                }
            }
        }
    
        $data = [
            'order' => $order,
            'items' => $items,
            'coupons' => $coupons
        ];
    
        $this->load->view('user/order_detail', $data);
    }
    
    public function view($user_id) {
        $data['user'] = $this->user_model->get_user($user_id);
        $data['transactions'] = $this->wallet_model->get_transactions($user_id);
        $this->load->view('user/view_user', $data);
    }

    public function add_address() {
        $this->load->view('user/add_address');
    }

    public function save_address() {
        $user_id = $this->session->userdata('user_id');
        $id = $this->input->post('id');

        $data = [
            'user_id' => $user_id,
            'name' => $this->input->post('name'),
            'mobile' => $this->input->post('mobile'),
            'email' => $this->input->post('email'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'pincode' => $this->input->post('pincode'),
            'is_default' => $this->input->post('is_default') ? 1 : 0
        ];

        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->where('user_id', $user_id);
            $this->db->update('addresses', $data);
            $this->session->set_flashdata('success', 'Address updated successfully.');
        } else {
            $this->db->insert('addresses', $data);
            $this->session->set_flashdata('success', 'Address added successfully.');
        }

        redirect('checkout');
    }

    public function edit_address($id) {
        if (!$this->session->userdata('user_id')) {
            redirect('checkout');
        }
    
        $data['addresses'] = $this->address_model->get_address_by_id($id);
    
        if (empty($data['addresses'])) {
            show_404();
        }
    
        $this->load->view('user/edit_address', $data);
    }

    public function delete_address($id) {
        $user_id = $this->session->userdata('user_id');
        
        $address = $this->db->get_where('addresses', ['id' => $id, 'user_id' => $user_id])->row();
        if (!$address) {
            $this->session->set_flashdata('error', 'Address not found or unauthorized access.');
            redirect('checkout');
        }

        $this->db->delete('addresses', ['id' => $id]);
        $this->session->set_flashdata('success', 'Address removed successfully.');
        redirect('checkout');
    }

    public function return_order($order_id) {
        if (!$order_id || !is_numeric($order_id)) {
            show_error('Invalid order ID');
        }
    
        $this->db->where('id', $order_id)->update('orders', ['return_status' => 'requested']);
        $this->session->set_flashdata('success', 'Return request submitted.');
        redirect('user/my_orders');
    }

    public function request_return($order_id) {
        $return_items = $this->input->post('return_items');
        
        if (empty($return_items)) {
            $this->session->set_flashdata('error', 'Please select at least one item to return.');
            redirect('user/order_details/'.$order_id);
            return;
        }

        $this->db->where_in('id', $return_items)
                 ->where('order_id', $order_id)
                 ->update('order_items', ['return_status' => 'requested']);

        $this->db->where('id', $order_id)
                 ->update('orders', ['return_status' => 'requested']);

        $this->session->set_flashdata('success', 'Return request submitted for selected items.');
        redirect('user/my_orders');
    }

    public function re_order($order_id) {
        if (!$this->session->userdata('user_id')) {
            redirect('user/login');
        }
    
        $user_id = $this->session->userdata('user_id');
    
        $order_items = $this->order_model->get_order_items($order_id);

        if (!empty($order_items)) {
            foreach ($order_items as $item) {
                $cart_item = $this->cart_model->get_cart_item($user_id, $item['product_id']);
                if ($cart_item) {
                    $this->cart_model->increment_qty($user_id, $item['product_id'], $item['qty']);
                } else {
                    $this->cart_model->add_to_cart($user_id, $item['product_id'], $item['qty'], $item['price']);
                }
            }
            $this->session->set_flashdata('success', 'Order products added to your cart successfully!');
        } else {
            $this->session->set_flashdata('error', 'No products found in this order.');
        }
    
        redirect('shop/cart');
    }

    public function aboutus() {
        $this->load->view('user/aboutus');
    }

    public function send() {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('message', 'Message', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('user/contactus');
        } else {
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $email = $this->input->post('email');
            $message = $this->input->post('message');
            
            $this->db->insert('contactus', [
                'name' => $name,
                'mobile' => $mobile,
                'email' => $email,
                'message' => $message,
            ]);
        
            $recaptcha_response = $this->input->post('g-recaptcha-response');
            $secret_key = $this->config->item('google_recaptcha_secret_key');
            $user_ip = $this->input->ip_address();

            $api_url = 'https://www.google.com/recaptcha/api/siteverify';
            $post_data = array(
                'secret'   => $secret_key,
                'response' => $recaptcha_response,
                'remoteip' => $user_ip
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $status = json_decode($response, true);

            if ($status['success']) {
                echo "Form submission successful! Email can be sent now.";
                $this->load->view('user/contactus');
            } else {
                $this->load->view('user/contactus');
            }
        }
    }
}
