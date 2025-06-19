<?php

// -- View functions --

function VIEW_Char_skills( $char_skills )
{
  ?>
  <table class="table-char-skills">
    <tr>
      <th>kenjutsu</th>
      <th>shuriken</th>
      <th>taijutsu</th>
      <th>ninjutsu</th>
      <th>genjutsu</th>
    </tr>
    
    <tr>
      <td><?= $char_skills['kenjutsu'] ?></td>
      <td><?= $char_skills['shuriken'] ?></td>
      <td><?= $char_skills['taijutsu'] ?></td>
      <td><?= $char_skills['ninjutsu'] ?></td>
      <td><?= $char_skills['genjutsu'] ?></td>
    </tr>
  </table>
  <?php
}

function VIEW_Mail_navbar()
{
  ?>
  <h2>
    <a href="mail-received">Mailbox</a> || <a href="mail-write">Write</a> || <a href="mail-sent">Mail sent</a>
  </h2>
  <?php
}

function VIEW_Skills_inline( $skills )
{
  echo
  (
    $skills['kenjutsu']
    .' • '.
    $skills['shuriken']
    .' • '.
    $skills['taijutsu']
    .' • '.
    $skills['ninjutsu']
    .' • '.
    $skills['genjutsu']
  );
}
