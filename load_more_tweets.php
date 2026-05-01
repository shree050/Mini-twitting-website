<?php
include "../config/db.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uid = $_SESSION['user_id'];

$offset = (int)$_GET['offset'];

$q = mysqli_query($conn,"
SELECT tweets.*,users.username,users.name
FROM tweets
JOIN users ON users.id=tweets.user_id
ORDER BY tweets.id DESC
LIMIT 10 OFFSET $offset
");

while($t=mysqli_fetch_assoc($q)){
?>

<div class="tweet">
<b>@<?= $t['username'] ?></b>

<p><?= htmlspecialchars($t['content']) ?></p>

</div>

<?php } ?>