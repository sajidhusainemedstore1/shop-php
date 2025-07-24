<?php
require_once('tcpdf_min/tcpdf.php');

$conn = new mysqli("localhost", "root", "", "your_database_name");

$order_id = (int) ($_GET['order_id'] ?? 0);

// Order info
$order_query = "SELECT o.id AS order_id, o.created_at, o.total, u.fullname 
                FROM orders o 
                JOIN user u ON o.user_id = u.id 
                WHERE o.id = $order_id";
$order = $conn->query($order_query)->fetch_assoc();

// Order items
$items_query = "SELECT p.name, oi.qty, oi.price 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = $order_id";
$items = $conn->query($items_query);

// TCPDF setup
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Header
$pdf->Cell(0, 10, "ORDER RECEIPT", 0, 1, 'C');
$pdf->Ln(4);

$pdf->Cell(0, 10, "Receipt No: #" . $order['order_id'], 0, 1);
$pdf->Cell(0, 10, "Order Date: " . date('d M Y', strtotime($order['created_at'])), 0, 1);
$pdf->Cell(0, 10, "Username: " . $order['fullname'], 0, 1);
$pdf->Ln(5);

// Table Headers
$pdf->SetFont('', 'B');
$pdf->Cell(70, 8, "Product", 1);
$pdf->Cell(30, 8, "Qty", 1, 0, 'C');
$pdf->Cell(40, 8, "Price (₹)", 1, 0, 'R');
$pdf->Cell(40, 8, "Subtotal (₹)", 1, 1, 'R');

// Table Data
$pdf->SetFont('', '');
$total = 0;
while ($item = $items->fetch_assoc()) {
    $subtotal = $item['qty'] * $item['price'];
    $total += $subtotal;

    $pdf->Cell(70, 8, $item['name'], 1);
    $pdf->Cell(30, 8, $item['qty'], 1, 0, 'C');
    $pdf->Cell(40, 8, number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($subtotal, 2), 1, 1, 'R');
}

// Total row
$pdf->SetFont('', 'B');
$pdf->Cell(140, 10, "Total", 1);
$pdf->Cell(40, 10, "₹" . number_format($total, 2), 1, 1, 'R');

// Output
$pdf->Output("Invoice_Order_$order_id.pdf", 'I');
