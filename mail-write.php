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
  if ( isset( $_POST['send-msg-text'] ) )
  {
    $send_message = MAIL_send( $_POST['send-msg-text'], $_POST['to-username'] ?? '' );
    
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
  
  <textarea name="send-msg-text" maxlength="800"></textarea>
  
  <br />
  <br />
  
  <button type="submit">Send</button>
  
</form>
