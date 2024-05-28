<header>
    <nav class="navbar navbar-expand-lg navbar-primary" style="background-color: #664878">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if ($_SESSION['role'] == 'admin') {
                    echo '<div class="input-group fc-input-group-navbar mb-3">
                <a href="../admin/userinfo.php" class="btn btn-danger">Admin</a>
            </div>';
                }
                ?>
                <div class="input-group fc-input-group-navbar mb-3">
                    <a href="../public/index.php" class="btn" style="background-color: #5c60d6">Home</a>
                </div>


                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fc-nav-link" href="../public/userprofile.php">
                        <img class="rounded-circle img-thumbnail img-fluid" src="<?php echo $_SESSION['pfp']; ?>" alt="Not Found" style="max-height: 2.5rem;" onerror="this.src='../assets/images/profile-default.png';">
                        
                            <?php
                            echo $_SESSION['username'];
                            ?>
                        </a>
                    </li>

                    
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMore" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="fc-icon-navbar bi bi-caret-down"></div>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMore">
                            <li><a class="dropdown-item" href="../includes/logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
