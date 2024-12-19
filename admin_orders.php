<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "Access denied!";
    exit();
}

// Fetch all orders with billing information
$query = "SELECT o.id AS order_id, u.username, b.address, b.zip_code, o.total_price, o.order_status, o.created_at 
          FROM orders o
          JOIN users u ON o.user_id = u.id
          JOIN billing_addresses b ON o.id = b.order_id";
$result = $conn->query($query);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
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


    <h1 style="width:fit-content; margin:1rem auto; font: size 4rem;">All Orders</h1>

    
    <table  class="orders" border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Username</th>
                <th>Address</th>
                <th>Zip Code</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['zip_code']); ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                    <td><?php echo $row['order_status']; ?></td>
                    <td>
                        <form method="POST" action="verify_order.php">
                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                            <button type="submit" name="action" value="verify">Verify</button>
                            <button type="submit" name="action" value="cancel">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
