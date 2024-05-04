<?php
session_start();
include_once 'db_inc.php';
include_once 'classes.php';
include_once 'verification.php';
if($_SESSION['role'] != 'admin'){
    header('Location: index.php');
}

$post = new Post($pdo);
$account = new Account();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['account_id'])) {
    if (isset($_POST['account_id'])) {
        $account_id = $_POST['account_id'];
        $account_username = $_POST['account_id'];
        $user = new Account();
        $account = $post->getUserPosts($account_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_post_id'])) {
        $post_id = $_POST['delete_post_id'];
        if($post->deletePost($post_id) == False) {
            echo 'No such post exists';
        }else{
            echo 'Post deleted';
        }
    }
if($_SERVER['REQUEST_METHOD'] == 'POST'&& isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];
    if($account->deleteAccount($user_id) == False) {
        echo 'No such user exists';
    }else{
        echo 'User deleted';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>Document</title>
</head>

<body>
    <?php include_once 'header.php'; ?>
    <div class="container">
    <div class="row">
        <div class="col-md-3">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="delete_user_id" class="form-control">Delete User by ID:</label>
                    <input type="text" class="form-control" name="delete_user_id" id="delete_user_id" rows="1">
                </div>
                <button type="submit" class="btn btn-danger" style="height: 40px;padding: 8px 16px;">Delete User</button>
            </form>
        </div>
        <div class="col-md-6 offset-md-3">
            <form action="" method="post">
                <h3>Enter Username or Account ID:</h3>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="account_id" aria-label="account_id" aria-describedby="basic-addon1">
                </div>
                <button type="submit" class="btn btn-primary" style="height: 40px;padding: 8px 16px;">Show User</button>
            </form>
        </div>
        <div class="col-md-3">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="delete_post_id" class="form-control">Delete Post by ID:</label>
                    <input type="text" class="form-control" name="delete_post_id" id="delete_post_id" rows="1">
                </div>
                <button type="submit" class="btn btn-danger" style="height: 40px;padding: 8px 16px;">Delete Post</button>
            </form>
        </div>
    </div>
</div>

    <div class="container" id="user_data">
        <?php
        if (!empty($account)):
            foreach ($account as $post):
                $img = $post['img'] ? $post['img'] : 'No Picture';
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-text">Account Name: <?php echo $post['account_name']; ?></p>
                        <h5 class="card-title">Post ID: <?php echo $post['post_id']; ?></h5>
                        <p class="card-text">Account ID: <?php echo $post['account_id']; ?></p>
                        <p class="card-text">Description: <?php echo $post['description']; ?></p>
                        <img src="<?php echo $img; ?>" class="img-fluid" alt="">
                        <p class="card-text">Post Date: <?php echo $post['post_date']; ?></p>
                    </div>
                </div>
                <?php
            endforeach;
        endif;
        ?>
    </div>

    <style>
        #user_data {
            margin-top: 20px;
        }

        #user_data h3 {
            margin-top: 20px;
        }

        #user_data .form-control {
            margin-bottom: 10px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>