<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors",1);
include_once 'classes.php';
include_once 'db_inc.php';
include_once 'verification.php';
$account = new Post();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    if($file['error'] === UPLOAD_ERR_OK) {
        $account->uploadPfp($file);
        $pfp = $_SESSION['pfp'];
        echo 'Profile picture uploaded successfully';
    } else {
        echo 'Failed to upload profile picture';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php include 'header.php'; ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <img src="<?php echo $_SESSION['pfp']; ?>" alt="Profile Picture" class="img-fluid rounded-circle">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post"
                    enctype="multipart/form-data" class="mt-4">
                    <input type="file" name="profile_pic" class="form-control">
                    <input type="submit" value="Upload" class="btn btn-primary mt-2">
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
