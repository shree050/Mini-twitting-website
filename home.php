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

/* NOTIFICATIONS */
$nq = mysqli_query($conn,"
SELECT COUNT(*) AS c
FROM notifications
WHERE user_id=$uid AND is_read=0
");
$notif_count = mysqli_fetch_assoc($nq)['c'] ?? 0;

/* MESSAGES */
$mq = mysqli_query($conn,"
SELECT COUNT(*) AS c
FROM messages
WHERE receiver_id=$uid AND seen=0
");
$msg_count = mysqli_fetch_assoc($mq)['c'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Home | Mini Sweeters</title>
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/main.js" defer></script>

<style>
/* NEW LAYOUT */
.layout{
display:grid;
grid-template-columns:250px 1fr 300px;
gap:20px;
padding:20px;
}

/* LEFT SIDEBAR */
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

/* RIGHT SIDEBAR */
.right-sidebar{
position:sticky;
top:80px;
height:fit-content;
}
.sidebar-box{
background:#fff;
padding:15px;
border-radius:12px;
margin-bottom:15px;
}


/* MAIN FEED */
.main-feed{
}

.top-nav a {
  font-weight: 700;
}
/* ===============================
   🎯 CENTER FEED ONLY (X STYLE)
================================ */

/* main feed spacing */
.main-feed{
display:flex !important;
flex-direction:column;
gap:10px;
}

/* tweet box */
.tweet-box{
background:#000 !important;
border:1px solid #2f3336;
padding:15px;
border-radius:16px;
}

/* textarea */
.tweet-box textarea{
width:100%;
background:transparent;
border:none;
outline:none;
color:#e7e9ea;
font-size:16px;
}

/* upload area */
#drop-area{
margin-top:10px;
background:#000;
border:2px dashed #2f3336 !important;
color:#71767b;
transition:0.2s;
}

#drop-area:hover{
border-color:#1d9bf0 !important;
}

/* tweet button */
.tweet-box button{
margin-top:10px;
background:#1d9bf0;
color:#fff;
border:none;
padding:8px 18px;
border-radius:25px;
font-weight:bold;
cursor:pointer;
}

/* tweet cards */
.tweet{
background:#000 !important;
border-bottom:1px solid #2f3336;
padding:15px;
transition:0.2s;
}

/* hover like X */
.tweet:hover{
background:#080808;
}

/* tweet text */
.tweet p{
font-size:15px;
line-height:1.5;
color:#e7e9ea;
}

/* username + handle */
.tweet a{
color:#e7e9ea;
text-decoration:none;
}

.tweet span{
color:#71767b;
}

/* tweet images */
.tweet img{
border-radius:12px;
}

/* actions */
.tweet-actions{
display:flex;
justify-content:space-between;
margin-top:10px;
max-width:400px;
}

/* buttons */
.icon-btn{
background:transparent;
border:none;
color:#71767b;
cursor:pointer;
padding:6px 10px;
border-radius:20px;
transition:0.2s;
}

.icon-btn:hover{
background:#16181c;
color:#1d9bf0;
}

/* liked */
.liked{
color:#f91880;
}

/* comment input */
form input[type="text"]{
width:75%;
padding:8px;
border-radius:20px;
border:none;
background:#16181c;
color:#fff;
margin-top:10px;
}

/* comment button */
form button{
padding:8px 14px;
border:none;
background:#1d9bf0;
color:white;
border-radius:20px;
cursor:pointer;
margin-left:5px;
}

/* feed spacing */
#tweetFeed{
display:flex;
flex-direction:column;
gap:10px;
}
/* ===============================
   🤍 CLEAN WHITE THEME
================================ */

:root{
--bg-main:#f5f8fa;      /* page background */
--bg-card:#ffffff;      /* cards */
--bg-hover:#f0f2f5;     /* hover */
--border:#e6ecf0;

--text-main:#0f1419;
--text-secondary:#536471;

--accent:#1d9bf0;
--like:#f91880;
}

/* PAGE */
body{
background:var(--bg-main) !important;
color:var(--text-main);
}

/* SIDEBARS */
.left-sidebar,
.right-sidebar{
background:transparent !important;
}

/* CARDS */
.tweet,
.tweet-box,
.sidebar-box{
background:var(--bg-card) !important;
border:1px solid var(--border);
border-radius:16px;
}

/* spacing */
.tweet{
margin-bottom:12px;
}

/* hover */
.tweet:hover{
background:var(--bg-hover);
}

/* TEXT */
.tweet p{
color:var(--text-main);
}

.tweet span,
small{
color:var(--text-secondary) !important;
}

/* LINKS */
a{
color:var(--text-main);
}

/* BUTTONS */
button{
background:var(--accent);
color:#fff;
}

/* ICON BUTTONS */
.icon-btn{
color:var(--text-secondary);
}

.icon-btn:hover{
background:var(--bg-hover);
color:var(--accent);
}

/* LIKE */
.liked {
  color: red;
}

