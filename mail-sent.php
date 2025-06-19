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
  
  $_messages = MAIL_get_sent();

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Mail sent</h1>

<?= VIEW_Mail_navbar() ?>

<?php if ( empty( $_messages ) )
{
  ?>
  No messages to show.
  <?php
}
else
{
  foreach ( $_messages as $row )
  {
    ?>
    
    <b>
      
      <?= $row['msg_time'] ?>
      
    </b> || <b>
      
      PM to:
      
    </b> <a href="char-profile?id=<?= $row['receiver_id'] ?>">
      
      <?= $row['receiver_username']?>
      
    </a> <b>
      
      <?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
      
    </b> || <a href="mail-write?to-username=<?= $row['receiver_username'] ?>">
      
      Send PM
      
    </a>
    
    <textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
    
    <br />
    
    <form method="POST">      
      <button type="submit" name="delete-msg-id" value="<?= $row['msg_id'] ?>">Delete</button>
    </form>
    
    <?php
  }
}
?>
