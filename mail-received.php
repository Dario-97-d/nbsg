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
  
  // -- Set Message as Seen --
  if ( isset( $_POST['set-msg-seen-id'] ) )
  {
    MAIL_view_message( $_POST['set-msg-seen-id'] );
    
    JS_add_message('PM seen.');
  }
  
  $_messages = MAIL_get_received();

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Mailbox</h1>

<h2>
  <a href="mail-received">Mailbox</a> || <a href="mail-write">Send pm</a> || <a href="mail-sent">PMs sent</a>
</h2>

<?php if ( empty( $_messages ) )
{
  ?>
  Mailbox is empty
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
      
    </a> || <b>
      
      <?= $row['seen'] == 0 ? 'Not s' : 'S' ?>een
      
    </b> || <a href="mail-write?to-username=<?= $row['sender_username'] ?>">
      
      Reply
      
    </a>
    
    <textarea name="msg-text" disabled><?= $row['msg_text'] ?></textarea>
    
    <form method="POST">
      <table align="center">
        <?php if ( $row['seen'] !== 1 )
        {
          ?>
          <td>
            <button type="submit" name="set-msg-seen-id" value="<?= $row['msg_id'] ?>">Seen</button>
          </td>
          <?php
        }
        ?>
        
        <td>
          <button type="submit" name="delete-msg-id" value="<?= $row['msg_id'] ?>">Delete</button>
        </td>
      </table>
    </form>
    
    <?php
  }
}
?>
