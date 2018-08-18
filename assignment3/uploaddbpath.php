<?php

require 'database.php';

//get file info
$fileName = $_FILES['fileToUpload']['name'];
$tempFileName = $_FILES['fileToUpload']['tmp_name'];
$fileSize = $_FILES['fileToUpload']['size'];
$fileType = $_FILES['fileToUpload']['type'];
$fileDescription = $_POST['fileDescription'];

//get full path
$fileLocation = "uploads2/";
$fullPath = $fileLocation . $fileName;

$pdo = Database::connect();

$fileExists = false;
$sql = "SELECT filename FROM upload2 WHERE filename='$fileName'";

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

// Check if file already exists
if (file_exists($fullPath)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
} else{
    mkdir($fileLocation);
}

//see if image is too big
if ($fileSize > 2000000) {
    echo "Sorry, your file is too large.";
    exit();
}

//try to upload
echo "The file " . $fileName . " was succesfully uploaded!";
echo "<br>";
$sql = "INSERT INTO upload2(id,filename,filesize,filetype,description,path)".
    "VALUES ('0','$fileName','$fileSize','$fileType', '$fileDescription','$fullPath')";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$q = $pdo->prepare($sql);
$q->execute(array());

$target = $fileLocation . basename($_FILES['fileToUpload']['name']);

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target)) {
    echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded to the directory.";
} else {
    echo "Sorry, there was an error uploading your file.";
    ?>
    <br>
    <button onclick="history.back()">Back</button>
    <?php
    exit();
}

echo 'All files in database...<br><br>';
$sql = 'SELECT * FROM upload2 ' . 'ORDER BY BINARY filename ASC;';
foreach ($pdo->query($sql) as $row) {
    $id = $row['id'];
    $sql = "SELECT * FROM upload2 where id=$id";
    echo $row['id'] . ' - ' . $row['filename'] . ' - ' . $row['description'] . '<br>' . '<img width=100 src="' . $row['path'] . '"/>' . '<br><br>';
}
echo '<br><br>';

echo '<br><button onclick=history.back()>Back</button>';

Database::disconnect();

?>