<?php
session_start();
ini_set( 'display_errors', 1 ); 
include_once 'db_inc.php';
include_once 'classes.php';


$login = new Login($pdo);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; 
    $password = $_POST['password']; 

   
    if(empty($username) || empty($password)){
        $error = 'Všetky polia musia byť vyplnené';
    }elseif($login->verifyAccount($username) === 0){
        $error = 'Deaktivovaný účet!';
    }else{
        $login->login( $username, $password );
        
        header("Location: index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
    <div class="wrapper">
        <header>Login Form</header>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="field user">
                <div class="input-area">
                    <input type="text" name="username" placeholder="user">
                    <i class="icon fas fa-user"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Email can't be blank</div>
            </div>
            <div class="field password">
                <div class="input-area">
                    <input type="password" name="password" placeholder="Password">
                    <i class="icon fas fa-lock"></i>
                    <i class="error error-icon fas fa-exclamation-circle"></i>
                </div>
                <div class="error error-txt">Password can't be blank</div>
            </div>
            <div class="pass-txt"><a href="#">Forgot password?</a></div>
            <input type="submit" value="Login">
        </form>
        <div class="sign-txt">Not yet member? <a href="register.php">Signup now</a></div>
        <?php if(isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
    </div>
</body>
</html>
