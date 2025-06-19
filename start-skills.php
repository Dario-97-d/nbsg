<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/start.php';

  if ( ! isset( $_uid ) ) exiter('index');
  
  if ( START_has_clan() ) exiter('char-home');
  
  if ( ! isset( $_GET['clan-name'] ) ) exiter('start-clan');
  
  if ( ! START_validate_clan_name( $_GET['clan-name'] ) ) exiter('start-clan');
  
  $_clan_name = $_GET['clan-name'];
  
  // -- Start Skill values --
  if ( isset( $_POST['start'] ) )
  {
    START_char(
      $_clan_name,
      [
        'kenjutsu' => $_POST['kenjutsu'] ?? 0,
        'shuriken' => $_POST['shuriken'] ?? 0,
        'taijutsu' => $_POST['taijutsu'] ?? 0,
        'ninjutsu' => $_POST['ninjutsu'] ?? 0,
        'genjutsu' => $_POST['genjutsu'] ?? 0
      ]
    );
    
    exiter('char-home');
  }
  
  $_initial_skill_values = [
    'Faruni' => [ 1, 1, 1, 3, 1 ],
    'Wyroni' => [ 1, 1, 1, 3, 1 ],
    'Raiyni' => [ 1, 1, 1, 3, 1 ],
    'Rokuni' => [ 1, 1, 1, 3, 1 ],
    'Watoni' => [ 1, 1, 1, 3, 1 ],
    'Tameru' => [ 1, 1, 5, 0, 0 ],
    'Tayuga' => [ 1, 1, 3, 1, 1 ],
    'Kensou' => [ 3, 1, 1, 1, 1 ],
    'Surike' => [ 1, 3, 1, 1, 1 ],
    'Geniru' => [ 1, 1, 1, 1, 3 ],
  ][ ucfirst( $_clan_name ) ];

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1><?= $_clan_name ?></h1>

<h3><a href="start-clan">Restart</a></h3>

<form action="start-skills?clan-name=<?= $_clan_name ?>" method="POST">
  <table class="table-char-skills">
    <tr>
      <th>kenjutsu</th>
      <th>shuriken</th>
      <th>taijutsu</th>
      <th>ninjutsu</th>
      <th>genjutsu</th>
    </tr>
    
    <tr>
      <td id="kenjutsu"><?= $_initial_skill_values[0] ?></td>
      <td id="shuriken"><?= $_initial_skill_values[1] ?></td>
      <td id="taijutsu"><?= $_initial_skill_values[2] ?></td>
      <td id="ninjutsu"><?= $_initial_skill_values[3] ?></td>
      <td id="genjutsu"><?= $_initial_skill_values[4] ?></td>
    </tr>
    
    <tr id="tr-skill-increments">

      <td><button type="button" onclick="incrementSkill('kenjutsu')">+1</button></td>
      <td><button type="button" onclick="incrementSkill('shuriken')">+1</button></td>
      <td><button type="button" onclick="incrementSkill('taijutsu')">+1</button></td>
      <?php if ( $_clan_name === 'Tameru')
      {
        ?>
        <td colspan="2">No chakra control</td>
        <?php
      }
      else
      {
        ?>
        <td><button type="button" onclick="incrementSkill('ninjutsu')">+1</button></td>
        <td><button type="button" onclick="incrementSkill('genjutsu')">+1</button></td>
        <?php
      }
      ?>
    </tr>
  </table>
  
  <h3 id="h3-left-to-upgrade">Still left to upgrade: <span id="still-left-to-upgrade">3</span></h3>
  
  <div>
    <br>
    
    <input type="hidden" id="button-submit-skills" name="start" value="Start" />
    
    <input type="hidden" id="input-kenjutsu" name="kenjutsu" />
    <input type="hidden" id="input-shuriken" name="shuriken" />
    <input type="hidden" id="input-taijutsu" name="taijutsu" />
    <input type="hidden" id="input-ninjutsu" name="ninjutsu" />
    <input type="hidden" id="input-genjutsu" name="genjutsu" />
  </div>
</form>

<script type="text/javascript">
  // -- Increment current Skill value --
  function incrementSkill(skillName)
  {
    // Increment value in the table.
    const targetElement = document.getElementById(skillName);
    targetElement.textContent = parseInt(targetElement.textContent, 10) + 1;
    
    // Check current total value of skills.
    const currentTotalSKills = getCurrentTotalSkills();
    if ( currentTotalSKills === 10 )
    {
      // Prepare for form submission.
      updateSkillsValues();
      updateUiForSubmission();
    }
    else
    {
      // Update message about remaining available skill points.
      document.getElementById('still-left-to-upgrade').innerHTML = 10 - currentTotalSKills;
    }
  }
  
  // -- Get current total value of Skills --
  function getCurrentTotalSkills()
  {
    const skillNames = ['kenjutsu', 'shuriken', 'taijutsu', 'ninjutsu', 'genjutsu'];
    let total = 0;
    skillNames.forEach(id =>
    {
      const td = document.getElementById(id);
      if (td)
      {
        // Add skill value to total.
        total += parseInt(td.textContent, 10) || 0;
      }
    });
    
    return total;
  }
  
  // -- Update UI elements to allow user to submit --
  function updateUiForSubmission()
  {
    // Hide increment elements.
    document.getElementById('h3-left-to-upgrade').style.display = 'none';
    document.getElementById('tr-skill-increments').style.display = 'none';
    
    // Show submit button by changing input type from hidden to submit.
    document.getElementById('button-submit-skills').type = 'submit';
  }
  
  // -- Prepare Skill values for submission --
  function updateSkillsValues()
  {
    document.getElementById('input-kenjutsu').value = document.getElementById('kenjutsu').textContent;
    document.getElementById('input-shuriken').value = document.getElementById('shuriken').textContent;
    document.getElementById('input-taijutsu').value = document.getElementById('taijutsu').textContent;
    document.getElementById('input-ninjutsu').value = document.getElementById('ninjutsu').textContent;
    document.getElementById('input-genjutsu').value = document.getElementById('genjutsu').textContent;
  }
</script>
