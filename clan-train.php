<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/clan/clan-train.php';
  
  if ( ! isset( $_uid ) ) exiter('index');
  
  $_char = CLAN_Train_get_char();

  if ( $_char['style_name'] === '' ) exiter('clan-hall');

  $_clan_members_to_train_with = CLAN_Train_get_members_for_training( $_char );

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_char['style_name'] ?></h1>

<h4>
  In the training grounds of the village
  <br />
  nin from the clan train their skills
</h4>

<table class="table-skill" align="center">
  <tr>
    <th title="Sword Skill">kenjutsu</th>
    <th title="Shuriken Skill">shuriken</th>
    <th title="Melee Skill">taijutsu</th>
    <th title="Elemental Skill">ninjutsu</th>
    <th title="Illusion Skill">genjutsu</th>
  </tr>
  
  <tr>
    <td><?= $_char['kenjutsu'] ?></td>
    <td><?= $_char['shuriken'] ?></td>
    <td><?= $_char['taijutsu'] ?></td>
    <td><?= $_char['ninjutsu'] ?></td>
    <td><?= $_char['genjutsu'] ?></td>
  </tr>
</table>

<a href="char-train">Train alone</a>

<h2>Rank-<?= $_char['char_rank'] ?></h2>

<form action="clan-train-skill" method="POST">
  <select class="select-skill" name="skill-name">
    <option hidden>-- skill --</option>
    <option value="kenjutsu">Kenjutsu</option>
    <option value="shuriken">Shuriken</option>
    <option value="taijutsu">Taijutsu</option>
    
    <?php if ( $_char['style_name'] !== 'Tameru' )
    {
      ?>
      <option value="ninjutsu">Ninjutsu</option>
      <option value="genjutsu">Genjutsu</option>
      <?php
    }
    ?>
  </select>
  
  <button type="submit">Train</button>
  
  <table align="center" style="text-align: center;" cellpadding="8" cellspacing="0">
    <?php if ( empty( $_clan_members_to_train_with ) )
    {
      ?>
      <p>There's no nin to train with.</p>
      <?php
    }
    else
    {
      ?>
      <tr>
        <th>Lv</th>
        <th>Nin</th>
        <th>Jutsu</th>
        <th>Select</th>
      </tr>
      <?php foreach ( $_clan_members_to_train_with as $row )
      {
        ?>
        <tr>
          
          <!-- Level -->
          <td><?= $row['char_level'] ?></td>
          
          <!-- Char name and link -->
          <td>
            <a href="char-profile?id=<?= $row['char_id'] ?>">
              <?= $row['username'] ?>
            </a>
          </td>
          
          <!-- Skill levels -->
          <th>
            <?=
              $row['kenjutsu']
              .' • '.
              $row['shuriken']
              .' • '.
              $row['taijutsu']
              .' • '.
              $row['ninjutsu']
              .' • '.
              $row['genjutsu']
            ?>
          </th>
          
          <!-- Radio button -->
          <td>
            <input type="radio" name="char-id" value="<?= $row['char_id'] ?>" />
          </td>
          
        </tr>
        <?php
      }
    }
    ?>
  </table>
</form>