/* INPUTS */
textarea,
input[type="text"]{
background:#fff !important;
color:var(--text-main) !important;
border:1px solid var(--border);
border-radius:10px;
}

/* DROP AREA */
#drop-area{
background:#fff;
border:2px dashed var(--border) !important;
color:var(--text-secondary);
}

#drop-area:hover{
border-color:var(--accent) !important;
}

/* RIGHT BOX */
.sidebar-box{
background:#fff !important;
}

/* CLEAN UI */
*{
box-shadow:none !important;
}

/* ===============================
   🔍 SEARCH DROPDOWN FINAL FIX
================================ */

#searchResults {
  position: absolute;
  top: 50px;
  width: 320px;
  background: #ffffff;
  border: 1px solid #e6ecf0;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.15);
  z-index: 9999;
  max-height: 300px;
  overflow-y: auto;
  padding: 5px 0;
}

#searchResults .search-item {
  display: flex !important;
  align-items: center !important;
  gap: 12px;
  padding: 10px 14px;
  text-decoration: none;
  color: #14171a;
  transition: background 0.2s ease;
}

#searchResults .search-item:hover {
  background: #f5f8fa;
}

/* 🔥 FORCE IMAGE SIZE */
#searchResults .search-item img {
  width: 40px !important;
  height: 40px !important;
  min-width: 40px !important;
  max-width: 40px !important;
  min-height: 40px !important;
  max-height: 40px !important;
  object-fit: cover !important;
  border-radius: 50% !important;
  display: block !important;
  flex-shrink: 0 !important;
}

/* text */
.search-name {
  font-weight: 600;
  font-size: 14px;
  color: #0f1419;
}

.search-username {
  font-size: 13px;
  color: #536471;
}

/* extra protection */
#searchResults img {
  width: 40px !important;
  height: 40px !important;
  object-fit: cover !important;
}

/* 🔥 GLOBAL IMAGE FIX */
img {
  max-width: 100%;
  height: auto;
}

/* profile images only */
.profile-pic,
.tweet img,
.search-item img {
  width: 40px !important;
  height: 40px !important;
  object-fit: cover !important;
  border-radius: 50% !important;
}

#searchResults {
  position: absolute;
  top: 55px;
  left: 50%;
  transform: translateX(-50%);
  width: 320px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.15);
  z-index: 9999;
  display: none;
}

#searchResults {
  pointer-events: auto;
}

/* 🔥 FIX: when hidden, don't block clicks */
#searchResults:empty {
  display: none;
  pointer-events: none;
}
</style>

</head>

<body>

<header class="topbar">

<div class="logo">
<img src="assets/logo1.svg" class="logo-img">
<span>Mini Sweeters</span>
</div>

<div class="top-search">
<input type="text" id="userSearch" placeholder="Search users..." autocomplete="off">
<div id="searchResults"></div>
</div>

<div class="top-nav">

<a href="messages.php">
Messages
<?php if ($msg_count > 0) { ?>
<span class="badge"><?= $msg_count ?></span>
<?php } ?>
</a>

<a href="notifications.php">
Notifications
<?php if ($notif_count > 0) { ?>
<span class="badge"><?= $notif_count ?></span>
<?php } ?>
</a>

<a href="profile.php">Profile</a>
<a href="auth/logout.php">Logout</a>

</div>

</header>


<div class="layout">

<!-- LEFT SIDEBAR -->
<div class="left-sidebar">
    <h3>Menu</h3>
    <a href="home.php">🏠 Home</a>
    <a href="explore.php">🔍 Explore</a>
    <a href="notifications.php">🔔 Notifications</a>
    <a href="messages.php">💬 Messages</a>
    <a href="profile.php">👤 Profile</a>
    <button onclick="document.getElementById('tweetBox').scrollIntoView({behavior:'smooth'})">
+ Tweet
</button>
</div>

<!-- MAIN -->
<div class="main-feed">

<!-- TWEET BOX -->
<div class="tweet-box" id="tweetBox">
<form action="actions/tweet_add.php" method="post" enctype="multipart/form-data">

<textarea name="content" maxlength="280" placeholder="What's happening?" required></textarea>

<div id="drop-area" style="border:2px dashed #ccc;padding:15px;border-radius:10px;text-align:center;cursor:pointer;">
📂 Drag & Drop or Click
<input type="file" name="tweet_image[]" id="fileElem" multiple accept="image/*" style="display:none;">
</div>

<div id="preview" style="display:flex;gap:10px;margin-top:10px;flex-wrap:wrap;"></div>

<button type="submit">Tweet</button>

</form>

</div>

<!-- WHO TO FOLLOW -->
<div class="tweet">
<h3>Who to follow</h3>

