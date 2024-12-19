<?php
session_start();
include 'config/db.php';
?>



<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .banner{
            background-image: url('image/banner-image.webp');
            background-color: rgba(0, 0, 0, .3);
            background-blend-mode: saturation;
            display: flex;
            justify-content: center;
            align-items: center;
            background-repeat: no-repeat;
            background-size: cover;
            height: calc(100svh - 30px + 1.2rem);
        }

        .banner-title{
            color: var(--clr-pnk);
            font-size: 4rem;
            

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


    <section class="banner" >
    <h1 class="banner-title">Welcome to Toys World</h1>
    </section>




</body>
</html>
