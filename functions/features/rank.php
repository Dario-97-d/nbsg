<?php

// -- Rank functions --

function RANK_get_chars()
{
  return sql_all('
    SELECT
      u.char_id,
      u.username,
      u.char_rank,
      a.char_level,
      s.style_name
    FROM game_users       u
    JOIN char_attributes  a USING (char_id)
    JOIN style_attributes s USING (char_id)
    ORDER BY
      u.char_rank,
      a.char_level DESC
    LIMIT 25
  ');
}
