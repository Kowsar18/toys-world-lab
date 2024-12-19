<?php
// Connect to the database
session_start();
include 'config/db.php';

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch verified products
$query = $conn->prepare("SELECT id,order_status ,user_id, total_price,payment_method FROM orders WHERE user_id = ?");
$query->bind_param("i", $_SESSION['user_id']);
$query->execute();
$result = $query->get_result();
$isVerified = $result->fetch_assoc();

$order_id =  $isVerified['id']? $isVerified['id'] : 'Default Name';
$order_status =$isVerified['order_status']? $isVerified['order_status'] : 'Default Name';
$total_price = $isVerified['total_price']? $isVerified['total_price'] : 'Default Name';
$payment_method = $isVerified['payment_method'] ? $isVerified['payment_method'] : 'Default Name';


// $name = property_exists($object->user, 'name') ? $object->user->name : 'Default Name';
// $email = property_exists($object->user, 'email') ? $object->user->email : 'No Email';




// $verified_products = [];
// while ($row = $result->fetch_assoc()) {
//     $verified_products[] = $row['name'];
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
    <style>
        /* Popup styling */
        .popup {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.25);
            border-radius: 8px;
            z-index: 1000;
        }
        .popup button {
            margin-top: 10px;
        }
        .popup-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
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



<!-- $order_id
$order_status
$total_price
$payment_method -->

<div> 
                <h3 style="font-size:2rem">Order Id:<?php echo htmlspecialchars($order_id); ?></h3>
                <p style="margin:.5rem 0; font-size:1rem">Order status:<?php echo htmlspecialchars($order_status); ?></p>
                <p style="margin:.5rem 0; font-size:1.25rem">Total Price: <?php echo $total_price; ?> Taka</p>
                <strong style="font-size:1.5rem">Payment Method:<?php echo htmlspecialchars($payment_method); ?> </strong></div>


    <!-- Popup Overlay -->
    <div id="popup-overlay" class="popup-overlay"></div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <p id="popup-message"></p>
        <button onclick="closePopup()">Close</button>
    </div>

    <script>
        // Pass verified products from PHP to JavaScript
       
        console.log(<?php echo json_encode($isVerified); ?>)


        const isVerified = <?php echo json_encode($isVerified); ?>;
     
        const popUp=(status)=>{
            
            const popupMessage = document.getElementById('popup-message');
            popupMessage.innerHTML = `Your order is <em>${status}</em>`;
            
            // Display popup
            document.getElementById('popup-overlay').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        }

        // Show popup if there are verified products
        if (isVerified.order_status) {
            popUp(isVerified.order_status)
        }
        else{
            popUp('Pending')
        }
        // Close popup
        function closePopup() {
            document.getElementById('popup-overlay').style.display = 'none';
            document.getElementById('popup').style.display = 'none';
        }
    </script>
</body>
</html>
