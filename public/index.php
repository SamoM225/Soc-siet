<?php
session_start();
include_once '../includes/classes.php';
include_once '../includes/db_inc.php';
include_once '../includes/verification.php';

$login = new Login($pdo);
$user = new Account();
$post = new Post();

$randomppl = $user->randomPeople($_SESSION['user_id']);
$friendrequests = $user->friendRequests($_SESSION['user_id']);
$friendlist = $user->friendList($_SESSION['user_id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['description'])) {
        $description = $_POST['description'];
        $userId = $_SESSION['user_id'];
        $imgPath = NULL;

        if (!empty($_FILES['picture']['tmp_name'])) {
            $imgPath = $post->uploadPicture($_FILES['picture']);
            if (!$imgPath) {
                echo "Failed to upload picture.";
                exit;
            }
        }

        if ($post->createPost($userId, $description, $imgPath)) {
            echo "Post created successfully.";
        } else {
            echo "Failed to create post.";
        }
    }

    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $postid = $_POST['postId'];
        $userid = $_SESSION['user_id'];

        if ($post->uploadComment($postid, $comment, $userid) === 1) {
            echo 'Comment added successfully';
        } else {
            echo 'Failed to add comment';
        }
    }

    if (isset($_POST['request_friend'])) {
        $userid = $_SESSION['user_id'];
        $friendid = $_POST['friend_id'];
        $status = 'Request';

        if ($user->requestFriend($userid, $friendid, $status)) {
            echo 'Friend request sent';
        } else {
            echo 'Error sending friend request';
        }
    }

    if (isset($_POST['add_friend_id'])) {
        $userid = $_SESSION['user_id'];
        $friendid = $_POST['add_friend_id'];

        try {
            if ($user->addFriend($userid, $friendid)) {
                echo 'Friend added';
            } else {
                echo 'Error accepting friend';
            }
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Facebook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body>

    <?php
    require_once '../includes/header.php'
        ?>

    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <br />
                <div class="accordion" id="friendListAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#friendListCollapse" aria-expanded="false"
                                aria-controls="friendListCollapse">
                                <img src="../assets/images/profile-default.png" class="rounded-circle"
                                    style="width: 22px; margin-right: 4px" /> Friends
                            </button>
                        </h2>
                        <div id="friendListCollapse" class="accordion-collapse collapse" aria-labelledby="headingOne"
                            data-bs-parent="#friendListAccordion">
                            <div class="accordion-body">
                                <?php foreach ($friendlist as $friend): ?>
                                    <?php
                                    $friend_pfp = !empty($friend['friend_pfp']) ? $friend['friend_pfp'] : '../assets/images/profile-default.png';
                                    $friend_pfp_style = 'width: 40px; height: 40px;';
                                    ?>
                                    <div class="friendlist-box">
                                        <a class="dropdown-item" href="#">
                                            <img src="<?php echo $friend_pfp; ?>" class="rounded-circle friendlist-img"
                                                alt="Profile Picture" style="<?php echo $friend_pfp_style; ?>">
                                            <?php echo $friend['friend_name']; ?>
                                        </a>
                                        <span class="medbr"></span>
                                        <hr>
                                        <span class="medbr"></span>
                                    </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header fc-card-header">
                                Create post
                            </div>
                            <form id="ajax-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2" style="text-align: right">
                                        <img src="../assets/images/profile-default.png" class="rounded-circle"
                                            style="width: 44px; margin-top: 10px;">
                                    </div>
                                    <div class="col-md-10">
                                        <?php echo '<textarea name="description" class="form-control" placeholder="What are you thinking, ' . $_SESSION['username'] . '"></textarea>'; ?>
                                    </div>
                                </div>
                                <hr>

                                <div class="row" style="margin-left: 0px;">
                                    <div class="col-md-6">
                                        <input type="file" name="picture" accept="image/*">
                                        <!-- File input for uploading picture -->
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="fc-btn fc-btn-rounded"
                                            style="width: 100px;">Upload</button>
                                    </div>
                                </div>
                            </form>
                            <br>
                        </div>
                    </div>

                </div>
                <br />
                <!--Vyberanie príspevkov a zobrazovanie na webstránke-->
                <?php
                require_once '../includes/functions.php';
                $posts = $post->fetchPostsFromDatabase();
                foreach ($posts as $post) {
                    renderPost($post);
                }
                ?>

                <br />
            </div>
            <div class="col-md-4">
                <br />
                <div class="card">
                    <div class="card-header fc-card-header-secondary">
                        Friend requests
                    </div>
                    <div class="card-body">
                        <?php
                        if (!empty($friendrequests)) {
                            foreach ($friendrequests as $friendrequest) {
                                renderFriendRequest($friendrequest);
                            }
                        } else {
                            echo "Žiadne žiadosti.";
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div class="card">
                    <div class="card-header fc-card-header-secondary">
                        People you may know
                    </div>
                    <div class="card-body">

                        <?php
                        if (!empty($randomppl)) {
                            foreach ($randomppl as $guy) {
                                renderPpl($guy);
                            }
                        } else {
                            echo "Žiadny náhodný užívatelia.";
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div class="card">
                    <div class="card-header fc-card-header-secondary">
                        Sponsored
                    </div>
                    <div class="card-body">
                        <img src="../assets/images/banner-default.png" />
                        <br /><br />
                        <a href="#">Lorem ipsum dolor sit amet</a><br />
                        <span>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam in feugiat mauris. Maecenas nec
                            pharetra arcu. Cras eleifend posuere dui, in molestie eros placerat vel.
                        </span>
                    </div>
                </div>

                <br />
                <div class="card">
                    <div class="card-body">
                        <span>Portuguese (Brazil)</span> · <a href="#">Portuguese (Portugal)</a> · <a href="#">English
                            (US)</a> ·
                        <a href="#">Spanish (Spain)</a> · <a href="#">French (France)</a>
                    </div>
                </div>
                <br />

                <?php
                include_once '../includes/footer.php';
                    ?>
            </div>
            
        </div>
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="js/functions.js"></script>

</html>