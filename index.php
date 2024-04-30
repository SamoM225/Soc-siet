<?php
session_start();
include_once 'classes.php';
include_once 'db_inc.php';
$login = new Login($pdo);
$user = new Account();
$post = new Post($pdo);
if ($_SESSION['username'] == NULL) {
    header('Location: login.php');
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['description'])) {
        $description = $_POST['description'];
        $userId = $_SESSION['user_id'];
        if (!empty($_FILES['picture']['tmp_name'])) {
            $imgPath = $post->uploadPicture($_FILES['picture']);
            if ($imgPath) {
                if ($post->createPost($userId, $description, $imgPath)) {
                    echo "Post created successfully.";
                } else {
                    echo "Failed to create post.";
                }
            } else {
                echo "Failed to upload picture.";
            }
        } else {
            if ($post->createPost($userId, $description, $imgPath = NULL)) {
                echo "Post created successfully.";
            } else {
                echo "Failed to create post.";
            }
        }
    } else {
        echo "Description is required.";
    }
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Facebook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <script src="js/functions.js"></script>
</head>

<body>

    <?php
    include 'header.php'
        ?>

    </nav><?php
    echo $_SESSION['pfp'];
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <br />
                <div class="list-group">
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <img src="images/profile-default.png" class="rounded-circle"
                            style="width: 22px; margin-right: 4px" />
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-feeds"><label
                                style="width: 110px; font-size: 12px; margin-left: 28px;">News Feed</label></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-messenger"><span>Messenger</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-watch"><span>Watch</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-marketplace"><span>Marketplace</span></div>
                    </a>
                </div>
                <label class="fc-label">Explore</label>
                <div class="list-group">
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-lembrancas"><span>Memories</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-salvos"><span>Saved</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-grupos"><span>Groups</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-paginas"><span>Pages</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">
                        <div class="fc-icon fc-icon-eventos"><span>Events</span></div>
                    </a>
                    <a href="#" class="list-group-item fc-list-group-item list-group-item-action">See more...</a>
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
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2" style="text-align: right">
                                        <img src="images/profile-default.png" class="rounded-circle"
                                            style="width: 44px; margin-top: 10px;">
                                    </div>
                                    <div class="col-md-10">
                                        <?php echo '<textarea name="description" class="form-control" placeholder="What are you thinking,"' . $_SESSION['username'] . '></textarea>'; ?>
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
                include_once 'functions.php';
                include_once 'db_inc.php';
                $posts = fetchPostsFromDatabase($pdo);
                foreach ($posts as $post) {
                    renderPost($post);
                }
                $conn = null;
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
                        <div class="row">
                            <div class="col-md-2" style="text-align: right">
                                <img src="images/profile-default.png" class="rounded-circle"
                                    style="width: 44px; margin-top: 10px" />
                            </div>
                            <div class="col-md-10">
                                <div style="margin-top: 10px">
                                    <a href="#">Lorem ipsum dolor</a><br />
                                    <span>3 mutual friends</span><br />
                                    <button class="fc-btn fc-btn-default">Confirm</button>
                                    <button class="fc-btn fc-btn-default">Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="text-align: right">
                                <img src="images/profile-default.png" class="rounded-circle"
                                    style="width: 44px; margin-top: 10px" />
                            </div>
                            <div class="col-md-10">
                                <div style="margin-top: 10px">
                                    <a href="#">Lorem ipsum dolor</a><br />
                                    <span>10 mutual friends</span><br />
                                    <button class="fc-btn fc-btn-default">Confirm</button>
                                    <button class="fc-btn fc-btn-default">Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="text-align: right">
                                <img src="images/profile-default.png" class="rounded-circle"
                                    style="width: 44px; margin-top: 10px" />
                            </div>
                            <div class="col-md-10">
                                <div style="margin-top: 10px">
                                    <a href="#">Lorem ipsum dolor</a><br />
                                    <span>5 mutual friends</span><br />
                                    <button class="fc-btn fc-btn-default">Confirm</button>
                                    <button class="fc-btn fc-btn-default">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="card">
                    <div class="card-header fc-card-header-secondary">
                        People you may know
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2" style="text-align: right">
                                <img src="images/profile-default.png" class="rounded-circle"
                                    style="width: 44px; margin-top: 10px" />
                            </div>
                            <div class="col-md-10">
                                <div style="margin-top: 10px">
                                    <a href="#">Lorem ipsum dolor</a><br />
                                    <button class="fc-btn fc-btn-default">Confirm</button>
                                    <button class="fc-btn fc-btn-default">Delete</button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2" style="text-align: right">
                                <img src="images/profile-default.png" class="rounded-circle"
                                    style="width: 44px; margin-top: 10px" />
                            </div>
                            <div class="col-md-10">
                                <div style="margin-top: 10px">
                                    <a href="#">Lorem ipsum dolor</a><br />
                                    <button class="fc-btn fc-btn-default">Confirm</button>
                                    <button class="fc-btn fc-btn-default">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br />
                <div class="card">
                    <div class="card-header fc-card-header-secondary">
                        Sponsored
                    </div>
                    <div class="card-body">
                        <img src="images/banner-default.png" />
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
                include 'footer.php'
                    ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</html>