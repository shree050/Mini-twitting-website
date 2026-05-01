<?php
include "../config/db.php";

$q=mysqli_query($conn,"
SELECT DATE(created_at) d,COUNT(*) c
FROM tweets
GROUP BY DATE(created_at)
ORDER BY DATE(created_at) DESC
LIMIT 7
");

$labels=[];
$data=[];

while($r=mysqli_fetch_assoc($q)){
$labels[]=$r['d'];
$data[]=$r['c'];
}

echo json_encode([
"labels"=>array_reverse($labels),
"data"=>array_reverse($data)
]);