<?php

require 'database.php';

//get file info
$fileName = $_FILES['fileToUpload']['name'];
$tempFileName = $_FILES['fileToUpload']['tmp_name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileType = $_FILES['fileToUpload']['type'];
$fileDescription = $_POST['fileDescription'];

//get full path
$fileLocation = "uploads/";
$fullPath = $fileLocation . $fileName;

$fp = fopen($tempFileName, 'r');
$content = fread($fp, filesize($tempFileName));
$content = addslashes($content);
fclose($fp);

$pdo = Database::connect();

$fileExists = false;
$sql = "SELECT filename FROM upload WHERE filename='$fileName'";

//check if the files already in the db
foreach ($pdo->query($sql) as $row) {
    if($row['filename'] == $fileName) {
        $fileExists = true;
    }
}

if($fileExists) {
    echo "File " . $fileName . " already exists in the database. Please use a different name.";
    exit();
}

//see if image is too big
if ($fileSize > 2000000) {
    echo "Sorry, your file is too large.";
    exit();
}

//try to upload
echo "The file " . $fileName . " was succesfully uploaded!";
$sql = "INSERT INTO upload(id,filename,filetype,filesize,description,content)".
       "VALUES ('0','$fileName','$fileType','$fileSize', '$fileDescription','$content')";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$q = $pdo->prepare($sql);
$q->execute(array());

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