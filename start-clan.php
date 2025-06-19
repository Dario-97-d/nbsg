<?php

  require_once 'backend/backstart.php';
  require_once 'functions/features/start.php';

  if ( ! isset( $_uid ) ) exiter('index');
  
  // Exit if char has clan.
  if ( START_has_clan() ) exiter('clan-hall');

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Clan Style</h1>

<form action="start-skills" method="GET">
  <select class="select-clan" name="clan-name">
    <option hidden>Select clan</option>
    <option>Tameru</option>
    <option>Tayuga</option>
    <option>Kensou</option>
    <option>Surike</option>
    <option>Geniru</option>
    <option>Faruni</option>
    <option>Wyroni</option>
    <option>Raiyni</option>
    <option>Rokuni</option>
    <option>Watoni</option>
  </select>
  
  <br />
  <br />
  
  <button type="submit">Choose</button>
</form>

<table>
  
  <tr>
    <th>Tameru</th>
    <td>Close combat, no chakra, extreme body</td>
  </tr>
  
  <tr>
    <th>Tayuga</th>
    <td>Close combat using chakra to enhance damage</td>
  </tr>
  
  <tr>
    <th>Kensou</th>
    <td>Sword fight, can chanel chakra through blade</td>
  </tr>
  
  <tr>
    <th>Surike</th>
    <td>Throw shuriken and other small weapons precisely</td>
  </tr>
  
  <tr>
    <th>Geniru</th>
    <td>Grab oponents in illusion</td>
  </tr>
  
  <tr>
    <th>Faruni</th>
    <td>Elemental ninjutsu, fire element</td>
  </tr>
  
  <tr>
    <th>Wyroni</th>
    <td>Elemental ninjutsu, air element</td>
  </tr>
  
  <tr>
    <th>Raiyni</th>
    <td>Elemental ninjutsu, ligthning element</td>
  </tr>
  
  <tr>
    <th>Rokuni</th>
    <td>Elemental ninjutsu, rock element</td>
  </tr>
  
  <tr>
    <th>Watoni</th>
    <td>Elemental ninjutsu, water element</td>
  </tr>
  
</table>

<p>
  Generally, all chars can develop any style.
  <br />
  It's just that each clan favours specific skills.
  <br />
  Geniru will be much more skilled to perform Genjutsu
  <br />
  than any other clan, while Tameru can't.
  <br />
  But Tameru are the most physically suited
  <br />
  to open the chakra gates, while Geniru can't.
</p>
