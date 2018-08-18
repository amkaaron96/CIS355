<?php

require 'database.php';

$pdo = Database::connect();

echo 'All files in database...<br><br>';
$sql = 'SELECT * FROM upload ' . 'ORDER BY BINARY filename ASC;';
foreach ($pdo->query($sql) as $row) {
$id = $row['id'];
$sql = "SELECT * FROM upload where id=$id";
echo $row['id'] . ' - ' . $row['filename'] . ' - ' . $row['description'] . '<br>' . '<img width=100 src="data:image/>jpeg;base64,' . base64_encode($row['content']) . '"/>' . '<br><br>';
}
echo '<br><br>';

echo '<br><button onclick=history.back()>Back</button>';

Database::disconnect();

?>