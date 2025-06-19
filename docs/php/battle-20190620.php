<?php

require_once 'backend/backstart.php';

if ( ! isset( $_uid ) ) exiter('index');

// check id was provided as int.
if ( ! is_int( $pid = array_search('Train', $_POST) ) ) exiter('clan-dojo');

if ( $_uid == $pid ) exiter('char-profile?id='. $pid);

// check skill was given.
$skills = array(
  'kenjutsu' => 'Kenjutsu',
  'shuriken' => 'Shuriken',
  'taijutsu' => 'Taijutsu',
  'ninjutsu' => 'Ninjutsu',
  'genjutsu' => 'Genjutsu' );

if ( in_array($_POST['skill'], $skills) )
{
  $skill = array_search($_POST['skill'], $skills);
}
else exiter('char-profile?id='. $pid);

echo $skill;

// -- START functions --

// randomize att to be upgraded.
function upatt( $c, $a, $m, $b )
{
  if ( in_array($c, [ 'flair', 'strength', 'agility', 'jutsu', 'tactics' ]) )
  {
    $upatt = $c;
  }
  else if ( $c == 3 )
  {
    if ( $e = substr( microtime(true), -4 ) != 0 )
    {
      $d = $e % 3;
    }
    else
    {
      $d = date( "s", time() ) % 3;
    }
  }
  else
  {
    $d = substr( microtime(true), -2 ) % $c;
  }
  
  foreach ( $b as $key => $value )
  {
    if ( $key == $d )
    {
      $upatt = $value;
    }
  }
  
  return $upatt ."=". $upatt ."+1";
}

// upgrade atts (bup: battle upgrade).
function sql_bup ( $setupatt, $_uid )
{
  sql_query("UPDATE char_attributes SET $setupatt WHERE char_id = $_uid");
}

// -- END functions --

