<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/user.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  // -- Update Email --
  if ( isset( $_POST['new-email'] ) )
  {
    $update_email = USER_update_email( $_POST['new-email'], $_POST['password'] ?? '' );
    
    // Success or failure message.
    JS_add_message( is_string( $update_email ) ? $update_email : 'Email updated!' );
  }
  
  // -- Update Password --
  if ( isset( $_POST['new-password'] ) )
  {
    $update_password = USER_update_password( $_POST['new-password'], $_POST['password'] ?? '' );
    
    // Success or failure message.
    JS_add_message( is_string( $update_password ) ? $update_password : 'Password updated!' );
  }
  
  $_email = USER_get_current_email();

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Account Settings</h1>

<!-- Update Email -->
<section>
  <br />
  <h2>Update Email</h2>

  <form method="POST">
    
    New e-mail:
    <br />
    
    <input type="email" style="width: 256px; text-align: center;" name="new-email" maxlength="48" placeholder="<?= $_email ?>" />
    
    <br />
    <br />
    
    Password:
    <br />
    
    <input type="password" name="password" />
    
    <br />
    <br />
    
    <button type="submit">Update Email</button>
    
    <br />
    
  </form>
</section>

<!-- Update Password -->
<section>
  <br />
  <h2>Update Password</h2>
  
  <form method="POST">
    
    Old password:
    
    <br />
    
    <input type="password" name="password" />
    
    <br />
    <br />
    
    New password:
    
    <br />
    
    <input type="password" name="new-password" />
    
    <br />
    <br />
    
    <button type="submit">Update Password</button>
    
    <br />
    
  </form>
</section>
