<?php
include 'config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .l-header{
            width: fit-content;
            margin-top: 100px;
            margin-inline: auto;
            font-size: 4rem;
        }

        #form-control{
            
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 50vw;
    
    margin: 50px auto;
    font-size: 2rem;
    font-weight: 500;
    padding: 1.15rem;
    border-radius: 1rem;
}


        input{
            box-sizing: border-box;
            padding: 1rem;
            font-size: 1rem;
            width:100%;
        }

        button{
            padding: 20px;
        }
        #form-control a{
            font-size: 1rem;
        }
    </style>

</head>
<body>
<div 
        style="border:2px solid green;border-radius:1rem;width:50vw;margin:2rem auto;background-color:white;"
>

<h2 
        style="font-size:3rem;
                font-weight:bold;
                padding:1rem;
                border-bottom:5px solid #252525;"
                       
class="l-header" >Welcome Back!</h2>
  
    <form id="form-control" method="post">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
       
        <a href="signup.php">Hasn't signed up? Sign up Now!</a>
       
        <button type="submit">Login</button>
    </form>

</div>
</body>
</html>
