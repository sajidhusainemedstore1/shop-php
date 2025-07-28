<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Order extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function details($order_id = 0) {
        $this->load->model('order_model');
        
        if (!$order_id || !is_numeric($order_id)) {
            show_error('Invalid order ID');
        }

        $data['order'] = $this->order_model->get_order($order_id);
        if (!$data['order']) {
            show_error('Order not found');
        }

        $data['items'] = $this->order_model->get_order_items($order_id);

        $data['coupons'] = [
            'dis_amount' => $data['order']['dis_amount'],
            'coupon_code' => $data['order']['coupon_code']
        ];

        $data['show_buttons'] = (
            isset($data['order']['return_status']) && 
            $data['order']['return_status'] === 'requested'
        );

        $this->load->view('order_receipt', $data);
    }

    public function logout() {
        $this->session->unset_userdata('admin_id');
        $this->session->sess_destroy();
        redirect('admin/login');
    }
    
    public function export_orders_excel($order_id = 0) {
        if(!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }

        if (!$order_id || !is_numeric($order_id)) {
            show_error('Invalid order ID');
        }

        $order = $this->order_model->get_order($order_id);
        $order_items = $this->order_model->get_order_items($order_id);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Product');
        $sheet->setCellValue('B1', 'Qty');
        $sheet->setCellValue('C1', 'Price (₹)');
        $sheet->setCellValue('D1', 'Subtotal (₹)');
        $row = 2;

        $total = 0;
        foreach ($order_items as $item) {
            $subtotal = $item['qty'] * $item['price'];
            $sheet->setCellValue('A' . $row, $item['name']);
            $sheet->setCellValue('B' . $row, $item['qty']);
            $sheet->setCellValue('C' . $row, $item['price']);
            $sheet->setCellValue('D' . $row, round($subtotal, 2));
            $total += $subtotal;
            $row++;
        }

        $discount = $order['dis_amount'];
        $grand_total = $total - $discount;

        $sheet->setCellValue('C' . $row, 'Total:');
        $sheet->setCellValue('D' . $row, round($total, 2));
        $row++;
        $sheet->setCellValue('C' . $row, 'Discount:');
        $sheet->setCellValue('D' . $row, round($discount, 2));
        $row++;
        $sheet->setCellValue('C' . $row, 'Grand Total:');
        $sheet->setCellValue('D' . $row, round($grand_total, 2));

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="order_'.$order_id.'.xlsx"');
        header('Cache-Control: max-age=0');

        ob_clean();
        flush();

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}
