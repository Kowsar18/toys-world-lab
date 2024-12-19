<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$products = $conn->query("SELECT * FROM products");
$cart =$conn->query("SELECT * FROM cart");

if (isset($_POST["add_product"])) {
    $new_product_name = $_POST['product_name'];
    $new_product_description = $_POST['product_description'];
    $new_product_status = $_POST['product_status'];
    $new_product_image=$_POST['product_image'];
    $new_product_price=$_POST['product_price'];

    $conn->query("INSERT INTO products (name, description, status, price, image) VALUES ('$new_product_name', '$new_product_description', '$new_product_status' , '$new_product_price' , ' $new_product_image ')");
    header("Location: admin.php"); // Refresh to show the new product
    exit();
}


if (isset($_POST['update_product'])) {
    $product_id = $_POST['product_id'];
    $updated_status = $_POST['status'];
    $updated_description = $_POST['description'];
    $updated_price=$_POST['product_price'];

    $conn->query("UPDATE products SET status='$updated_status', description='$updated_description' , price='$updated_price' WHERE id='$product_id'");
    header("Location: admin.php");
    exit();
}


if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete product from database
    $conn->query("DELETE FROM cart WHERE product_id='$product_id'");
    $conn->query("DELETE FROM order_items WHERE product_id='$product_id'");

    $conn->query("DELETE FROM products WHERE id='$product_id'");
  
    header("Location: admin.php");
    exit();
}




?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">


    <style>
        h2,h3,h6{
            width: fit-content;
            margin: 20px auto;
        }
        form{
            border: 2px solid green;
            border-radius: 1rem;
            width: 70%;
            margin: 20px auto 40px;
            padding: 1.5rem;
        }

        .box{
            border: none;
        }

        .products-update-container{
            width:70vw;
            border:2px solid green;
            border-radius: 12px;
            margin: 20px auto 40px;
            padding: 1.5rem;
        }
        .title,.products-update{
    
            display:grid;
            grid-template-columns: repeat(3,1fr);
            place-items: center;
            
          
            font-size: 1.5rem;
            font-weight: bold;
        }
        .products-update{
            border-top:2px solid green;
            font-size: 1rem;
            font-weight: normal;
        }
        .new_product_form{
            display: grid;
            grid-template-columns: repeat(1, 1fr);
        }
        
        select{
            padding: 1rem;
            font-size:1rem;
            border-radius: 1rem;
            margin-block: 1rem;
        }
        textarea{
            appearance: none;
            resize: none;
            padding: .5rem;
            height:200px;
            border-radius: 1rem;
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

    <h2>Admin Panel</h2>
<!-- Add New Product Form -->
<h3>Add New Product</h3>
    <form onsubmit="alert('Product added successfully!')" class="new_product_form" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required>
        
        <label for="product_description">Description:</label>
        <textarea name="product_description" rows="3" required></textarea>
        
        <label for="product_status">Status:</label>
        <select name="product_status">
            <option value="available">Available</option>
            <option value="unavailable">Unavailable</option>
        </select> <br>
        <label for="product_price">Price:</label>
        <input type="number" step="0.05" name="product_price" value="0">

        <label for="product_image">Image: </label>
        <input type="text" name="product_image" >
        
        <button type="submit" name="add_product" style="width:fit-content; margin:1rem auto;">Add Product</button>
    </form>






    <div  class="products-update-container">
        <div class="title">
            <h6>Product</h6>
            <h6 class="middle">Status</h6>
            <h6>Action</h6>
        </div>
        <?php while ($product = $products->fetch_assoc()) { ?>
            <div class="products-update" >
                <p 
                    style="font-size:2rem;text-align:center;border-right:2px solid green; height:100%; display: grid;place-items: center; width:100%;"
                ><?php echo $product['name']; ?></p>
                <p 
                    style="font-size:2rem;text-align:center;border-right:2px solid green; height:100%;display: grid;place-items: center; width:100%;"
                class="middle"><?php echo $product['status']; ?></p>
                <div class="update-action" style="display:flex; gap:1rem align-items:center;">
                    <form  onsubmit="alert('Product updated successfully!')" class="box" method="POST" style="border-radius:0px;">
                    <label for="product_price">Price:</label>
                    <input type="number" step="0.05" name="product_price" value="<?php echo htmlspecialchars($product['price']); ?>">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <select
                            style="width:100%"
                        name="status">
                                  <option value="available" <?php echo $product['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                            <option value="unavailable" <?php echo $product['status'] == 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                        </select>



                 <div
                 style="display:flex;
                 gap:2rem;
                 justify-content:center;
                 align-items:center;
                 ">
                 <button type="submit" name="update_product">Update</button>
                   
                   <!-- Delete Button -->
                   <form  onsubmit="alert('Product deleted successfully!')" class="box" method="delete">
                     <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                     <button type="submit" name="delete_product" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                 </form>
                 </div>
                    </form>
                                
                  

                      
                    
                </div>
            </div >
        <?php } ?>
    </div>
</body>
</html>
