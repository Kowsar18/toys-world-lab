<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    $new_status = $action === 'verify' ? 'Verified' : 'Cancelled';

    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();

    echo "Order has been " . ($action === 'verify' ? "verified" : "cancelled") . ".";
    header("Location: admin_orders.php");
    exit();
}
?>
