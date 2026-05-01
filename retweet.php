<?php
include "../config/db.php";

/* start session safely */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* check login */
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$uid = (int)$_SESSION['user_id'];

/* check tweet id */
if (!isset($_POST['tweet_id'])) {
    header("Location: ../home.php");
    exit;
}

$tid = (int)$_POST['tweet_id'];

/* get original tweet */
$result = mysqli_query($conn,
"SELECT content,image FROM tweets WHERE id=$tid LIMIT 1"
);

if (!$result || mysqli_num_rows($result) == 0) {

    echo "<script>
    alert('Tweet not found');
    window.location='../home.php';
    </script>";
    exit;
}

$tweet = mysqli_fetch_assoc($result);

$content = mysqli_real_escape_string($conn, $tweet['content']);
$image   = mysqli_real_escape_string($conn, $tweet['image']);

/* prevent duplicate retweets */
$check = mysqli_query($conn,"
SELECT id FROM tweets
WHERE user_id=$uid
AND is_retweet=1
AND content='$content'
LIMIT 1
");

if(mysqli_num_rows($check) == 0){

    /* insert retweet */
    mysqli_query($conn,"
    INSERT INTO tweets (user_id,content,image,is_retweet)
    VALUES ($uid,'$content','$image',1)
    ");

    echo "<script>
    alert('Retweeted successfully 🔁');
    window.location='../home.php';
    </script>";

}else{

    echo "<script>
    alert('You already retweeted this tweet');
    window.location='../home.php';
    </script>";

}
?>