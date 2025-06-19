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
  In the training grounds of the village,
  <br />
  chars from the clan train their skills.
</h4>

<?= VIEW_Char_skills( $_char ); ?>

<h3>
  <a href="char-train">Train alone</a>
</h3>

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
  
  <table class="table-generic">
    <?php if ( empty( $_clan_members_to_train_with ) )
    {
      ?>
      <p>There's no char to train with.</p>
      <?php
    }
    else
    {
      ?>
      <tr>
        <th>Lv</th>
        <th>Char</th>
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
            <?= VIEW_Skills_inline( $row ) ?>
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
