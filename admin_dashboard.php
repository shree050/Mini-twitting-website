<?php
session_start();
include "../config/db.php";

if(!isset($_SESSION['admin_id'])){
header("Location: admin_login.php");
exit();
}

$admin_id=$_SESSION['admin_id'];

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn,$_GET['search']) : "";

/* LOG FUNCTION */

function logAction($conn,$admin_id,$action){
mysqli_query($conn,"INSERT INTO admin_logs (admin_id,action) VALUES ('$admin_id','$action')");
}

/* ACTIONS */

if(isset($_GET['delete_user'])){
$id=$_GET['delete_user'];
mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
logAction($conn,$admin_id,"Deleted user $id");
}

if(isset($_GET['ban_user'])){
$id=$_GET['ban_user'];
mysqli_query($conn,"UPDATE users SET status='banned' WHERE id='$id'");
logAction($conn,$admin_id,"Banned user $id");
}

if(isset($_GET['unban_user'])){
$id=$_GET['unban_user'];
mysqli_query($conn,"UPDATE users SET status='active' WHERE id='$id'");
logAction($conn,$admin_id,"Unbanned user $id");
}

if(isset($_GET['suspend_user'])){
$id=$_GET['suspend_user'];
mysqli_query($conn,"UPDATE users SET status='suspended' WHERE id='$id'");
logAction($conn,$admin_id,"Suspended user $id");
}

if(isset($_GET['delete_tweet'])){
$id=$_GET['delete_tweet'];
mysqli_query($conn,"DELETE FROM tweets WHERE id='$id'");
logAction($conn,$admin_id,"Deleted tweet $id");
}

/* STATS */

$stats=[];

$q=mysqli_query($conn,"SELECT COUNT(*) c FROM users");
$stats['users']=mysqli_fetch_assoc($q)['c'];

$q=mysqli_query($conn,"SELECT COUNT(*) c FROM tweets");
$stats['tweets']=mysqli_fetch_assoc($q)['c'];

$q=mysqli_query($conn,"SELECT COUNT(*) c FROM likes");
$stats['likes']=mysqli_fetch_assoc($q)['c'];

$q=mysqli_query($conn,"SELECT COUNT(*) c FROM comments");
$stats['comments']=mysqli_fetch_assoc($q)['c'];

$q=mysqli_query($conn,"SELECT COUNT(*) c FROM moderation_queue WHERE status='pending'");
$stats['moderation']=mysqli_fetch_assoc($q)['c'];

/* USERS */

