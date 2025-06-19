<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/char/char-profile.php';

  if ( ! isset( $_uid ) ) exiter('index');

  if ( ! VALIDATE_Char_id( $_GET['id'] ?? null ) ) $_pid = $_uid;
  else $_pid = $_GET['id'];

  $_own = CHAR_Profile_get_own_info();

  $_profile = CHAR_Profile_get( $_pid );

  $_can_train = CHAR_Profile_can_train_with( $_own, $_profile );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_profile['username'] ?></h1>

<h3><?= $_profile['style_name'] ?></h3>

<?php if ( $_pid != $_uid )
{
  ?>
  <h3>
    <a href="mail-write?to-username=<?= $_profile['username'] ?>">pm</a>
  </h3>
  <?php
}
?>

<?= VIEW_Char_skills( $_profile ); ?>

<br />

<b>Skill Points: <?= $_own['skill_points'] ?> / 5</b>

<?php if ( $_can_train )
{
  ?>
  <form action="clan-train-skill" method="POST">
    <select class="select-skill" name="skill-name">
      <option hidden>-- Skill --</option>
      <option value="kenjutsu">Kenjutsu</option>
      <option value="shuriken">Shuriken</option>
      <option value="taijutsu">Taijutsu</option>
      
      <?php if ( $_own['style_name'] !== 'Tameru' )
      {
        ?>
        <option value="ninjutsu">Ninjutsu</option>
        <option value="genjutsu">Genjutsu</option>
        <?php
      }
      ?>
    </select>
    
    <button type="submit" name="char-id" value="<?= $_pid ?>">Train</button>
  </form>
  <?php
}
?>

<p>
  Rank-<?= $_profile['char_rank'] ?>
  <br />
  <b>Lv <?= $_profile['char_level'] ?></b>
</p>

<?php if ( true ) //if ( in_bonds )
{
  ?>
  <table>
    
    <tr>
      <td>Flair</td>
      
      <td><?= $_profile['flair'] ?></td>
    </tr>
    
    <tr>
      <td>Power</td>
      
      <td><?= $_profile['strength'] ?></td>
    </tr>
    
    <tr>
      <td>Speed</td>
      
      <td><?= $_profile['agility'] ?></td>
    </tr>
    
    <tr>
      <td>Jutsu</td>
      
      <td><?= $_profile['jutsu'] ?></td>
    </tr>
    
    <tr>
      <td>Tactics</td>
      
      <td><?= $_profile['tactics'] ?></td>
    </tr>
    
  </table>
  <?php
}
?>

<br />pvp wins
<br />
<br />missions:
<br />patrol - anbu
