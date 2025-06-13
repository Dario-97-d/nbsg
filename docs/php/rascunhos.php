<!--

<table class="table-skill" align="center">
  
  <tr>
    <th>Kenjutsu</th>
    <th>Shuriken</th>
    <th>Taijutsu</th>
    <th>Ninjutsu</th>
    <th>Genjutsu</th>
  </tr>
  
  <tr>
    <td><?= $kenjutsu ?></td>
    <td><?= $shuriken ?></td>
    <td><?= $taijutsu ?></td>
    <td><?= $ninjutsu ?></td>
    <td><?= $genjutsu ?></td>
  </tr>
  
  <tr>
    <td>Progress</td>
    <td>Progress</td>
    <td>Progress</td>
    <td>Progress</td>
    <td>Progress</td>
  </tr>
  
  <tr>
    
    <td>
      <div class="barprogt">
        <div class="bartrain" style="width: <?= $kenjutsu_points * 100 / $kenjutsu ?>px;"></div>
      </div>
      
      <?= $kenjutsu_points .'/'. $kenjutsu ?>
    </td>
    
    <td>
      <div class="barprogt">
        <div class="bartrain" style="width: <?= $shuriken_points * 100 / $shuriken ?>px;"></div>
      </div>
      
      <?= $shuriken_points .'/'. $shuriken ?>
    </td>
    
    <td>
      <div class="barprogt">
        <div class="bartrain" style="width: <?= $taijutsu_points * 100 / $taijutsu ?>px;"></div>
      </div>
      
      <?= $taijutsu_points .'/'. $taijutsu ?>
    </td>
    
    <td>
      <div class="barprogt">
        <div class="bartrain" style="width: <?= $ninjutsu_points * 100 / $ninjutsu ?>px;"></div>
      </div>
      
      <?= $ninjutsu_points .'/'. $ninjutsu ?>
    </td>
    
    <td>
      <div class="barprogt">
        <div class="bartrain" style="width: <?= $genjutsu_points * 100 / $genjutsu ?>px;"></div>
      </div>
      
      <?= $genjutsu_points .'/'. $genjutsu ?>
    </td>
    
  </tr>
  
  <tr>
    
    <td>
      <select name="skill">
        <?php
        
        for ( $i = 1; $i <= $kenjutsu; $i++ )
        {
          ?>
          <option><?= $i ?></option>
          <?php
        }
        
        ?>
      </select>
    </td>
    
    <td>
      <select name="skill">
        <option>1</option>
      </select>
    </td>
    
    <td>
      <select name="skill">
        <option>1</option>
      </select>
    </td>
    
    <td>
      <select name="skill">
        <option>1</option>
      </select>
    </td>
    
    <td>
      <select name="skill">
        <option>1</option>
      </select>
    </td>
    
  </tr>
  
</table>

<table id="table-train" align="center">
  <tr>
    <td>
      <button type="submit">Train</button>
    </td>
    
    <td>
      <button type="submit">Train</button>
    </td>
    
    <td>
      <button type="submit">Train</button>
    </td>
    
    <td>
      <button type="submit">Train</button>
    </td>
    
    <td>
      <button type="submit">Train</button>
    </td>
  </tr>
</table>

-->