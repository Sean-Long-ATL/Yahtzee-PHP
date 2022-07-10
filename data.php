<?php
/* These are our valid username and passwords */
$user = 'jonny4';
$pass = 'delafoo';

if (isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
    
        echo 'Welcome back ' . $_COOKIE['login'];
        header('Location: start.html');
    
} else {
    header('Location: home.php');
}
?>

