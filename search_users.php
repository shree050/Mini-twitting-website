<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION['user_id'])) exit;

$q = trim($_GET['q'] ?? '');
if ($q === '') exit;

$uid = $_SESSION['user_id'];

$res = mysqli_query(
  $conn,
  "SELECT id, name, username, profile_pic
   FROM users
   WHERE username LIKE '%$q%'
   AND id != $uid
   LIMIT 6"
);

while ($u = mysqli_fetch_assoc($res)) {

  // 🔥 FIX: safe profile image
  $profile = !empty($u['profile_pic']) ? htmlspecialchars($u['profile_pic']) : 'default.png';
?>
<a href="../profile.php?id=<?= $u['id'] ?>" class="search-item">

  <img src="../assets/profile_pics/<?= $profile ?>" alt="profile">

  <div>
    <div class="search-name"><?= htmlspecialchars($u['name']) ?></div>
    <div class="search-username">@<?= htmlspecialchars($u['username']) ?></div>
  </div>

</a>
<?php } ?>