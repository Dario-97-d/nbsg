<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/rank.php';

  if ( ! isset( $_uid ) ) exiter('index');
  
  $_chars = RANK_get_chars();

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Ranking</h1>

<table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
  <tr>
    <th>#</th>
    <th>Clan</th>
    <th>Char</th>
    <th>Rank</th>
    <th>Lv</th>
  </tr>
  
  <?php
  $r = 0;
  foreach ( $_chars as $row )
  {
    $r++;
    ?>
    <tr>
      
      <th><?= $r ?></th>
      
      <td><?= $row['style_name'] ?></td>
      
      <td>
        <a href="char-profile?id=<?= $row['char_id'] ?>">
          <?= $row['username'] ?>
        </a>
      </td>
      
      <td><?= $row['char_rank'] ?></td>
      
      <td><?= $row['char_level'] ?></td>
      
    </tr>
    <?php
  }
  ?>
</table>
