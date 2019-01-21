Welcome to item_show! <a href="index.php">Go Back</a><br/><br/>
         
 <?php 
    AlphabetLinks();
    $items = $db->GetTable('items', $sortby, $desc);
    StartTable();
    
    if(!$desc) {
      TH('Name <a href="index.php?action=item_show&sortby=Name&desc=1">v</a>');
    } else {
      TH('Name <a href="index.php?action=item_show&sortby=Name">^</a>');
    }
    
    TH('St.1<a href="index.php?action=item_show&sortby=Isle01">^</a>');
	TH('St.2<a href="index.php?action=item_show&sortby=Isle02">^</a>');
    echo    "</tr>" ;
    
    if($items) { foreach ($items as $titem) : ?>
      <?php $firstLetter = $titem['Name'][0]; ?>
        <tr>
          <td id='<?php echo $firstLetter; ?>'>
            <form method="post" style="display: table-cell;">
              <input type="hidden" name="action" value="item_edit"/>
              <input type="hidden" name="Barcode" value="<?php echo $titem['Barcode']; ?>"/>
              <input type="submit" value="<?php echo $titem['Name']; ?>" style="font-size: 16pt;"/>
            </form>
          </td>

        <form method="post" id="<?php echo $titem['Name']; ?>">
            <input type="hidden" name="action" value="item_isle_change"/>
            <input type="hidden" name="Barcode" value="<?php echo $titem['Barcode']; ?>"/>
            <td>
              <input onkeydown="if (event.keyCode === 13) { submit(); }" style='font-size: 16pt;' type='text' size='3' value='<?php echo $titem['Isle01']; ?>' name='Isle01'/>
            </td>

            <td>
              <input onkeydown="if (event.keyCode === 13) { submit(); }" style='font-size: 16pt;' type='text' size='3' value='<?php echo $titem['Isle02']; ?>' name='Isle02'/>
            </td>
          </form>
        </tr>
    <?php endforeach; NoDataRow($titem, 3); } EndTable(); ?> 

    <br/><a href="index.php">Go Back</a>