// fetch atts from user and player.
extract(
  sql_mfa('
    SELECT
      username
      a.*,
      s.*,
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $_uid .'
  '),
  EXTR_PREFIX_ALL, 'u' );

extract(
  sql_mfa('
    SELECT
      username,
      a.*,
      s.*,
    FROM  game_users       u
    JOIN  char_attributes  a USING (char_id)
    JOIN  style_attributes s USING (char_id)
    WHERE u.char_id = '. $pid .'
  '),
  EXTR_PREFIX_ALL, 'p' );
  
// calc scores for battle.
$uscore = $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics;
$pscore = $p_flair + $p_strength + $p_agility + $p_jutsu + $p_tactics;

// determine result, based on (user/player) _total_atts.
$result = $uscore / $pscore * 8;

// go back if too much difference.
if ( $result < 4 || $result >= 12 ) exiter('char-profile?id='. $pid);

// attribute ratios.
$flair    = $u_flair    / $p_flair;
$strength = $u_strength / $p_strength;
$agility  = $u_agility  / $p_agility;
$jutsu    = $u_jutsu    / $p_jutsu;
$tactics  = $u_tactics  / $p_tactics;

// array with the ratios.
$a = array(
  "flair"    => $flair,
  "strength" => $strength,
  "agility"  => $agility,
  "jutsu"    => $jutsu,
  "tactics"  => $tactics );

// -- whatever this is --
// victor: max; victum: min.
$m = ( $result > 8 ? 'max' : 'min' );
// keys of the values from min/max of ratios.
$b = array_keys( $a, $m( $a ) );

// how many atts considered for upgrade, if 1 then $c = 'att'.
$c = $result >= 6 && $result < 10 ?
  'random' :
  count($b) > 1 ? count($b) : array_search( $m ( $a ), $a );

// if $c = [att], then it's on; if it's random, so be it.
if ( in_array($c, [ 'flair', 'strength', 'agility', 'jutsu', 'tactics' ]) )
{
  if ( $result >= 4 && $result < 12 )
  {
    $setupatt = $c ."=". $c ."+1";
  }
  else
  {
    $setupatt = $upgrade = '';
  }
}
else if ('random')
{
  // random number from microtime two chars from last three chars (different from upatt, which is -2 instead of -3.2).
  switch ( substr( microtime(true), -3, 2 ) % 5 )
  {
    case 0: $rupatt = "fla = fla + 1"; break;
    case 1: $rupatt = "pow = pow + 1"; break;
    case 2: $rupatt = "agi = agi + 1"; break;
    case 3: $rupatt = "jut = jut + 1"; break;
    case 4: $rupatt = "tac = tac + 1"; break;
    
    default:
      $rupatt = "microtime Error";
      break;
  }
  
  // check draw, if so then just upgrade $rupatt.
  if ( $result < 7 || $result >= 9 )
  {
    // no draw, so let's upgrade $rupatt + $upatt.
    $c = ( count($b) > 1 ? count($b) : array_search( $m ( $a ), $a ) );
    
    $upatt = upatt( $c, $a, $m, $b );
    
    // just in case $rupatt == $upatt.
    if ( $rupatt == $upatt )
    {
      $setupatt = str_replace( 1, 2, $upatt );
    }
    else
    {
      $setupatt = $rupatt .','. $upatt;
    }
  }
  else
  {
    $setupatt = $rupatt;
  }
}
else
{
  die(print "c Error");
}

$atts = array(
  'flair'    => 'Flair',
  'strength' => 'Power',
  'agility'  => 'Speed',
  'jutsu'    => 'Jutsu',
  'tactics'  => 'Tactics' );

// result !!
switch ( true )
{
  case ( $result < 6 ):  $placard = "Major Loss"; sql_bup( $setupatt = upatt( $c, $a, 'min', $b ), $_uid ); break;
  case ( $result < 7 ):  $placard = "Minor Loss"; sql_bup( $setupatt, $_uid); break;
  case ( $result < 9 ):  $placard = "Draw";       sql_bup(  $setupatt, $_uid);
                          sql_query ("UPDATE style_attributes SET taijutsu = taijutsu + 1 WHERE char_id = $_uid"); break;
  case ( $result < 10 ): $placard = "Minor Win";  sql_bup( $setupatt, $_uid ); break;
  case ( $result < 12 ): $placard = "Major Win";  sql_bup( $setupatt = upatt( $c, $a, 'max', $b ), $_uid ); break;
  
  default:
    echo "switch_result Error";
    break;
}

// End message and '+1' after the att in table.
$flair = $strength = $agility = $jutsu = $tactics = '';

// 1 or 2 diff atts.
if ( strlen($setupatt) == 9 )
{
  $att = substr( $setupatt, 0, 3 );
  $n = substr( $setupatt, -1 );
  $upgrade = $atts[$att] ." +". $n;
  $$att = "+". $n;
}
else if ( strlen($setupatt) == 19 )
{
  $att1 = substr( $setupatt, 0, 3 );
  $att2 = substr( $setupatt, 10, 3 );
  $upgrade = $atts[$att1] ." +1<br />". $atts[$att2] ." +1";
  $$att1 = "+1";
  $$att2 = "+1";
}

// switch for clan-train-skill, before being if/else and upa/rna.
/*
switch ( true )
{
  default: echo "switch_result Error"; break;
  
  case ( $result < 4 || $result >= 12 ):
    $placard = "The difference in skill is too wide<br />There's no point in training together";
    break;
  
  case ( $result < 6 || $result >= 10 ):
    $placard = "There's some gap";
    sql_query("UPDATE char_attributes SET $up_att = $up_att + 1 WHERE char_id = $_uid");
    $upgrade = $atts[$up_att] ." +1";
    $$up_att = "+1";
    break;
  
  case ( $result >= 6 && $result < 10):
    switch ( substr( microtime(true), -1 ) % 5 )
    {
      default: "switch_microtime Error"; break;
      
      case 0: $ranatt = "flair";    break;
      case 1: $ranatt = "strength"; break;
      case 2: $ranatt = "agility";  break;
      case 3: $ranatt = "jutsu";    break;
      case 4: $ranatt = "tactics";  break;
    }
  case ( $result < 7  || $result >= 9):
    $placard = "Good training";
    
    if ( $u_att / $p_att < $u_skill / $p_skill )
    {
      if (
        floor(
          ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + 2 )
          / 5 )
        > $u_char_level )
      {
        $uplv = 'char_level = char_level + 1, ';
        $u_char_level += 1;
        $char_level = 'Lv: '. $u_char_level .'<br />';
      }
      else $uplv = '';
      
      if ( $up_att == $ranatt )
      {
        sql_query("UPDATE char_attributes SET $uplv $up_att = $up_att + 2 WHERE char_id = $_uid");
        $upgrade = $atts[$up_att] ." +2";
        $$up_att = "+2";
      }
      else
      {
        sql_query("UPDATE char_attributes SET $uplv $up_att = $up_att + 1, $ranatt = $ranatt + 1 WHERE char_id = $_uid");
        $upgrade = $atts[$up_att] ." +1<br />". $atts[$ranatt] ." +1";
        $$up_att = "+1";
        $$ranatt = "+1";
      }
    }
    else
    {
      $$up_skill = 1;
      sql_query("UPDATE style_attributes SET $up_skill = $up_skill + 1 WHERE char_id = $_uid");
      
      if (
        floor(
          ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + 1 )
          / 5 )
        > $u_char_level )
      {
        $uplv = 'char_level = char_level + 1, ';
        $u_char_level += 1;
        $char_level = 'Lv: '. $u_char_level .'<br />';
      }
      else $uplv = '';
      
      sql_query("UPDATE char_attributes SET $uplv $ranatt = $ranatt+1 WHERE char_id = $_uid");
      $$ranatt = "+1";
      $upgrade = $skills[$up_skill] ." +1<br />". $atts[$ranatt] ." +1";
    }
    break;
  case ( $result < 9 || $result >= 7 ):
    $placard = "Evenly matched";
    $$up_skill = 1;
    sql_query("UPDATE style_attributes SET $up_skill = $up_skill + 1 WHERE char_id = $_uid");
    
    if (
      floor(
        ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + 2 )
        / 5 )
      > $u_char_level )
    {
      $uplv = 'char_level = char_level + 1, ';
      $u_char_level += 1;
      $char_level = 'Lv: '. $u_char_level .'<br />';
    }
    else $uplv = '';
    
    if ( $up_att == $ranatt )
    {
      sql_query("UPDATE char_attributes SET $uplv $up_att = $up_att + 2 WHERE char_id = $_uid");
      $upgrade = $skills[$up_skill] ." +1<br />". $atts[$up_att] ." +2";
      $$up_att = "+2";
    }
    else
    {
      sql_query("UPDATE char_attributes SET $uplv $up_att = $up_att + 1, $ranatt = $ranatt + 1 WHERE char_id = $_uid");
      $upgrade = $skills[$up_skill] ." +1<br />". $atts[$up_att] ." +1<br />". $atts[$ranatt] ." +1";
      $$up_att = "+1";
      $$ranatt = "+1";
    }
    
    break;
}
if (
  floor(
    ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + $nflv )
    / 5 )
  > $u_char_level )
{
  $uplv = 'sta = sta + 10, cha = cha + 10, char_level = char_level + 1, ';
  $u_char_level += 1;
  $char_level = 'Lv: '. $u_char_level .'<br />';
}
else $uplv = '';

sql_query("UPDATE style_attributes SET skill_points = skill_points - 5");
*/
/*
if ( $result < 4 || $result >= 12 )
{
  $placard = "The difference in use of skill is too wide<br />There's no point in training together";
}
else
{
  if ( $result < 6 || $result >= 10 )
  {
    $placard = "There's some gap";
    $upa = 1;
  }
  else
  {
    switch ( substr( microtime(true), -1 ) % 5 )
    {
      default: "switch_microtime Error"; break;
      
      case 0: $ranatt = "fla"; break;
      case 1: $ranatt = "pow"; break;
      case 2: $ranatt = "agi"; break;
      case 3: $ranatt = "jut"; break;
      case 4: $ranatt = "tac"; break;
    }
    
    if ( $result < 7 || $result >= 9 )
    {
      $placard = "Good training";
      
      if ( $u_char_level / $p_char_level < $u_skill / $p_skill )
      {
        if ( $up_att == $ranatt )
        {
          $upa = 2;
        }
        else
        {
          $upa = 1;
          $rna = 1;
        }
      }
      else
      {
        $skl = 1;
        $rna = 1;
      }
    }
    else
    {
      $placard = "Evenly matched";
      $skl = 1;
      if ( $up_att == $ranatt )
      {
        $upa = 2;
      }
      else
      {
        $upa = 1;
        $rna = 1;
      }
    }
  }
  
  if ( isset($upa) )
  {
    $$up_att = '+'. $upa;
    $upgrade_upa = $atts[$up_att] .' +'. $upa;
    $set_up_att = $up_att .'='. $up_att .'+'. $upa;
  }
  else
  {
    $upa = 0;
    $upgrade_upa = '';
    $set_up_att = '';
  }
  
  if ( isset($rna) )
  {
    $$ranatt = '+'. $rna;
    $upgrade_rna = $atts[$ranatt] .' +'. $rna;
    $set_ranatt = $ranatt .'='. $ranatt .'+'. $rna;
  }
  else
  {
    $rna = 0;
    $upgrade_rna = '';
    $set_ranatt = '';
  }
  
  if ( isset($skl) )
  {
    $$up_skill = $skl;
    $upgrade_skl = $skill .' +1<br />';
    $set_up_skl = $up_skill .'='. $up_skill .'+1,';
  }
  else
  {
    $upgrade_skl = '';
    $set_up_skl = '';
  }
  
  $upgrade = $upgrade_skl . $upgrade_upa . ( $upa == 1 && $rna == 1 ? '<br />' : '' ) . $upgrade_rna;
  
  if (
    floor(
      ( $u_flair + $u_strength + $u_agility + $u_jutsu + $u_tactics + $upa + $rna )
      / 5 )
    > $u_char_level )
  {
    $uplv = 'char_level = char_level + 1, ';
    $u_char_level += 1;
    $char_level = 'Lv: '. $u_char_level .'<br />';
  }
  else $uplv = '';
  
  sql_query("UPDATE char_attributes SET $uplv $set_up_att". ( $rna == 1 ? ', ' : '' ) ." $set_ranatt WHERE char_id = $_uid");
  sql_query("UPDATE style_attributes SET $set_up_skl skill_points = skill_points - 5 WHERE char_id = $_uid");
}*/

?>

<?php LAYOUT_wrap_onwards(); ?>

<h1>Battle</h1>

<table style="text-align: center;">
  
  <tr>
    <th><?= $u_username ?></th>
    <th>VS</th>
    <th><?= $p_username ?></th>
  </tr>
  
  <tr>
    <td><?= $u_style_name ?></td>
    <th></th>
    <td><?= $p_style_name ?></td>
  </tr>
  
  <tr><td></td><th></th><td></td></tr>
  
  <tr>
    <th><?= $u_kenjutsu .' • '. $u_shuriken .' • '. $u_taijutsu .' • '. $u_ninjutsu .' • '. $u_genjutsu ?></th>
    <th>K•S•T•N•G</th>
    <th><?= $p_kenjutsu .' • '. $p_shuriken .' • '. $p_taijutsu .' • '. $p_ninjutsu .' • '. $p_genjutsu ?></th>
  </tr>
  
  <tr>
    <td><?= $u_sta .' - '. $u_cha ?></td>
    <th>Stamina - Chakra</th>
    <td><?= $p_sta .' - '. $p_cha ?></td>
  </tr>
  
  <tr><td></td><th></th><td></td></tr>
  
  <tr>
    <td><?= $u_flair . $flair ?></td>
    <th>Flair</th>
    <td><?= $p_flair ?></td>
  </tr>
  
  <tr>
    <td><?= $u_strength . $strength ?></td>
    <th>Power</th>
    <td><?= $p_strength ?></td>
  </tr>
  
  <tr>
    <td><?= $u_agility . $agility ?></td>
    <th>Speed</th>
    <td><?= $p_agility ?></td>
  </tr>
  
  <tr>
    <td><?= $u_jutsu . $jutsu ?></td>
    <th>Jutsu</th>
    <td><?= $p_jutsu ?></td>
  </tr>
  
  <tr>
    <td><?= $u_tactics . $tactics ?></td>
    <th>Tactics</th>
    <td><?= $p_tactics ?></td>
  </tr>
  
</table>

<b>-- <?= $placard ?> --</b>

<br />Major Loss
<br />The difference is still too significant.

<br />Minor Loss
<br />You're getting closer.

<br />Draw
<br />Too close to tell who's stronger.

<br />Minor Win
<br />You're leaving this char behind.

<br />Major Win
<br />Is it worth it to keep training with this char?

<br />

<?= $upgrade ?>
