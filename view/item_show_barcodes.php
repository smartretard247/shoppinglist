Welcome to item_show_barcodes! <a href="index.php">Go Back</a><br/><br/>
         
 <?php 
    AlphabetLinks();
    $items = $db->GetTable('items', $sortby, $desc);
    StartTable();
    
    if($sortby == 'Name') {
      if(!$desc) {
        TH('Name <a href="index.php?action=item_show_barcodes&sortby=Name&desc=1">v</a>');
      } else {
        TH('Name <a href="index.php?action=item_show_barcodes&sortby=Name">^</a>');
      }
      TH('Barcode <a href="index.php?action=item_show_barcodes&sortby=Barcode">^</a>');
    } else {
      TH('Name <a href="index.php?action=item_show_barcodes&sortby=Name">^</a>');
      if(!$desc) {
        TH('Barcode <a href="index.php?action=item_show_barcodes&sortby=Barcode&desc=1">v</a>');
      } else {
        TH('Barcode <a href="index.php?action=item_show_barcodes&sortby=Barcode">^</a>');
      }
    }
    
    echo    "</tr>" ;
    
    if($items) { foreach ($items as $titem) : ?>
        <?php $firstLetter = $titem['Name'][0]; ?>
        <?php echo "<tr><td id='$firstLetter'>" . $titem['Name'] . "</td>"; ?>
        <td><form method="post" style="display: table-cell;">
            <input type="hidden" name="action" value="item_edit"/>
            <input type="hidden" name="Barcode" value="<?php echo $titem['Barcode']; ?>"/>
            <input type="submit" value="<?php echo $titem['Barcode']; ?>" style="font-size: 16pt;"/>
        </form></td>
    <?php endforeach; NoDataRow($titem, 3); } EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
