<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/clan/clan-hall.php';

  if ( ! isset( $_uid ) ) exiter('index');

  $_clan_style_name = CLAN_Hall_get_style_name();

  $_is_user_in_clan = $_clan_style_name !== '';

  if ( $_is_user_in_clan )
  {
    $_clan_members = CLAN_Hall_get_members( $_clan_style_name );
  }

?>

<?php LAYOUT_wrap_onwards(); ?>

<?php if ( $_is_user_in_clan )
{
  ?>
  
  <h1><?= $_clan_style_name ?></h1>
  
  <h4>Clan members gather in the village</h4>
  
  <h2>
    <a href="clan-train">Train</a>
  </h2>
  
  <table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
    <tr>
      <th>Rank</th>
      <th>Lv</th>
      <th>Nin</th>
      <th>Send</th>
    </tr>
    
    <?php foreach ( $_clan_members as $row )
    {
      ?>
      <tr<?= $row['char_id'] === $_uid ? ' style="outline: 1px solid #0033CC;"' : '' ?>>
        
        <td><?= $row['char_rank'] ?></td>
        
        <td><?= $row['char_level'] ?></td>
        
        <td>
          <a href="char-profile?id=<?= $row['char_id'] ?>">
            <?= $row['username'] ?>
          </a>
        </td>
        
        <td>
          <a href="mail-write?to-username=<?= $row['username'] ?>">PM</a>
        </td>
        
      </tr>
      <?php
    }
    ?>
  </table>
  
  <?php
}
else
{
  ?>
  <h1>Clan</h1>
  
  <p style="padding: 0 32px;">
    Clans determine char's lineage.
    <br />
    Each clan has a specific set of characteristics,
    <br />
    making them more suited to certain fighting styles.
    <br />
    As an example, all Tameru are incapable of manipulating chakra,
    <br />
    so they must resort to tools and close body combat.
    <br />
    However, they are capable of opening the body chakra gates,
    <br />
    so they have unusually high physical capabilities.
  </p>
  
  <h3>
    <a href="start-clan">Choose Clan</a>
  </h3>
  <?php
}
?>
