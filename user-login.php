<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/user.php';
  
  if ( isset( $_uid ) ) exiter('char-home');
  
  // -- Login --
  if ( isset( $_POST['login'] ) )
  {
    $login = USER_login( $_POST['username'] ?? '', $_POST['password'] ?? '' );
    
    if ( $login ) exiter('char-home');
    else JS_add_message('wrong credentials');
  }

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Login</h1>

<form method="POST">
  
  Username:
  
  <br />
  
  <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" />
  
  <br />
  
  Password:
  
  <br />
  
  <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" />
  
  <br />
  <br />
  
  <button type="submit" name="login">Login</button>
  
</form>

<a href="user-register">Register</a>

<hr>
