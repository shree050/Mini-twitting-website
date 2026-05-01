<?php
include "config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$uid = (int)$_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Explore | Mini Sweeters</title>
<link rel="stylesheet" href="assets/css/style.css">

<style>
.layout{
display:grid;
grid-template-columns:250px 1fr 300px;
gap:20px;
padding:20px;
}

.left-sidebar{
background:#fff;
padding:15px;
border-radius:12px;
height:fit-content;
position:sticky;
top:80px;
}

.left-sidebar a{
display:block;
padding:10px;
text-decoration:none;
color:black;
border-radius:8px;
}

.left-sidebar a:hover{
background:#f2f2f2;
}

.left-sidebar button{
margin-top:10px;
width:100%;
padding:10px;
border:none;
background:#1da1f2;
color:#fff;
border-radius:20px;
cursor:pointer;
}

.right-sidebar{
position:sticky;
top:80px;
}

.sidebar-box{
background:#fff;
padding:15px;
border-radius:12px;
margin-bottom:15px;
}
</style>

</head>

<body>

<!-- TOPBAR -->
<header class="topbar">

<div class="logo">
<img src="assets/logo1.svg" class="logo-img">
<span>Mini Sweeters</span>
</div>


<div class="top-nav">
<a href="home.php">Home</a>
<a href="explore.php"><b>Explore</b></a>
<a href="messages.php">Messages</a>
<a href="notifications.php">Notifications</a>
<a href="profile.php">Profile</a>
<a href="auth/logout.php">Logout</a>
</div>

</header>

<!-- MAIN LAYOUT -->
<div class="layout">

<!-- LEFT SIDEBAR -->
<div class="left-sidebar">
<h3>Menu</h3>
<a href="home.php">🏠 Home</a>
<a href="explore.php"><b>🔍 Explore</b></a>
<a href="notifications.php">🔔 Notifications</a>
<a href="messages.php">💬 Messages</a>
<a href="profile.php">👤 Profile</a>

<button onclick="window.location.href='home.php#tweetBox'">+ Tweet</button>
</div>

<!-- MAIN FEED -->
<div>

<h2>🔍 Explore Tweets</h2>

<?php
$q = mysqli_query($conn,"
SELECT tweets.*, users.name, users.username, users.profile
FROM tweets
JOIN users ON users.id = tweets.user_id
ORDER BY RAND()
LIMIT 20
");

while ($t = mysqli_fetch_assoc($q)) {
?>

<div class="tweet">

<div style="display:flex;align-items:center;gap:10px;">

<img src="assets/profile_pics/<?= !empty($t['profile']) ? htmlspecialchars($t['profile']) : 'default.png' ?>" 
style="width:40px;height:40px;border-radius:50%;">

<div>

<b>
<a href="profile.php?id=<?= $t['user_id'] ?>" style="text-decoration:none;color:inherit;">
<?= htmlspecialchars($t['name']) ?>
</a>
</b>

<br>
<span style="color:#657786;">
@<?= htmlspecialchars($t['username']) ?>
</span>

</div>

</div>

<!-- CONTENT WITH CLICKABLE HASHTAGS -->
<p>
<?php
$content = htmlspecialchars($t['content']);
$content = preg_replace('/#(\w+)/', '<a href="hashtag.php?tag=$1">#$1</a>', $content);
echo nl2br($content);
?>
</p>
<?php if(!empty($t['image'])){ 
$imgs = explode(",", $t['image']);
?>
<div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;">
<?php foreach($imgs as $img){ ?>
<img src="assets/tweet_images/<?= htmlspecialchars($img) ?>" 
style="width:120px;height:120px;object-fit:cover;border-radius:10px;">
<?php } ?>
</div>
<?php } ?>

</div>

<?php } ?>

</div>

<!-- RIGHT SIDEBAR -->
<div class="right-sidebar">

<div class="sidebar-box">
<h3>🔥 Trending</h3>
<p><a href="hashtag.php?tag=MiniSweeters">#MiniSweeters</a></p>
<p><a href="hashtag.php?tag=PHP">#PHP</a></p>
<p><a href="hashtag.php?tag=WebDev">#WebDev</a></p>
<p><a href="hashtag.php?tag=Coding">#Coding</a></p>
</div>

<div class="sidebar-box">
<h3>📊 Your Stats</h3>

<?php
$totalTweets = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM tweets WHERE user_id=$uid"))['c'];
$totalLikes = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM likes WHERE user_id=$uid"))['c'];
?>

<p>Tweets: <?= $totalTweets ?></p>
<p>Likes: <?= $totalLikes ?></p>

</div>

</div>

</div>

</body>
</html>