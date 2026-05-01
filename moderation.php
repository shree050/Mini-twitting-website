
<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin_id'])){
header("Location: admin_login.php");
exit();
}

$admin_id = $_SESSION['admin_id'];

/* APPROVE */

if(isset($_GET['approve'])){
$id = intval($_GET['approve']);

mysqli_query($conn,"
UPDATE moderation_queue 
SET status='approved'
WHERE id='$id'
");
}

/* DELETE */

if(isset($_GET['delete'])){
$id = intval($_GET['delete']);

$q = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT tweet_id FROM moderation_queue WHERE id='$id'
"));

mysqli_query($conn,"
DELETE FROM tweets WHERE id='".$q['tweet_id']."'
");

mysqli_query($conn,"
UPDATE moderation_queue
SET status='deleted'
WHERE id='$id'
");
}

/* MODERATION STATS */

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM moderation_queue WHERE status='pending'
"))['c'];

$approved = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM moderation_queue WHERE status='approved'
"))['c'];

$deleted = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) c FROM moderation_queue WHERE status='deleted'
"))['c'];

/* FETCH REPORTS */

$reports=mysqli_query($conn,"
SELECT moderation_queue.*,tweets.content,tweets.id AS tweet_id,users.username,
(SELECT COUNT(*) FROM likes WHERE tweet_id=tweets.id) AS like_count,
(SELECT COUNT(*) FROM comments WHERE tweet_id=tweets.id) AS comment_count
FROM moderation_queue
JOIN tweets ON tweets.id=moderation_queue.tweet_id
JOIN users ON users.id=tweets.user_id
WHERE moderation_queue.status='pending'
ORDER BY moderation_queue.id DESC
");

?>

<!DOCTYPE html>
<html>

<head>

<title>Moderation Panel</title>

<style>

body{
font-family:Segoe UI;
background:#f5f8fa;
padding:40px;
margin:0;
}

h2{
margin-bottom:20px;
}

/* STATS */

.stats{
display:flex;
gap:20px;
margin-bottom:25px;
}

.card{
background:white;
padding:15px 25px;
border-radius:10px;
box-shadow:0 3px 8px rgba(0,0,0,0.1);
text-align:center;
}

.card h3{
margin:0;
color:#1d9bf0;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

th{
background:#1d9bf0;
color:white;
padding:10px;
}

td{
padding:10px;
border-bottom:1px solid #eee;
text-align:center;
}

/* BUTTONS */

.btn{
padding:5px 10px;
border-radius:6px;
color:white;
text-decoration:none;
font-size:12px;
margin:2px;
}

.approve{background:#17bf63;}
.delete{background:#e0245e;}
.view{background:#1d9bf0;}

</style>

</head>

<body>

<h2>🚨 Moderation Queue</h2>

<div class="stats">

<div class="card">
Pending
<h3><?php echo $pending; ?></h3>
</div>

<div class="card">
Approved
<h3><?php echo $approved; ?></h3>
</div>

<div class="card">
Deleted
<h3><?php echo $deleted; ?></h3>
</div>

</div>

<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Tweet</th>
<th>Likes</th>
<th>Comments</th>
<th>Reason</th>
<th>Action</th>
</tr>

<?php while($r=mysqli_fetch_assoc($reports)){ ?>

<tr>

<td><?php echo $r['id']; ?></td>

<td><?php echo $r['username']; ?></td>

<td><?php echo htmlspecialchars($r['content']); ?></td>

<td><?php echo $r['like_count']; ?></td>

<td><?php echo $r['comment_count']; ?></td>

<td><?php echo $r['reason']; ?></td>

<td>

<a class="btn approve" href="?approve=<?php echo $r['id']; ?>">Approve</a>

<a class="btn delete" href="?delete=<?php echo $r['id']; ?>">Delete</a>

<a class="btn view" href="../tweet.php?id=<?php echo $r['tweet_id']; ?>">View</a>

</td>

</tr>

<?php } ?>

</table>

</body>
</html>

