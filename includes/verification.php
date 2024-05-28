<?php

if(!isset($_SESSION['role'])) {
    header('Location: ../public/login.php');
    exit();
}
