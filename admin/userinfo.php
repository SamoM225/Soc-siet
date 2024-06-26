<?php
session_start();
include_once '../includes/db_inc.php';
include_once '../includes/classes.php';
include_once '../includes/verification.php';

if ($_SESSION['role'] != 'admin') {
    header('Location: ../public/index.php');
    exit;
}

$post = new Post();
$account = new Account();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['account_id'])) {
        $account_id = $_POST['account_id'];
        $account_username = $_POST['account_id'];
        $post_info = $post->getUserPosts($account_id);
        $userinfo = $account->getUserInfo($account_id);
    }

    if (isset($_POST['delete_post_id'])) {
        $post_id = $_POST['delete_post_id'];
        if ($post->deletePost($post_id)) {
            echo 'Post deleted';
        } else {
            echo 'No such post exists';
        }
    }

    if (isset($_POST['delete_user_id'])) {
        $user_id = $_POST['delete_user_id'];
        if ($account->deleteAccount($user_id)) {
            echo 'User deleted';
        } else {
            echo 'No such user exists';
        }
    }
    if (isset($_POST['give_rights'])) {
        $user_id = $_POST['give_rights'];
        if ($account->giveAdminRights($user_id)) {
            echo 'Given admin rights';
        }
    }
    if (isset($_POST['activate_user_id'])) {
        $user_id = $_POST['activate_user_id'];
        if ($account->activateUser($user_id)) {
            echo 'User activated';
        }
    }
    if (isset($_POST['remove_admin'])) {
        $user_id = $_POST['remove_admin'];
        if ($account->removeAdminRights($user_id)) {
            echo 'Admin rights removed';
        }
    }
    if(isset($_POST['remove_user'])){
        $comment = $_POST['remove_user'];
        if($account->removeComment($comment)){
            echo 'Komentar odstraneny0';
        }
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
    <?php include_once '../includes/header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="ajax-form">
                    <div class="mb-3">
                        <label for="delete_user_id" class="form-control">Delete User by ID:</label>
                        <input type="text" class="form-control" name="delete_user_id" id="delete_user_id" rows="1">
                    </div>
                    <button type="submit" class="btn btn-danger" style="height: 40px;padding: 8px 16px;">Delete
                        User</button>
                </form>
            </div>
            <div class="col-md-3">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="ajax-form">
                    <div class="mb-3">
                        <label for="activate_user_id" class="form-control">Activate user by ID:</label>
                        <input type="text" class="form-control" name="activate_user_id" id="activate_user_id" rows="1">
                    </div>
                    <button type="submit" class="btn btn-danger" style="height: 40px;padding: 8px 16px;">Activate
                        User</button>
                </form>
            </div>
            <div class="col-md-6">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <p>Enter Username or Account ID:</p>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="account_id" aria-label="account_id"
                            aria-describedby="basic-addon1">
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 40px;padding: 8px 16px;">Show
                        User</button>
                </form>
            </div>
            <div class="col-md-3">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <div class="mb-3">
                        <label for="delete_post_id" class="form-control">Delete Post by ID:</label>
                        <input type="text" class="form-control" name="delete_post_id" id="delete_post_id" rows="1">
                    </div>
                    <button type="submit" class="btn btn-danger" style="height: 40px;padding: 8px 16px;">Delete
                        Post</button>
                </form>
            </div>
            <div class="col-md-3">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <label for="give_admin_rights" class="form-control">Give admin rights to:</label>
                    <input type="text" class="form-control" name="give_rights" rows="1" placeholder="Write user id">
                    <button type="submit" class="btn btn-primary"
                        style="height: 40px;padding: 8px 16px;width: 9rem;margin-top: 1rem;">Give admin</button>
                </form>
            </div>
            <div class="col-md-3">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <label for="remove_admin" class="form-control">Remove Admin Rights:</label>
                    <input type="text" class="form-control" name="remove_admin" rows="1" placeholder="Admin ID:">
                    <button type="submit" class="btn btn-danger"
                        style="height: 40px;padding: 8px 16px;width: 9rem;margin-top: 1rem;">Remove admin</button>
                </form>
            </div>
            <div class="col-md-3">
                <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                    <label for="remove_comment" class="form-control">Remove comment:</label>
                    <input type="text" class="form-control" name="remove_comment" rows="1" placeholder="Comment ID:">
                    <button type="submit" class="btn btn-danger"
                        style="height: 40px;padding: 8px 16px;width: 9rem;margin-top: 1rem;">Remove comment</button>
                </form>
            </div>
        </div>
    </div>
    <div class="contain">
        <h1 class="text-center">User Info</h1>
        <?php
        if (!empty($userinfo)):
            foreach ($userinfo as $info):
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-text">Account Name: <?php echo $info['account_name']; ?></p>
                            <h5 class="card-title">Account ID: <?php echo $info['account_id']; ?></h5>
                            <p class="card-text">Creation Date: <?php echo $info['account_reg_time']; ?></p>
                            <p class="card-text">Account Role: <?php echo $info['role']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="container" id="user_data">
        <?php
        if (!empty($post_info)):
            foreach ($post_info as $post):
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
    <script src="js/functions.js"></script>
</body>

</html>