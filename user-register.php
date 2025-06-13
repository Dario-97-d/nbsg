<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/user.php';
  
  if ( isset( $_uid ) ) exiter('char-home');
  
  // -- Register --
  if ( isset( $_POST['register'] ) )
  {
    $register = USER_register( $_POST['username'] ?? '', $_POST['password'] ?? '', $_POST['email'] ?? '' );
    
    if ( is_string( $register ) ) JS_add_message( $register );
    else
    {
      exiter('clan-hall');
    }
  }

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Register</h1>

<form method="POST">
  
  Username:
  <br />
  
  <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>" />
  <br />
  
  Password:
  <br />
  
  <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" />
  <br />
  
  E-mail:
  <br />
  
  <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" />
  <br />
  
  <br />
  <button type="submit" name="register">Start</button>
  
</form>

<a href="user-login">Login</a>

<hr>
