<?php
session_start();
require 'database.php';
if($_GET) $errorMessage = $_GET['errorMessage'];
else $errorMessage = '';
$errorMessage = $_GET['errorMessage'];
if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = MD5($password);
    
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM users WHERE email = '$username' AND password = '$password' LIMIT 1";
    $q = $pdo->prepare($sql);
    $q->execute(array());
    $data = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();
    if($data){
        $_SESSION["username"] = $username;
        header("Location: success.php");
    }
    else{
        header("Location: login.php?errorMessage=Invalid");
        exit();
    }
}
?>

<h1>Log in</h1>
<form class="form-horizontal" action="login.php" method="post">

    <div class="control-group">
        <label class="control-label">Username (Email)</label>
        <div class="controls">
        <p style='color: red;'><?php echo $errorMessage; ?></p>
            <input name="username" type="text" placeholder="me@email.com">
            <input name="password" type="password" required>
            <button type="submit" class="btn btn-success">Sign in </button>
            <a href='logout.php'>Log out </a>
        </div>
        <div class="controls">
            <a href='create.php'>Create a new user!</a>
        </div>
    </div>
    
</form>