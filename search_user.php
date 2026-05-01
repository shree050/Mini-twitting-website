<?php
include "../config/db.php";

/* safe session */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) exit;

/* get search */
$q = trim($_GET['q'] ?? '');
if ($q === '') exit;

$uid = (int)$_SESSION['user_id'];

/* escape input */
$q = mysqli_real_escape_string($conn, $q);

/* 🔥 improved search (name + username) */
$sql = "
SELECT id, name, username, profile
FROM users
WHERE (username LIKE '%$q%' OR name LIKE '%$q%')
AND id != $uid
LIMIT 6
";

$res = mysqli_query($conn, $sql);

/* error check */
if (!$res) {
    echo "SQL ERROR: " . mysqli_error($conn);
    exit;
}

/* output */
while ($u = mysqli_fetch_assoc($res)) {

    $profile = !empty($u['profile']) ? htmlspecialchars($u['profile']) : 'default.png';
?>

<a href="/mini_sweeters_pro/profile.php?id=<?= $u['id'] ?>" class="search-item">

  <img src="/mini_sweeters_pro/assets/profile_pics/<?= $profile ?>" 
     alt="profile"
     style="width:40px;height:40px;border-radius:50%;object-fit:cover;">

  <div>
    <div class="search-name"><?= htmlspecialchars($u['name']) ?></div>
    <div class="search-username">@<?= htmlspecialchars($u['username']) ?></div>
  </div>

</a>

<?php } ?>