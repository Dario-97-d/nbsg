<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/mail.php';

  if ( ! isset( $_uid ) ) exiter('index');
  
  // Set username to send message to.
  if ( isset( $_GET['to-username'] ) )
  {
    $_to_username = $_GET['to-username'];
  }
  
  // -- Send Message --
  if ( isset( $_POST['send-msg'] ) )
  {
    $send_message = MAIL_send( $_POST['to-username'] ?? '', $_POST['msg-text'] ?? '' );
    
    if ( is_string( $send_message ) )
    {
      JS_add_message( $send_message );
    }
    else exiter('mail-sent');
  }

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Write mail</h1>

<?= VIEW_Mail_navbar() ?>

<form method="POST">
  
  PM to:
  <br />
  
  <input type="text" style="text-align: center;" name="to-username" value="<?= $_to_username ?? '' ?>" />
  
  <br />
  <br />
  
  <textarea name="msg-text" maxlength="800"></textarea>
  
  <br />
  <br />
  
  <button type="submit" name="send-msg">Send</button>
  
</form>
