<?php
include "config/db.php";

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) { header("Location: auth/login.php"); exit; }

$uid = (int)$_SESSION['user_id'];

/* UPDATE ONLINE STATUS */
mysqli_query($conn,"UPDATE users SET last_seen=NOW() WHERE id=$uid");

$chat_user = isset($_GET['user']) ? (int)$_GET['user'] : 0;

/* MARK SEEN */
if ($chat_user > 0) {
  mysqli_query($conn,"UPDATE messages SET seen=1 WHERE sender_id=$chat_user AND receiver_id=$uid");
}

/* USERS */
$users = mysqli_query($conn,"
SELECT u.id, u.username, u.last_seen, MAX(m.id) as last_msg_id
FROM messages m
JOIN users u ON u.id = IF(m.sender_id=$uid, m.receiver_id, m.sender_id)
WHERE m.sender_id=$uid OR m.receiver_id=$uid
GROUP BY u.id
ORDER BY last_msg_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Messages</title>

<style>
body { margin:0; font-family:Arial; background:#f5f7fb; }

.topbar {
  display:flex; justify-content:space-between;
  padding:15px; background:#000; color:white;
}

.dm-container {
  display:flex; height:90vh; margin:20px;
  background:white; border-radius:20px;
}

/* SIDEBAR */
.dm-sidebar { width:30%; padding:15px; border-right:1px solid #eee; }

.dm-user {
  padding:10px; border-radius:10px; display:block;
  margin-bottom:8px; text-decoration:none; color:#333;
}

.dm-user.active { background:#4f7cff; color:white; }

/* CHAT */
.dm-chat { width:70%; display:flex; flex-direction:column; }

.chat-box { flex:1; padding:20px; overflow:auto; }

.msg { margin:10px 0; display:flex; }
.left { justify-content:flex-start; }
.right { justify-content:flex-end; }

.bubble {
  padding:10px 15px;
  border-radius:15px;
  max-width:60%;
}

.left .bubble { background:#eee; }
.right .bubble { background:#4f7cff; color:white; }

/* IMAGE FIX 🔥 */
.bubble img {
  max-width:200px;
  border-radius:10px;
  margin-top:5px;
  display:block;
}

/* META */
.meta {
  font-size:11px;
  opacity:.6;
  margin-top:5px;
}

/* SEND */
.dm-send {
  display:flex;
  padding:10px;
  border-top:1px solid #eee;
  align-items:center;
  gap:10px;
}

.dm-send input[type="text"] {
  flex:1;
  padding:12px 15px;
  border-radius:25px;
  border:1px solid #ccc;
}

.dm-send button {
  background:#4f7cff;
  border:none;
  padding:10px 14px;
  border-radius:50%;
  color:white;
  cursor:pointer;
}

/* TYPING */
#typing { font-size:12px; color:#666; padding:5px 20px; }

.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 30px;
  background: #000;
  color: white;
}

.top-nav {
  display: flex;
  gap: 25px;
}

.top-nav a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  font-size: 16px;
}

.top-nav a:hover {
  color: #1da1f2;
}

</style>

</head>

<body>
<header class="topbar">

  <!-- LEFT: LOGO -->
  <div style="display:flex; align-items:center; gap:10px;">
    <img src="assets/logo1.svg" style="width:28px;">
    <span style="font-size:20px; font-weight:bold;">Mini Sweeters</span>
  </div>


  <!-- RIGHT: NAV -->
  <div class="top-nav">
    <a href="home.php">Home</a>
    <a href="messages.php">Messages</a>
    <a href="notifications.php">Notifications</a>
    <a href="auth/logout.php">Logout</a>
  </div>

</header>

<div class="dm-container">

<div class="dm-sidebar">
<h3>Messages</h3>

<?php while ($u = mysqli_fetch_assoc($users)) {
  $online = (strtotime($u['last_seen']) > time()-10) ? "🟢 Online" : "⚫ Offline";
?>
<a href="messages.php?user=<?= $u['id'] ?>" class="dm-user <?= $chat_user==$u['id']?'active':'' ?>">
  @<?= htmlspecialchars($u['username']) ?><br>
  <small><?= $online ?></small>
</a>
<?php } ?>

</div>

<div class="dm-chat">

<?php if ($chat_user > 0) { ?>

<div class="chat-box" id="chatBox">

<?php
$msgs = mysqli_query($conn,"
SELECT * FROM messages
WHERE (sender_id=$uid AND receiver_id=$chat_user)
OR (sender_id=$chat_user AND receiver_id=$uid)
ORDER BY id ASC
");

while ($m = mysqli_fetch_assoc($msgs)) {
$isMe = $m['sender_id']==$uid;
$time = isset($m['created_at']) ? date("h:i", strtotime($m['created_at'])) : "";
?>

<div class="msg <?= $isMe?'right':'left' ?>">
  <div class="bubble">

    <?php if ($m['type']=='image') { ?>
      <img src="<?= htmlspecialchars($m['message']) ?>">
    <?php } else { ?>
      <?= htmlspecialchars($m['message']) ?>
    <?php } ?>

    <div class="meta">
      <?= $time ?>
      <?php if ($isMe) echo $m['seen'] ? " ✓✓" : " ✓"; ?>
    </div>

    <?php if ($isMe) { ?>
    
    <a href="delete.php?id=<?= $m['id'] ?>&user=<?= $chat_user ?>" 
     onclick="return confirm('Delete this message?')" 
      style="font-size:10px;color:red;">
     Delete</a>
     <?php } ?>

  </div>
</div>

<?php } ?>

</div>

<div id="typing"></div>

<form class="dm-send" method="post" action="actions/send_message.php" enctype="multipart/form-data">

  <input type="hidden" name="receiver_id" value="<?= $chat_user ?>">

  <input type="text" name="message" placeholder="Type a message..." id="msgInput">

  <input type="file" name="image" id="imgInput" style="display:none;">

  <button type="button" onclick="document.getElementById('imgInput').click()">📷</button>

  <button type="submit">➤</button>

</form>

<?php } ?>

</div>
</div>

<script>
/* AUTO REFRESH */
setInterval(()=>{
 fetch(location.href).then(r=>r.text()).then(html=>{
  let doc=new DOMParser().parseFromString(html,'text/html');
  let newChat=doc.getElementById("chatBox");
  document.getElementById("chatBox").innerHTML=newChat.innerHTML;
 });
},2000);

/* TYPING */
let input=document.getElementById("msgInput");
input?.addEventListener("input",()=>{
 document.getElementById("typing").innerText="Typing...";
 setTimeout(()=>{document.getElementById("typing").innerText="";},1000);
});
</script>

</body>
</html>