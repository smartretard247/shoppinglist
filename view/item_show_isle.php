Welcome to item_show_isle! <a href="index.php">Go Back</a><br/><br/>
         
 <?php 
  AlphabetLinks();
  $items = $db->GetTable('categories', "Name", $desc); ?>
  <table>
    <tr>
    
    <?php
      if(!$desc) {
        TH('Category <a href="index.php?action=item_show_isle&desc=1">v</a>');
      } else {
        TH('Category <a href="index.php?action=item_show_isle">^</a>');
      }
      TH('Isle01');
      TH('Isle02');
    ?>
    </tr>
    
    <?php if($items) {
      foreach ($items as $titem) : ?>
          <?php $firstLetter = $titem['Name'][0]; ?>
          <tr>
            <td style="font-size: 15pt;" id="<?php echo $firstLetter; ?>">
              <input type="button" value="X" onclick="window.location='?rc=1&id=<?php echo $titem['ID']; ?>'" title="Remove category"/>
              <?php echo $titem['Name']; ?>
            </td>
            <td style="font-size: 15pt;">
              <form method="post" style="display: inline;">
                <input type="text" name="Isle01" value="<?php echo $titem['Isle01']; ?>" size="3" style="font-size: 12pt;"/>
                <input type="hidden" name="action" value="item_edit_isle"/>
                <input type="hidden" name="Name" value="<?php echo $titem['Name']; ?>"/>
                <input type="submit" value="-->" style="font-size: 12pt;" title="Set all items of this category"/>
              </form>
            </td>
            <td style="font-size: 15pt;">
              <form method="post" style="display: inline;">
                <input type="text" name="Isle02" value="<?php echo $titem['Isle02']; ?>" size="3" style="font-size: 12pt;"/>
                <input type="hidden" name="action" value="item_edit_isle"/>
                <input type="hidden" name="Name" value="<?php echo $titem['Name']; ?>"/>
                <input type="submit" value="-->" style="font-size: 12pt;" title="Set all items of this category"/>
              </form>  
            </td>
          </tr>
        
      <?php endforeach;
    }
    NoDataRow($titem, 3);
    EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
