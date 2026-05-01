<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
} // 🔥 YOU FORGOT THIS (important)

$error = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE username = '$username'"
    );

    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../home.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

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
        flex-direction: column; /* 🔥 MAIN FIX */
    }

    /* TITLE */
    .auth-box h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #1da1f2;
    }

    /* INPUT */
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

    /* ERROR */
    .error {
        color: red;
        text-align: center;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>

<div class="auth-wrapper">
    <form class="auth-box" method="post">
        <h2>Sign in to Mini Sweeters</h2>

        <?php if ($error != "") { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="login">Log in</button>

        <p>
            Don’t have an account?
            <a href="register.php">Sign up</a>
        </p>
    </form>
</div>

</body>
</html>