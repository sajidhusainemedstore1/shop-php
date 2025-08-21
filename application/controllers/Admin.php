<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function login() {
        $this->load->view('admin/login');
    }

    public function login_check() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->admin_model->check_admin($username, $password);

        if ($admin) {
            $this->session->set_userdata([
            'admin_logged_in' => true,
            'admin_id' => $admin['id'],
            'admin_username' => $admin['username']
        ]);

            redirect('admin/dashboard');
        } else {
            $data['error'] = 'Invalid login credentials';
            $this->load->view('admin/login', $data);
        }
    }

    public function logout() {
    if ($this->session->userdata('admin_logged_in')) {
        $this->session->unset_userdata(['admin_logged_in', 'admin_id', 'admin_username']);
        redirect('admin/login');
    }
}


    public function signup()  {
        
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Fullname', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('Con_Password', 'Confirm Password', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $data['error'] = validation_errors();
            $this->load->view('admin/signup', $data);
        } else {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 2048; 

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $data['error'] = $this->upload->display_errors();
                $this->load->view('admin/signup', $data);
            } else {
                $upload_data = $this->upload->data();
                $image_name = $upload_data['file_name'];

                $username = $this->input->post('username');
                $email = $this->input->post('email');
                $mobile = $this->input->post('mobile');
                $password = $this->input->post('password');

                $this->db->insert('admin', [
                    'username' => $username,
                    'email' => $email,
                    'mobile' => $mobile,
                    'password' => $password,
                    'image' => $image_name 
                ]);

                redirect('admin/login');
            }
        }
    }

    public function userlist() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['user'] = $this->admin_model->get_all_user();

        $this->load->view('admin/userlist', $data);
    }

    public function edit($id) {
    if (!$this->session->userdata('admin_logged_in')) {
        redirect('admin/login');
    }

    $data['user'] = $this->admin_model->get_user_by_id($id);

    if (empty($data['user'])) {
        show_404();
    }

    $this->load->view('admin/edit_user', $data);
}

    public function update($id) {
        $this->form_validation->set_rules('fullname', 'Fullname', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|numeric|min_length[10]|max_length[15]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }   

        $fullname = $this->input->post('fullname');
        $email = $this->input->post('email');
        $mobile = $this->input->post('mobile');

        $update_data = [
            'fullname' => $fullname,
            'email' => $email,
            'mobile' => $mobile
        ];

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = '*';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $update_data['image'] = $upload_data['file_name'];
            } else {
                $data['error'] = $this->upload->display_errors();
                $data['user'] = $this->admin_model->get_user_by_id($id);
                $this->load->view('admin/edit_user', $data);
                return;
            }
        }

    $this->admin_model->update_user($id, $update_data);
    redirect('admin/userlist');
    }


    public function delete($id) {
        if(!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $this->admin_model->delete_user($id);
        redirect('admin/userlist');
    }

    public function product_list() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['products'] = $this->product_model->get_all_products();
        $this->load->view('admin/product_list', $data);
    }

    public function add_product() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
        $this->load->view('admin/add_product');
    }

    public function save_product() {
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('image')) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('admin/add_product', $data);
        } else {
            $upload_data = $this->upload->data();
            $image = $upload_data['file_name'];

            $product_data = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('description'),
                'price' => $this->input->post('price'),
                'image' => $image
            ];

            $this->product_model->insert_product($product_data);
            redirect('admin/product_list');
        }
    }

    public function edit_product($id) {
        $data['product'] = $this->product_model->get_product($id);
        $this->load->view('admin/edit_product', $data);
    }

    public function update_product($id) {
        $product_data = [
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'price' => $this->input->post('price')
        ];

        if (!empty($_FILES['image']['name'])) {
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                $upload_data = $this->upload->data();
                $product_data['image'] = $upload_data['file_name'];
            }
        }

        $this->product_model->update_product($id, $product_data);
        redirect('admin/product_list');
    }

    public function delete_product($id) {
        $this->product_model->delete_product($id);
        redirect('admin/product_list');
    }

    public function dashboard() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['total_users'] = count($this->admin_model->get_all_user());
        $data['total_products'] = count($this->product_model->get_all_products());
        $data['total_coupons'] = count($this->coupon_model->get_all_coupons());


        $this->load->view('admin/dashboard', $data);
    }

    public function product_view() {
        $data['products'] = $this->product_model->get_all_products();
        $this->load->view('admin/product_view', $data);
    }

    public function user_products() {
        $data['products'] = $this->product_model->get_all_products();
        $this->load->view('user/product_list', $data);
    }

    public function cart_list() {
        $this->load->model('Cart_model');

        $data['cart'] = $this->Cart_model->get_all_cart_items(); 
        $this->load->view('admin/cart_list', $data);
    }

    public function delete_cart($id) {
        $this->db->delete('cart', ['id' => $id]);
        $this->session->set_flashdata('success', 'Cart item deleted.');
        redirect('admin/cart_list');
    }

    public function edit_cart($id) {
        $this->load->model('Cart_model');

        if ($this->input->post()) {
            $qty   = (int)$this->input->post('qty');
            $price = (float)$this->input->post('price');

            $this->db->update('cart', ['qty' => $qty, 'price' => $price], ['id' => $id]);
            $this->session->set_flashdata('success', 'Cart item updated.');
            redirect('admin/cart_list');
        }

        $data['cart_item'] = $this->db->get_where('cart', ['id' => $id])->row_array();
        $this->load->view('admin/edit_cart', $data);
    }

    public function get_cart_items_ajax() {
        $this->load->model('Cart_model');
        $cart_items = $this->Cart_model->get_all_cart_items();
        echo json_encode($cart_items);
    }
    
    public function delete_cart_ajax($id) {
        $this->db->delete('cart', ['id' => $id]);
        echo json_encode(['status' => 'success']);
    }
    
    public function update_cart_ajax() {
        $item_id = $this->input->post('item_id');
        $qty = (int) $this->input->post('qty');
        $user_id = $this->session->userdata('user_id');
    
        if (!$item_id || !$qty || !$user_id) {
            echo json_encode(['success' => false]);
            return;
        }
    
        $cart_item = $this->db->get_where('cart', [
            'id' => $item_id,
            'user_id' => $user_id
        ])->row_array();
        
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
            echo json_encode(['success' => false]);
        }
    }

    public function order_list() {
        $data['orders'] = $this->admin_model->get_all_orders();
        $this->load->view('admin/order_list', $data);
    }

    public function order_detail($order_id) {
        $order = $this->order_model->get_order_by_id($order_id);
        $items = $this->order_model->get_order_items($order_id);
        
        if (!$order) {
            show_404();
        }
    
        $data['order'] = $order;
        $data['items'] = $items;
    
        $data['show_buttons'] = (isset($order['return_status']) && $order['return_status'] === 'requested');
    
        $this->load->view('admin/order_detail', $data);
    }

    public function invoice($order_id) {
        $this->output->enable_profiler(false);
        ob_clean();

        $order = $this->admin_model->get_order_by_id($order_id);
        $items = $this->admin_model->get_order_items($order_id);

        if (!$order || empty($items)) {
            show_404();
        }

        $pdf = new Pdf();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $pdf->Cell(0, 10, "ORDER RECEIPT", 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->Cell(0, 10, "Receipt No: #" . $order['order_id'], 0, 1);
        $pdf->Cell(0, 10, "Order Date: " . date('d M Y', strtotime($order['created_at'])), 0, 1);
        $pdf->Cell(0, 10, "Username: " . $order['fullname'], 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('', 'B');
        $pdf->Cell(70, 8, "Product", 1);
        $pdf->Cell(30, 8, "Qty", 1, 0, 'C');
        $pdf->Cell(40, 8, "Price (₹)", 1, 0, 'R');
        $pdf->Cell(40, 8, "Subtotal (₹)", 1, 1, 'R');

        $pdf->SetFont('', '');
        $total = 0;
        foreach ($items as $item) {
            $subtotal = $item['qty'] * $item['price'];
            $total += $subtotal;

            $pdf->Cell(70, 8, $item['name'], 1);
            $pdf->Cell(30, 8, $item['qty'], 1, 0, 'C');
            $pdf->Cell(40, 8, number_format($item['price'], 2), 1, 0, 'R');
            $pdf->Cell(40, 8, number_format($subtotal, 2), 1, 1, 'R');
        }

        $pdf->SetFont('', 'B');
        $pdf->Cell(140, 10, "Total", 1);
        $pdf->Cell(40, 10, "₹" . number_format($total, 2), 1, 1, 'R');

        $pdf->Output('Invoice_' . $order_id . '.pdf', 'I');
    }

    public function export_orders_excel()  {
        if(!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $user_id = $this->session->userdata('user_id');
        $orders = $this->admin_model->get_all_orders($user_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Receipt No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Total (₹)');
        $sheet->setCellValue('D1','Order Date');
        $row = 2;

        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $order['id']);
            $sheet->setCellValue('B' . $row, $order['fullname']);
            $sheet->setCellValue('c' . $row, $order['total']);
            $sheet->setCellValue('D' . $row, date('d M Y H:i A', strtotime($order['created_at'])));
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="my_orders.xlsx"');
        header('Cache-Control: max-age=0');

        ob_clean();
        flush();

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function add_coupon() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $this->form_validation->set_rules('code', 'Promo Code', 'required|alpha_numeric|is_unique[coupons.code]');
        $this->form_validation->set_rules('discount_type', 'Discount Type');
        $this->form_validation->set_rules('discount_value', 'Discount Value');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/add_coupon_form');
        } else {
            $data = [
                'code' => $this->input->post('code', true),
                'number_of_users' => $this->input->post('number_of_users', true) ?: null,
                'redemption_limit' => $this->input->post('redemption_limit', true),
                'description' => $this->input->post('description', true),
                'terms_conditions' => $this->input->post('terms', true),
                'customer_filter' => $this->input->post('customer_filter', true),
                'type' => $this->input->post('discount_type', true),
                'value' => $this->input->post('discount_value', true),
                'max_discount_amount' => $this->input->post('max_discount', true) ?: null,
                'min_purchase_amount' => $this->input->post('min_purchase', true) ?: null,
                'dis_amount' => $this->input->post('dis_amount', true) ?: null,
                'start_date' => $this->input->post('start_date', true),
                'start_time' => $this->input->post('start_time', true),
                'expiry_date' => $this->input->post('expiry_date', true),
                'expiry_time' => $this->input->post('expiry_time', true),
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->insert('coupons', $data);

            $this->session->set_flashdata('success', 'Coupon added successfully!');
            redirect('admin/coupons');
        }
    }

    public function coupons() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['coupons'] = $this->coupon_model->get_all_coupons();
        $this->load->view('admin/coupons_list', $data);
    }

    public function edit_coupon($code = null) {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $data['coupon'] = $this->db->get_where('coupons', ['code' => $code])->row_array();

        if ($code === null) {
            show_404();
        }

        $this->form_validation->set_rules('code', 'Promo Code', 'required|alpha_numeric');
        $this->form_validation->set_rules('discount_type', 'Discount Type');
        $this->form_validation->set_rules('discount_value', 'Discount Value');
        $this->form_validation->set_rules('start_date', 'Start Date', 'required');
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('admin/edit_coupon', $data);
        } else {
            $expiry_datetime_str = $this->input->post('expiry_date') . ' ' . $this->input->post('expiry_time');
            $expiry_datetime = strtotime($expiry_datetime_str);

            $current_datetime = time();

            $status = ($expiry_datetime > $current_datetime) ? 1 : 0;

            $update_data = [
                'code' => $this->input->post('code'),
                'number_of_users' => $this->input->post('number_of_users') ?: null,
                'redemption_limit' => $this->input->post('redemption_limit'),
                'custom_redemption_limit' => $this->input->post('custom_redemption_limit'),
                'description' => $this->input->post('description'),
                'terms_conditions' => $this->input->post('terms'),
                'customer_filter' => $this->input->post('customer_filter'),
                'type' => $this->input->post('discount_type'),
                'value' => $this->input->post('discount_value'),
                'max_discount_amount' => $this->input->post('max_discount') ?: null,
                'min_purchase_amount' => $this->input->post('min_purchase') ?: null,
                'dis_amount' => $this->input->post('dis_amount') ?: null,
                'start_date' => $this->input->post('start_date'),
                'start_time' => $this->input->post('start_time'),
                'expiry_date' => $this->input->post('expiry_date'),
                'expiry_time' => $this->input->post('expiry_time'),
                'active' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if ($this->coupon_model->edit_coupons($code, $update_data)) {
                $this->session->set_flashdata('success', 'Coupon updated successfully!');
            } else {
                $this->session->set_flashdata('error', 'Coupon update failed.');
            }

            redirect('admin/coupons');
        }
    }

    public function delete_coupon($code) {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        $this->db->delete('coupons', ['code' => $code]);
        $this->session->set_flashdata('success', 'Coupon deleted successfully!');
        redirect('admin/coupons');
    }

    public function approve_return($order_id) {
        $this->db->where('id', $order_id)->update('orders', [
            'return_status' => 'approved'
        ]);
        $this->session->set_flashdata('success', 'Return request approved.');
        redirect('admin/order_detail/' . $order_id);
    }

    public function cancel_return($order_id) {
        $this->db->where('id', $order_id)->update('orders', [
            'return_status' => 'rejected'
        ]);
        $this->session->set_flashdata('error', 'Return request rejected.');
        redirect('admin/order_detail/' . $order_id);
    }

    public function update_item_return_status($item_id, $status) {
        $this->db->where('id', $item_id);
        return $this->db->update('order_items', ['return_status' => $status]);
    }

    public function deliverd_order($order_id) {
        $this->db->where('id', $order_id);
        $this->db->update('orders', [
            'status' => 'Delivered',
            'delivered_at' => date('Y-m-d H:i:s')
        ]);
    
        redirect('admin/order_detail/' . $order_id);
    }
    
    public function cancel_order($order_id) {
        $this->db->where('id', $order_id)->update('orders', ['status' => 'Cancelled']);
        $this->session->set_flashdata('error', 'Order has been Cancelled');
        redirect('admin/order_detail/' . $order_id);
    }

}
