<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/mail.php';

  if ( ! isset( $_uid ) ) exiter('index');
  
  // -- Delete Message --
  if ( isset( $_POST['delete-msg-id'] ) )
  {
    MAIL_delete_message( $_POST['delete-msg-id'] );
    
    JS_add_message('PM deleted.');
  }
  
  $_messages = MAIL_get_received();

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Mailbox</h1>

<?= VIEW_Mail_navbar() ?>

<?php if ( empty( $_messages ) )
{
  ?>
  Mailbox is empty.
  <?php
}
else
{
  foreach( $_messages as $row )
  {
    ?>
    
    <b>
      
      <?= $row['msg_time'] ?>
      
    </b> || <b>
      
      From:
      
    </b> <a href="char-profile?id=<?= $row['sender_id'] ?>">
      
      <?= $row['sender_username'] ?>
      
    </a> || <a href="mail-write?to-username=<?= $row['sender_username'] ?>">
      
      Reply
      
    </a>
    
    <textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
    
    <form method="POST">
      <button type="submit" name="delete-msg-id" value="<?= $row['msg_id'] ?>">Delete</button>
    </form>
    
    <?php
  }
}
?>
