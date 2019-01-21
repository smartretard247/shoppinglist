Welcome to item_show_category! <a href="index.php">Go Back</a><br/><br/>

 <?php
    AlphabetLinks();
    $items = $db->GetTable('items', $sortby, $desc);
    StartTable();
    
    if($sortby == 'Name') {
      if(!$desc) {
        TH('Name <a href="index.php?action=item_show_category&sortby=Name&desc=1">v</a>');
      } else {
        TH('Name <a href="index.php?action=item_show_category&sortby=Name">^</a>');
      }
      TH('Category <a href="index.php?action=item_show_category&sortby=Category">^</a>');
    } else {
      TH('Name <a href="index.php?action=item_show_category&sortby=Name">^</a>');
      if(!$desc) {
        TH('Category <a href="index.php?action=item_show_category&sortby=Category&desc=1">v</a>');
      } else {
        TH('Category <a href="index.php?action=item_show_category&sortby=Category">^</a>');
      }
    }
    
    echo    "</tr>" ;
    
    if($items) { foreach ($items as $titem) : ?>
        <?php $firstLetter = $titem['Name'][0]; ?>
        <?php echo "<tr><td style='font-size: 15pt;' id='$firstLetter'>" . $titem['Name'] . "</td>"; ?>
        <td style="font-size: 15pt;">
          <?php $val = ($titem['Category']) ? $titem['Category'] : "None"; ?>
          
          <form method="post" style="display: inline;">
            <input type="hidden" name="action" value="item_edit_category"/>
            <input type="hidden" name="Barcode" value="<?php echo $titem['Barcode']; ?>"/>
            <input type="submit" value="<?php echo $val; ?>" style="font-size: 12pt;"/>
          </form>
          
        </td>
    <?php endforeach; NoDataRow($titem, 3); } EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
