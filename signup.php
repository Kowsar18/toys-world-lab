<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST["email"];
    $user_image=$_POST["user_image"];
    // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password = $_POST['password'];
    $password_hidden = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&_])[A-Za-z\d@$!%*?&_]{8,}$/";

    if (preg_match($pattern, $password)) {
        $sql = "INSERT INTO users (username, email,user_image, password) VALUES ('$username', '$email', '$user_image', '$password_hidden')";
    } else {
        echo "<script>alert('Password must have A uppercase,lowercase,special character and atleast 8 characters.');
               window.location.href = 'signup.php';
        </script>";
     
    }

    
    if ($conn->query($sql) === TRUE) {

        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        $user = $result->fetch_assoc();
        if ($user ==  true) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            echo "Invalid credentials";
        }


        header("Location: index.php");
        exit();
    } else {
        echo "Already Exists ".$conn->error;
    }
}
// $conn->error;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">

    <style>
        .s-header{
            width: fit-content;
            margin: auto auto;
            margin-top: 3rem;
            font-size:3rem;
                font-weight:bold;
                padding:1rem;
                border-bottom:5px solid #252525;
                text-align: center;
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
            width:100%;
            font-size: 1rem;
        }

        button{
            padding: 20px;
        }

        #form-control a{
            font-size: .875rem;
        }
    </style>
</head>
<body>
   <div
          style="border:2px solid green;border-radius:1rem;width:50vw;margin:2rem auto;background-color:white;"
   >
   <h2 class="s-header">New here? Sign Up now!</h2>
    <form id="form-control" method="post">
        Username: <input type="text" name="username" required><br>
        Email: <input type="email" name="email" required ><br>
        PhotoURL: <input type="text" name="user_image"> <br>
        Password: <input type="text" name="password" required><br>
        
        <a href="login.php">Already have account? Log in now!</a>
        <button type="submit">Signup</button>
    </form>


   </div>
</body>
</html>
