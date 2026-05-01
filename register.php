<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}; // safe to keep consistent

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query(
        $conn,
        "INSERT INTO users (name, username, email, password)
         VALUES ('$name', '$username', '$email', '$password')"
    );

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <style>
    /* RESET */
    * {
        box-sizing: border-box;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    body {
        margin: 0;
        background: linear-gradient(135deg, #dfe9f3, #ffffff);
    }

    /* CENTER */
    .auth-wrapper {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* BOX */
    .auth-box {
        width: 360px;
        background: #fff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);

        display: flex;
        flex-direction: column; /* 🔥 FIX */
    }

    /* TITLE */
    .auth-box h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #1da1f2;
    }

    /* INPUTS */
    .auth-box input {
        width: 100%;
        padding: 12px;
        margin-bottom: 14px;
        border-radius: 8px;
        border: 1px solid #ccd6dd;
        font-size: 14px;
    }

    /* BUTTON */
    .auth-box button {
        width: 100%;
        padding: 12px;
        background: #1da1f2;
        border: none;
        border-radius: 30px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        font-size: 15px;
    }

    .auth-box button:hover {
        background: #0d8ae5;
    }

    /* TEXT */
    .auth-box p {
        text-align: center;
        margin-top: 15px;
    }

    .auth-box a {
        color: #1da1f2;
        text-decoration: none;
        font-weight: bold;
    }
    </style>
</head>

<body>

<div class="auth-wrapper">
    <form class="auth-box" method="post">
        <h2>Create your account</h2>

        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Sign Up</button>

        <p>
            Already have an account?
            <a href="login.php">Log in</a>
        </p>
    </form>
</div>

</body>
</html>