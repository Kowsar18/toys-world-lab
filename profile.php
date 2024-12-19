<?php
session_start();
include 'config/db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first!";
    header("Location: login.php");
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = $conn->prepare("SELECT username, email,user_image, role FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
}

$query = $conn->prepare("SELECT o.id, o.total_price, o.order_status, b.address, b.zip_code 
                         FROM orders o 
                         JOIN billing_addresses b ON o.id = b.order_id 
                         WHERE o.user_id = ? AND o.order_status = 'Verified'");
$query->bind_param("i", $_SESSION['user_id']);
$query->execute();
$orders = $query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
           
        }
        .profile-container {
            max-width: 400px;
            margin: 3rem auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }
        h1 {
            text-align: center;
        }
        .profile-field {
            margin-bottom: 15px;
        }
        .profile-field span {
            font-weight: bold;
        }
        a {
            display: block;
           
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .profile-img{
            width: 50%;
            margin: 0 auto;
            aspect-ratio: 1/1;
            border-radius: 50%;
        }
    </style>
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



    <div class="profile-container">
        <img class="profile-img" src="<?php  echo htmlspecialchars($user["user_image"]); ?>" alt="profile picture">
        <h1>User Profile</h1>
        <div class="profile-field">
            <span>Username:</span> <?php echo htmlspecialchars($user['username']); ?>
        </div>
        <div class="profile-field">
            <span>Email:</span> <?php echo htmlspecialchars($user['email']); ?>
        </div>
        <div class="profile-field">
            <span>Role:</span> <?php echo htmlspecialchars($user['role']); ?>
        </div>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
