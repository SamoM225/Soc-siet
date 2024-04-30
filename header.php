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
                <a href="userinfo.php" class="btn btn-danger">Admin</a>
            </div>';
                }
                ?>
                <div class="input-group fc-input-group-navbar mb-3">
                    <a href="index.php" class="btn" style="background-color: #5c60d6">Home</a>
                </div>


                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link fc-nav-link" href="#">
                            <img src="images/profile-default.png" class="rounded-circle"
                                style="width: 22px; margin-right: 4px" />
                            <?php
                            echo $_SESSION['username'];
                            ?>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMessenger" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="fc-icon-navbar fc-icon-notificao-messenger"></div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownNotifications" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="fc-icon-navbar fc-icon-notificao-notificacoes"></div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownHelp" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="fc-icon-navbar fc-icon-notificao-ajuda"></div>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="navbarDropdownMore" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="fc-icon-navbar fc-icon-notificao-mais"></div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMore">
                            <li><a class="dropdown-item" href="#">Manage Pages</a></li>
                            <li><a class="dropdown-item" href="#">Your groups</a></li>
                            <li><a class="dropdown-item" href="#">Manage ads</a></li>
                            <li><a class="dropdown-item" href="#">Activity log</a></li>
                            <li><a class="dropdown-item" href="#">News Feed Preferences</a></li>
                            <li><a class="dropdown-item" href="#">Settings</a></li>
                            <li><a class="dropdown-item" href="logout.php">Log out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>