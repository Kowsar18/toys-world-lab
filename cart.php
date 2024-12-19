<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user
$cart_items = $conn->query("SELECT c.id, p.name, c.quantity, c.total_price 
                            FROM cart c 
                            JOIN products p ON c.product_id = p.id 
                            WHERE c.user_id = $user_id");



// Calculate the total price of the cart
$total_price_result = $conn->query("SELECT SUM(total_price) AS total FROM cart WHERE user_id = $user_id");
$total_price = $total_price_result->fetch_assoc()['total'];



if (isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $conn->query("DELETE FROM cart WHERE id = $cart_id");
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body{
            width: 100%;
        }
        


            .title{
                font-size: 3rem;
                width:fit-content;
                margin: 3rem auto 1rem;
            }

            .cart-container{
                width: 70vw;
                border: 2px solid green;
                border-radius: 12px;
                padding: 1rem;
                margin:2rem auto;
            }
            .cart-table-title{
                font-size: 2.25rem;
                font-weight: bold;
                background-color: green;
                color: white;
                padding:1rem;
            }
            .cart-table-title,.cart-table-content{
                display:grid;
                grid-template-columns: repeat(4, 1fr);
               
                place-items: center;
                
            }

            .cart-table-content{
                  text-align: center;
                    border-bottom: 2px solid green;
                    height: 100%;
                    border-left: 2px solid green;
                    

            }
            .cart-table-content>*{
                border-right: 2px solid green;
                display: grid;
                place-items: center;
                height: 100%;
                width: 100%;
                
              
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



    <h2 class="title">Your Cart</h2>
    <div class="cart-container">
        
         <div class="cart-table-title">   
            <h6>Product</h6>
            <h6>Quantity</h6>
            <h6>Total Price</h6>
            <h6>Actions</h6>
        </div>
        <?php while ($item = $cart_items->fetch_assoc()) { ?>
            <div class="cart-table-content">
                <p><?php echo htmlspecialchars($item['name']); ?></p>
                <p><?php echo htmlspecialchars($item['quantity']); ?></p>
                <p><?php echo htmlspecialchars($item['total_price']); ?></p>
                <div>
                    <form method="post">
                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="remove_item">Remove</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
    <h3
            style="width:fit-content;
                   margin:1.25rem auto;
                   font-size:2rem; "   
    >Total Amount: $<?php echo $total_price ? number_format($total_price, 2) : '0.00'; ?></h3>
    <form
            style="width:100%;
            display:flex;
            align-items:center;
            justify-content:center"
    action="place-order.php" method="GET">
    <button
    style="width:40%;
           margin:1.25rem auto; "       
    type="submit">Proceed to Checkout</button>
</form>

</body>
</html>
