
<?php
/* These are our valid username and passwords */
$user = 'jonny4';
$pass = 'delafoo';

if (isset($_POST['login']) && isset($_POST['password'])) {
              
        if (isset($_POST['remember_me'])) {
            /* Set cookie to last 1 year */
            setcookie('login', $_POST['login'], time()+60*60*24*365);
            setcookie('password', md5($_POST['password']), time()+60*60*24*365);
        
        } else {
            /* Cookie expires when browser closes */
            setcookie('login', $_POST['login']);
            setcookie('password', md5($_POST['password']));
        }
        header('Location: data.php');
        
    }  else {
    echo 'You must supply a username and password.';
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Homepage</title>
<link href="homepage.css" rel="stylesheet">
</head>
<body>
<div class="login">
  <h1>Yahtzee Game </h1>
  <form method="post" action="">
    <p><input type="text" name="login" value="" placeholder="Username"></p>
    <p><input type="password" name="password" value="" placeholder="Password"></p>
    <p class="remember_me">
      <label>
        <input type="checkbox" name="remember_me" id="remember_me">
        Remember me on this computer
      </label>
    </p>

    <p class="submit"><input type="submit" name="commit" value="Login"></p>
  </form>
</div>
</body>
</html>

