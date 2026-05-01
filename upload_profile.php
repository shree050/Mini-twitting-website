<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION['user_id'])) exit;

$uid = $_SESSION['user_id'];

if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] !== 0) {
    die("No file uploaded");
}

$file = $_FILES['profile_pic'];

/* ✅ VALIDATION */
$allowed = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($file['type'], $allowed)) {
    die("Invalid file type");
}

/* ✅ CREATE IMAGE RESOURCE */
switch ($file['type']) {
    case 'image/jpeg':
        $src = imagecreatefromjpeg($file['tmp_name']);
        break;
    case 'image/png':
        $src = imagecreatefrompng($file['tmp_name']);
        break;
    case 'image/webp':
        $src = imagecreatefromwebp($file['tmp_name']);
        break;
    default:
        die("Unsupported format");
}

/* ✅ RESIZE */
$maxSize = 300;
$width = imagesx($src);
$height = imagesy($src);

$scale = min($maxSize / $width, $maxSize / $height, 1);

$newW = (int)($width * $scale);
$newH = (int)($height * $scale);

$dst = imagecreatetruecolor($newW, $newH);

/* preserve transparency for PNG */
imagealphablending($dst, false);
imagesavealpha($dst, true);

imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $width, $height);

/* ✅ SAVE FILE */
$filename = "user_" . $uid . "_" . time() . ".webp";
$path = "../assets/profile_pics/" . $filename;

imagewebp($dst, $path, 80);

/* cleanup */
imagedestroy($src);
imagedestroy($dst);

/* ✅ UPDATE DB */
mysqli_query($conn, "
UPDATE users 
SET profile='$filename' 
WHERE id=$uid
");

/* redirect */
header("Location: ../profile.php");
exit;