$users=mysqli_query($conn,"
SELECT * FROM users
WHERE username LIKE '%$search%' OR email LIKE '%$search%'
ORDER BY id DESC
");

/* TWEETS */

$tweets=mysqli_query($conn,"
SELECT tweets.*,users.username
FROM tweets
JOIN users ON users.id=tweets.user_id
ORDER BY tweets.id DESC
");

/* COMMENTS */

$comments=mysqli_query($conn,"
SELECT comments.*,users.username
FROM comments
JOIN users ON users.id=comments.user_id
ORDER BY comments.id DESC
");

/* REPORTS */

$reports=mysqli_query($conn,"
SELECT reports.*,tweets.content,users.username
FROM reports
JOIN tweets ON tweets.id=reports.tweet_id
JOIN users ON users.id=reports.reported_by
");

/* ADMIN LOGS */

$logs=mysqli_query($conn,"
SELECT * FROM admin_logs
ORDER BY id DESC
LIMIT 20
");

/* ANALYTICS */

$analytics=mysqli_query($conn,"
SELECT DATE(created_at) day,COUNT(*) total
FROM tweets
GROUP BY DATE(created_at)
ORDER BY DATE(created_at) DESC
LIMIT 7
");

$labels=[];
$data=[];

while($r=mysqli_fetch_assoc($analytics)){
$labels[]=$r['day'];
$data[]=$r['total'];
}

$labels=array_reverse($labels);
$data=array_reverse($data);

?>

<!DOCTYPE html>
<html>
<head>

<title>Twitter Admin</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
margin:0;
font-family:Segoe UI;
background:#f5f8fa;
}

.sidebar{
position:fixed;
width:240px;
height:100%;
background:#15202b;
color:white;
padding:20px;
}

.sidebar h2{
margin-bottom:40px;
}

.sidebar a{
display:block;
padding:12px;
color:#d9d9d9;
text-decoration:none;
border-radius:8px;
margin-bottom:6px;
}

.sidebar a:hover{
background:#1d9bf0;
}

.topbar{
position:fixed;
left:240px;
right:0;
top:0;
height:60px;
background:white;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 25px;
box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.logout{
background:#e0245e;
color:white;
padding:8px 15px;
border-radius:8px;
text-decoration:none;
}

.main{
margin-left:260px;
padding:90px 40px;
}

.stats{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:20px;
border-radius:12px;
text-align:center;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
}

table{
width:100%;
border-collapse:collapse;
background:white;
margin-top:20px;
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

.profile{
width:40px;
height:40px;
border-radius:50%;
object-fit:cover;
}

.btn{
padding:6px 12px;
border-radius:20px;
font-size:12px;
color:white;
text-decoration:none;
}

.delete{background:red;}
.ban{background:black;}
.suspend{background:orange;}

.actions{
display:flex;
gap:5px;
justify-content:center;
flex-wrap:wrap;
}

.unban{
background:green;
}

.dark{
background:#15202b;
color:white;
}

.dark table{
background:#192734;
}

</style>

</head>

<body>

<div class="sidebar">

<h2>Admin</h2>

<a href="#dashboard">Dashboard</a>
<a href="#users">Users</a>
<a href="#tweets">Tweets</a>
<a href="#comments">Comments</a>
<a href="#reports">Reports</a>
<a href="#logs">Admin Logs</a>
<a href="moderation.php">Moderation Queue</a>

</div>

<div class="topbar">

<h3>Mini Sweeters Admin</h3>

<div>

<button onclick="toggleDark()">🌙</button>

<a class="logout" href="logout.php">Logout</a>

</div>

</div>

<div class="main">

<h2 id="dashboard">Dashboard</h2>

<div class="stats">

<div class="card">
<h3>Users</h3>
<p style="font-size:28px;"><?php echo $stats['users']; ?></p>
</div>

<div class="card">
<h3>Tweets</h3>
<p style="font-size:28px;"><?php echo $stats['tweets']; ?></p>
</div>

<div class="card">
<h3>Likes</h3>
<p style="font-size:28px;"><?php echo $stats['likes']; ?></p>
</div>

<div class="card">
<h3>Comments</h3>
<p style="font-size:28px;"><?php echo $stats['comments']; ?></p>
</div>

<div class="card">
<h3>Moderation</h3>
<p style="font-size:28px;"><?php echo $stats['moderation']; ?></p>

<?php if($stats['moderation']>0){ ?>
<p style="color:red;font-size:12px;">⚠ Pending reviews</p>
<?php } ?>

<a href="moderation.php" style="display:block;margin-top:8px;text-decoration:none;color:#1d9bf0;">Open Queue</a>

</div>

</div>

<canvas id="tweetChart"></canvas>

<script>

const labels=<?php echo json_encode($labels); ?>;
const data=<?php echo json_encode($data); ?>;

new Chart(document.getElementById("tweetChart"),{
type:'line',
data:{
labels:labels,
datasets:[{
label:'Tweet Activity',
data:data,
borderColor:'#1d9bf0'
}]
}
});

function toggleDark(){
document.body.classList.toggle("dark");
}

</script>

<h2 id="users">Users</h2>

<table>

<tr>
<th>ID</th>
<th>Profile</th>
<th>Username</th>
<th>Email</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php while($u=mysqli_fetch_assoc($users)){ 

$img="../uploads/".$u['profile'];

if(empty($u['profile']) || !file_exists($img)){
$img="../assets/default.png";
}

?>

<tr>

<td><?php echo $u['id']; ?></td>

<td><img class="profile" src="<?php echo $img; ?>"></td>

<td><?php echo $u['username']; ?></td>

<td><?php echo $u['email']; ?></td>

<td><?php echo $u['status']; ?></td>

<td class="actions">

<a class="btn suspend" href="?suspend_user=<?php echo $u['id']; ?>">Suspend</a>

<a class="btn ban" href="?ban_user=<?php echo $u['id']; ?>">Ban</a>

<a class="btn suspend" href="?unban_user=<?php echo $u['id']; ?>">Unban</a>

<a class="btn delete" href="?delete_user=<?php echo $u['id']; ?>">Delete</a>

<a class="btn suspend" href="view_user.php?id=<?php echo $u['id']; ?>">View</a>

</td>

</tr>

<?php } ?>

</table>

<h2 id="tweets">Tweets</h2>

<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Tweet</th>
<th>Action</th>
</tr>

<?php while($t=mysqli_fetch_assoc($tweets)){ ?>

<tr>

<td><?php echo $t['id']; ?></td>

<td><?php echo $t['username']; ?></td>

<td><?php echo $t['content']; ?></td>

<td>

<a class="btn delete" href="?delete_tweet=<?php echo $t['id']; ?>">Delete</a>

</td>

</tr>

<?php } ?>

</table>

<h2 id="comments">Comments</h2>

<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Comment</th>
</tr>

<?php while($c=mysqli_fetch_assoc($comments)){ ?>

<tr>

<td><?php echo $c['id']; ?></td>

<td><?php echo $c['username']; ?></td>

<td><?php echo $c['comment']; ?></td>

</tr>

<?php } ?>

</table>

<h2 id="reports">Reports</h2>

<table>

<tr>
<th>ID</th>
<th>Tweet</th>
<th>Reported By</th>
<th>Reason</th>
</tr>

<?php while($r=mysqli_fetch_assoc($reports)){ ?>

<tr>

<td><?php echo $r['id']; ?></td>

<td><?php echo $r['content']; ?></td>

<td><?php echo $r['username']; ?></td>

<td><?php echo $r['reason']; ?></td>

</tr>

<?php } ?>

</table>

<h2 id="logs">Admin Logs</h2>

<table>

<tr>
<th>ID</th>
<th>Action</th>
<th>Date</th>
</tr>

<?php while($l=mysqli_fetch_assoc($logs)){ ?>

<tr>

<td><?php echo $l['id']; ?></td>

<td><?php echo $l['action']; ?></td>

<td><?php echo $l['created_at']; ?></td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>