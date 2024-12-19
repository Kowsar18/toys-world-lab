 <?php
session_start();
include 'config/db.php';

$products = $conn->query("SELECT * FROM products");

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id']; // Assume the user is logged in

    // Fetch product price
    $product = $conn->query("SELECT price FROM products WHERE id = $product_id")->fetch_assoc();
    $price = $product['price'];
    $total_price = $price * $quantity;

    // Check if product is already in cart
    $cart_check = $conn->query("SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    if ($cart_check->num_rows > 0) {
        // Update quantity and total price
        $conn->query("UPDATE cart SET quantity = quantity + $quantity, total_price = total_price + $total_price WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Add new item to cart
        $conn->query("INSERT INTO cart (user_id, product_id, quantity, total_price) VALUES ($user_id, $product_id, $quantity, $total_price)");
    }

    header("Location: product.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .btn{
            padding: .5rem 1rem;
            border:none;
            font-size: 1.25rem;
            font-weight: bold;
            width: fit-content;
           margin: 1rem auto;
        }
        .btn-primary{
            color: whitesmoke;
            background-color: #FF00FF;
        }
        
        .btn-secondary{
            color: #FF00FF;
            background-color: whitesmoke; 
        }
        .btn-primary:hover{
            color:#FF00FF;
            background-color: whitesmoke;
        }
        .btn-secondary:hover{
            color:#FF00FF;
            background-color: transparent;
            outline: 1px solid #FF00FF;
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



    <h2 style="margin:2rem;text-align:center;" class="heading-product-page">Products</h2>
<div>

<div class="product-container">
        <?php while ($product = $products->fetch_assoc()) { ?>
            <div class="product-card">
            <img class="product-image" src="<?php echo htmlspecialchars($product['image']); ?>" alt="">
               <div> 
                <h3 style="font-size:2rem"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p style="margin:.5rem 0; font-size:1rem"><?php echo htmlspecialchars($product['description']); ?></p>
                <p style="margin:.5rem 0; font-size:1.25rem">Status: <?php echo $product['status'] == 'available' ? 'Available' : 'Unavailable'; ?></p>
                <strong style="font-size:1.5rem"> <?php echo htmlspecialchars($product['price']); ?> Taka</strong></div>
                


                <form style="width:100%;display:flex;flex-direction:column; justify-content:center;align-items:center;" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" value="1" min="1" required>
                        <button class="btn-secondary btn"  type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
              
            </div>
        <?php } ?>
    </div>


</div>

</body>
</html>
