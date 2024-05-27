<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once 'classes.php';
include_once 'db_inc.php';
include_once 'verification.php';
$post = new Post();
$account = new Account();
$login = new Login($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $post->uploadPfp($_FILES['profile_pic']);
        $pfp = $_SESSION['pfp'];
        echo 'Profile picture uploaded successfully';
    }

    if (isset($_POST['rename_user'])) {
        $username = $_POST['rename_user'];
        if ($account->renameUser($username, $_SESSION['username'])) {
            echo 'Username changed successfully';
            $_SESSION['username'] = $username;
        } else {
            echo 'Failed to change username';
        }
    }

    if (isset($_POST['old_passwd']) && isset($_POST['new_passwd'])) {
        $old_password = $_POST['old_passwd'];
        $new_passwd = $_POST['new_passwd'];
        if ($account->checkAndEditPassword($_SESSION['user_id'], $old_password, $new_passwd)) {
            echo 'Heslo zmenené';
        } else {
            echo 'Pôvodné heslo nie je správne';
        }
    }
}
        ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <img src="<?php echo $_SESSION['pfp']; ?>" alt="Profile Picture" class="img-fluid rounded-circle">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                    enctype="multipart/form-data" class="mt-4">
                    <input type="file" name="profile_pic" class="form-control">
                    <input type="submit" value="Upload" class="btn btn-primary mt-2">
                </form>
            </div>
            <div class="col-md-4 d-flex flex-column justify-content-center align-items-center">
                <p style="margin-bottom: 0">Meno: <?php echo $_SESSION['username']; ?></p>
                <form action="" id="ajax-form" method="post" class="mt-4">
                    <input type="text" class="form-control" name="rename_user" rows="1">
                    <button type="submit" class="btn btn-primary mt-2"
                        style="height: 40px;padding: 8px 8px;">Rename</button>
                </form>
            </div>
            <div class="col-md-3 flex-column justify-content-right align-items-right">
                <form id="ajax-form" action="" method="post" class="mt-4">
                    <input type="password" class="form-control" name="old_passwd" placeholder="Pôvodné heslo">
                    <input type="password" class="form-control" name="new_passwd" placeholder="Nové heslo">
                    <button type="submit" class="btn btn-warning mt-2"
                        style="height: 40px; padding: 0.5rem 0.5rem;">Change password</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/functions.js"></script>
</body>

</html>