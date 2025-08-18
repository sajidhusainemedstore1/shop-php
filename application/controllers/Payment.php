<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';  // include Razorpay SDK
use Razorpay\Api\Api;

class Payment extends CI_Controller {

    private $keyId = "rzp_test_xxxxxxx";      // Replace with your test key
    private $keySecret = "xxxxxxxxxxxx";      // Replace with your test secret

    public function start($order_id = null) {
        // Initialize Razorpay
        $api = new Api($this->keyId, $this->keySecret);

        // Example order details (can come from your DB/cart)
        $orderData = [
            'receipt'         => $order_id ?? rand(1000,9999),
            'amount'          => 50000, // amount in paise (₹500)
            'currency'        => 'INR',
            'payment_capture' => 1
        ];

        // Create order in Razorpay
        $razorpayOrder = $api->order->create($orderData);

        // Data for checkout.js
        $data = [
            "key"               => $this->keyId,
            "amount"            => $orderData['amount'],
            "currency"          => $orderData['currency'],
            "name"              => "My Test Shop",
            "description"       => "Test Payment",
            "order_id"          => $razorpayOrder['id'],
            "callback_url"      => "https://8ea8222955dd.ngrok-free.app/payment/callback",
            "prefill"           => [
                "name"    => "Test User",
                "email"   => "test@example.com",
                "contact" => "9876543210",
            ],
        ];

        // Load view and pass data
        $this->load->view('payment_checkout', $data);
    }

    public function callback() {
        // Razorpay will hit this after payment
        echo "Payment Callback Reached!";
        // here you can verify signature and update DB
    }
}
