<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to proceed.";
    header("Location: login.php");
    exit();
}

// Fetch user's cart data to display
$user_id = $_SESSION['user_id'];
$cart_query = $conn->prepare("SELECT p.name, c.quantity, p.price,c.product_id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
// $cart_query = $conn->prepare("SELECT p.name, c.quantity, c.total_price, c.product_id FROM cart c JOIN products p WHERE c.user_id = ?");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();
$cart_items = $cart_result->fetch_all(MYSQLI_ASSOC);


$jsonObject = json_encode($cart_items);
echo "<script>console.log('PHP Object:', $jsonObject);</script>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $address = $_POST['address'];
    $zip_code = $_POST['zip_code'];
    $payment_method=$_POST['payment_method'];
    $total_price = array_reduce($cart_items, fn($total, $item) => $total + $item['quantity'] * $item['price'], 0);

    // Insert order into `orders` table
    $order_stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, payment_method) VALUES (?, ?, ?)");
    $order_stmt->bind_param("ids", $user_id, $total_price, $payment_method);
    $order_stmt->execute();
    $order_id = $order_stmt->insert_id;
    
    // Insert order items into `order_items`
    $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $item) {

        

        $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }
   

    // Insert billing address into `billing_addresses` table
    $billing_stmt = $conn->prepare("INSERT INTO billing_addresses (order_id, user_id, username, address, zip_code) VALUES (?, ?, ?, ?, ?)");
    $billing_stmt->bind_param("iisss", $order_id, $user_id, $username, $address, $zip_code);
    $billing_stmt->execute();

    // Clear user's cart
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    echo "Order placed successfully! Awaiting admin verification.";
    header("Location: user_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Address</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="nav-container" >

<div class="logo-container"><p class="logo">K</p></div>
<nav class="nav-bar">

<a href="index.php">Home</a>
<?php if ( $_SESSION != true) { ?>
    <a href="signup.php">Signup</a>
    <a href="login.php">Login</a>
<?php } ?>
<a href="profile.php">Profile</a>
<a href="product.php">Product</a>
<a href="cart.php">Cart</a>
<a href="user_dashboard.php">User Dashboard</a>
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
    <a href="admin.php">Admin Panel</a>
    <a href="admin_orders.php">Orders</a>
<?php } ?>
<?php if ( $_SESSION == true) { ?>
    <a href="logout.php">Logout</a>
<?php } ?>    
</nav>
</div>

    <h1 style="width:fit-content; margin:1rem auto; font: size 4rem;">Billing Address</h1>

    <form
        style="width:70vw;
                margin:3rem auto;
                padding:1.5rem;
                border:2px solid green;
                border-radius:1rem;
                "
    onsubmit="alert('Order as been placed')" method="POST" action="">
        <label 
            style="font-size:1.5rem;
                    margin:2rem auto;"   
        for="username">Username:</label>
        <input type="text" name="username" required >
        <br>
        <label 
            style="font-size:1.5rem;
                    margin:2rem auto;"   
        for="address">Address:</label>
        <textarea 
        style="resize:none;
                width:100%;
                height:200px;
                border-radius:1rem;
                padding:1.5rem;
                font-size:1rem;"

        name="address" required></textarea>
        <br>
        <label 
            style="font-size:1.5rem;
                    margin:2rem auto;"   
        for="zip_code">Zip Code:</label>
        <input type="text" name="zip_code" required>
        <br>
        <label for="payment_method">Payment Method:</label>
        <select
        style="padding: 1rem;
            font-size:1rem;
            border-radius: 1rem;
            margin-block: 1rem;"
        name="payment_method" required>
            <option value="Bkash">Bkash</option>
            <option value="Nagad">Nagad</option>
            <option value="Visa">Visa</option>
        </select>
        <br>
        <button 
        style="width:100%"
         type="submit">Place Order</button>
    </form>
    
</body>
</html>
