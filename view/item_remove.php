Welcome to item_remove! <a href="index.php">Go Back</a><br/><br/>  

 <?php 
    AlphabetLinks();
    $items = $db->GetTable('items', $sortby);
    StartTable();
    TH('Name <a href="index.php?action=item_remove&sortby=Name">^</a>');
    TH('St. 1<a href="index.php?action=item_remove&sortby=Isle01">^</a>');
    TH('St. 2<a href="index.php?action=item_remove&sortby=Isle02">^</a>');
    TH('&nbsp;X');
    echo    "</tr>" ; ?>
    <form method="post">
    <?php if($items) { foreach ($items as $titem) : ?>
        <?php $firstLetter = $titem['Name'][0]; ?>
        <?php echo "<tr><td id='$firstLetter'>" . $titem['Name'] . "</td>"; ?>
        <?php echo "<td>" . $titem['Isle01'] . "</td>"; ?>
        <?php echo "<td>" . $titem['Isle02'] . "</td><td>"; ?>
	<?php if($select_all) : ?>
            <input name="remove<?php echo $titem['Barcode']; ?>" type="checkbox" checked="checked"/>
	<?php else : ?>
            <input name="remove<?php echo $titem['Barcode']; ?>" type="checkbox"/>
	<?php endif; ?>
            <input name="<?php echo $titem['Barcode']; ?>" value="<?php echo $titem['Barcode']; ?>" type="hidden"/>
        </td>
    <?php endforeach; } ?>
    </tr>
    <?php NoDataRow($titem, 4) ?>
    <tr>
        <td colspan="4">
			<p align="right">
				<input type="hidden" name="action" value="item_remove"/>
				<input name="select_all" type="submit" value="Select All"/>
				<input name="select_none" type="submit" value="Select None"/>
				<input name="pending_removal" type="submit" value="Remove Selected"/>
			</p>
        </td>
    </tr>
    </form>
    
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>
