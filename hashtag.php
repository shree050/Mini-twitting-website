<?php
include "config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['tag'])) {
    die("No hashtag selected");
}

$tag = mysqli_real_escape_string($conn, $_GET['tag']);
?>

<!DOCTYPE html>
<html>
<head>
<title>#<?= $tag ?> | Mini Sweeters</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<h2 style="text-align:center;">#<?= htmlspecialchars($tag) ?></h2>

<div class="container">

<?php
$q = mysqli_query($conn,"
SELECT tweets.*, users.name, users.username, users.profile
FROM tweets
JOIN users ON users.id = tweets.user_id
WHERE tweets.content LIKE '%#$tag%'
ORDER BY tweets.id DESC
");

while ($t = mysqli_fetch_assoc($q)) {
?>

<div class="tweet">

<b>
<a href="profile.php?id=<?= $t['user_id'] ?>">
<?= htmlspecialchars($t['name']) ?>
</a>
</b>

<span style="color:#657786;">
@<?= $t['username'] ?>
</span>

<p><?= htmlspecialchars($t['content']) ?></p>

</div>

<?php } ?>

</div>

</body>
</html>