<?php
$suggest = mysqli_query($conn,"
SELECT id,name,username
FROM users
WHERE id != $uid
ORDER BY RAND()
LIMIT 3
");

while($s = mysqli_fetch_assoc($suggest)){
?>

<div style="margin-top:8px;">
<a href="profile.php?id=<?= $s['id'] ?>" style="text-decoration:none;color:black;font-weight:bold;">
<?= htmlspecialchars($s['name']) ?>
</a>
<span style="color:#657786;">
@<?= htmlspecialchars($s['username']) ?>
</span>
</div>

<?php } ?>

</div>

<!-- FEED -->
<div id="tweetFeed">

<?php

$q = mysqli_query($conn,"
SELECT tweets.*, users.name, users.username, users.profile
FROM tweets
JOIN users ON users.id = tweets.user_id
ORDER BY tweets.id DESC
");

while ($t = mysqli_fetch_assoc($q)) {

$tid = (int)$t['id'];
$owner = (int)$t['user_id'];

$likes = mysqli_fetch_assoc(
  mysqli_query($conn,"SELECT COUNT(*) AS c FROM likes WHERE tweet_id=$tid")
)['c'];

$liked = mysqli_num_rows(mysqli_query(
$conn,"SELECT id FROM likes WHERE tweet_id=$tid AND user_id=$uid"
));

$comments_count = mysqli_num_rows(mysqli_query(
$conn,"SELECT id FROM comments WHERE tweet_id=$tid"
));
?>

<div class="tweet" id="tweet-<?= $tid ?>">

<div style="display:flex;align-items:center;gap:10px;">

<img src="assets/profile_pics/<?= !empty($t['profile']) ? htmlspecialchars($t['profile']) : 'default.png' ?>" 
style="width:45px;height:45px;border-radius:50%;object-fit:cover;display:block;">
<div>

<b>
<a href="profile.php?id=<?= $owner ?>" style="text-decoration:none;color:inherit;">
<?= htmlspecialchars($t['name']) ?>
<span style="color:#657786">@<?= htmlspecialchars($t['username']) ?></span>
</a>
</b>

<br>

<small style="color:#657786;font-size:12px;">
<?= date("M d H:i", strtotime($t['created_at'])) ?>
</small>

</div>

</div>

<?php if($t['is_retweet'] == 1){ ?>
<div style="color:#657786;font-size:13px;margin-top:5px;">
🔁 Retweeted
</div>
<?php } ?>

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

<div class="tweet-actions">

<button type="button" class="icon-btn" onclick="likeTweet(<?= $tid ?>)">
<span id="like-icon-<?= $tid ?>" class="<?= $liked ? 'liked' : '' ?>">
<?= $liked ? "❤️" : "🤍" ?>
</span>
<span id="like-count-<?= $tid ?>"><?= $likes ?></span>
</button>

<span class="icon-btn">💬 <?= $comments_count ?></span>

<form action="actions/retweet.php" method="post" style="display:inline;">
<input type="hidden" name="tweet_id" value="<?= $tid ?>">
<button type="submit" class="icon-btn">🔁</button>
</form>

<?php if ($owner === $uid) { ?>
<a href="actions/tweet_delete.php?id=<?= $tid ?>" class="icon-btn delete" onclick="return confirm('Delete this tweet?')">❌</a>
<?php } ?>

</div>

<form action="actions/comment.php" method="post">
<input type="hidden" name="tweet_id" value="<?= $tid ?>">
<input type="text" name="comment" placeholder="Write a comment..." required>
<button type="submit">Post</button>
</form>

<?php
$cq = mysqli_query($conn,"
SELECT comments.*, users.username
FROM comments
JOIN users ON users.id = comments.user_id
WHERE comments.tweet_id=$tid
ORDER BY comments.id ASC
");

while ($c = mysqli_fetch_assoc($cq)) {
?>

<div style="margin-left:15px;margin-top:6px;font-size:14px;">
<b>@<?= htmlspecialchars($c['username']) ?>:</b>
<?= htmlspecialchars($c['comment']) ?>

<?php if ($c['user_id'] == $uid) { ?>
<a href="actions/comment_delete.php?id=<?= $c['id'] ?>" style="color:red;margin-left:6px;">🗑</a>
<?php } ?>

</div>

<?php } ?>

</div>

<?php } ?>

</div>

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

<!-- JS FOR PREVIEW -->
<script>
const dropArea = document.getElementById("drop-area");
const fileInput = document.getElementById("fileElem");
const preview = document.getElementById("preview");

let filesArray = [];

dropArea.addEventListener("click", () => fileInput.click());

fileInput.addEventListener("change", (e) => handleFiles(e.target.files));

dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.style.background = "#f0f0f0";
});

dropArea.addEventListener("dragleave", () => {
    dropArea.style.background = "transparent";
});

dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.style.background = "transparent";
    handleFiles(e.dataTransfer.files);
});

function handleFiles(files) {
    for (let file of files) {
        if (!file.type.startsWith("image/")) continue;

        filesArray.push(file);

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "80px";
            img.style.height = "80px";
            img.style.objectFit = "cover";
            img.style.borderRadius = "10px";
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }

    const dt = new DataTransfer();
    filesArray.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}
</script>

</body>
</html>