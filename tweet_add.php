<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = (int)$_SESSION['user_id'];
$content = mysqli_real_escape_string($conn, $_POST['content']);

$imageNames = [];

// CHECK IF FILES EXIST
if (isset($_FILES['tweet_image']) && !empty($_FILES['tweet_image']['name'][0])) {

    $folder = "../assets/tweet_images/";

    // CREATE FOLDER IF NOT EXISTS
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    foreach ($_FILES['tweet_image']['tmp_name'] as $key => $tmp_name) {

        if ($_FILES['tweet_image']['error'][$key] === 0) {

            $originalName = basename($_FILES['tweet_image']['name'][$key]);

            // UNIQUE NAME
            $newName = time() . "_" . $key . "_" . $originalName;

            $target = $folder . $newName;

            // MOVE FILE
            if (move_uploaded_file($tmp_name, $target)) {
                $imageNames[] = $newName;
            }
        }
    }
}

// CONVERT ARRAY → STRING
$imagesString = implode(",", $imageNames);

// INSERT INTO DB
mysqli_query($conn, "
INSERT INTO tweets (user_id, content, image)
VALUES ('$uid', '$content', '$imagesString')
");

header("Location: ../home.php");
exit;
?>