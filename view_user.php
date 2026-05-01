
<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin_id'])){
header("Location: admin_login.php");
exit();
}

if(!isset($_GET['id'])){
header("Location: admin_dashboard.php");
exit();
}

$id = intval($_GET['id']);

/* DELETE TWEET */

if(isset($_GET['delete_tweet'])){
$tid = intval($_GET['delete_tweet']);
mysqli_query($conn,"DELETE FROM tweets WHERE id='$tid'");
header("Location: view_user.php?id=".$id);
exit();
}

/* USER DATA */

$user = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM users WHERE id='$id'
"));

/* PROFILE IMAGE */

$img = "../uploads/".$user['profile'];

if(empty($user['profile']) || !file_exists($img)){
$img = "../assets/default.png";
}

/* USER STATS */

$total_tweets = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM tweets WHERE user_id='$id'
"))['c'];

$total_likes = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c 
FROM likes 
JOIN tweets ON tweets.id = likes.tweet_id
WHERE tweets.user_id='$id'
"))['c'];

$total_comments = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c
FROM comments
JOIN tweets ON tweets.id = comments.tweet_id
WHERE tweets.user_id='$id'
"))['c'];

/* USER TWEETS */

$tweets = mysqli_query($conn,"
SELECT tweets.*,
(SELECT COUNT(*) FROM likes WHERE tweet_id=tweets.id) AS like_count,
(SELECT COUNT(*) FROM comments WHERE tweet_id=tweets.id) AS comment_count
FROM tweets
WHERE user_id='$id'
ORDER BY tweets.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>User Profile</title>

<style>

body{
font-family:Segoe UI;
background:#f5f8fa;
margin:0;
padding:40px;
}

.container{
max-width:750px;
margin:auto;
}

/* profile card */

.card{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
margin-bottom:20px;
}

.profile{
display:flex;
gap:20px;
align-items:center;
}

.profile img{
width:90px;
height:90px;
border-radius:50%;
object-fit:cover;
border:3px solid #1d9bf0;
}

.username{
font-size:24px;
font-weight:600;
}

.email{
color:#555;
font-size:14px;
}

/* stats */

.stats{
display:flex;
justify-content:space-between;
margin-top:20px;
}

.stat{
text-align:center;
flex:1;
}

.stat h2{
margin:5px 0;
color:#1d9bf0;
}

/* tweets */

.tweets{
background:white;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.tweet{
border-bottom:1px solid #eee;
padding:12px 0;
}

.meta{
font-size:13px;
color:#555;
margin-top:5px;
}

.delete{
background:#e0245e;
color:white;
padding:4px 10px;
border-radius:6px;
text-decoration:none;
font-size:12px;
margin-left:10px;
}

.back{
display:inline-block;
margin-bottom:20px;
background:#1d9bf0;
color:white;
padding:7px 14px;
border-radius:8px;
text-decoration:none;
}

</style>

</head>

<body>

<div class="container">

<a class="back" href="admin_dashboard.php">← Back</a>

<!-- PROFILE -->

<div class="card">

<div class="profile">

<img src="<?php echo $img; ?>">

<div>

<div class="username"><?php echo $user['username']; ?></div>

<div class="email"><?php echo $user['email']; ?></div>

<div>Status: <b><?php echo $user['status']; ?></b></div>

</div>

</div>

<!-- USER STATS -->

<div class="stats">

<div class="stat">
Tweets
<h2><?php echo $total_tweets; ?></h2>
</div>

<div class="stat">
Likes
<h2><?php echo $total_likes; ?></h2>
</div>

<div class="stat">
Comments
<h2><?php echo $total_comments; ?></h2>
</div>

</div>

</div>

<!-- TWEETS -->

<div class="tweets">

<h3>User Tweets</h3>

<?php
if(mysqli_num_rows($tweets)==0){
echo "<p>No tweets yet</p>";
}

while($t=mysqli_fetch_assoc($tweets)){
?>

<div class="tweet">

<?php echo htmlspecialchars($t['content']); ?>

<div class="meta">

❤️ <?php echo $t['like_count']; ?> likes  
💬 <?php echo $t['comment_count']; ?> comments  

<a class="delete" href="?id=<?php echo $id; ?>&delete_tweet=<?php echo $t['id']; ?>">Delete</a>

</div>

</div>

<?php } ?>

</div>

</div>

</body>
</html>

