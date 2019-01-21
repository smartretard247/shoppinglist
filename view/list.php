<a href="index.php">Go Back</a>
<p>Welcome to list!</p>
         
 <?php 
    $items = $db->GetTableWithGrouping('items', 'Name', 'Name', 'Isle0' . $_SESSION['default_store'] . ' != 99');
    StartTable(); ?>
	
    <form method="post">
	<tr>
        <td colspan="2">
            <p align="right">
                    <input name="select_all" type="submit" value="Select All" style="font-size:20px"/>
                    <input name="select_none" type="submit" value="Select None" style="font-size:20px"/>
                    <input name="pending_add" type="submit" value="Add Selected" style="font-size:20px"/>
            </p>
        </td>
    </tr>
	
	<?php
	TH('Add');
    TH('Name');
    echo    "</tr>" ; ?>
    <?php if($items) { foreach ($items as $titem) : ?>
        <?php echo "<tr><td>"; ?>
            <?php if($select_all) : ?>
                    <input name="add<?php echo $titem['Barcode']; ?>" type="checkbox" checked="checked"/>
            <?php else : ?>
                    <input name="add<?php echo $titem['Barcode']; ?>" type="checkbox"/>
            <?php endif; ?>
            <input name="<?php echo $titem['Barcode']; ?>" value="<?php echo $titem['Barcode']; ?>" type="hidden"/>
        </td>
		<?php echo "<td>" . $titem['Name'] . "</td></tr>"; ?>
    <?php endforeach; } ?>
    </tr>
    <?php NoDataRow($titem, 2) ?>
    <tr>
        <td colspan="2">
            <p align="right">
                    <input type="hidden" name="action" value="SL_add"/>
                    <input name="select_all" type="submit" value="Select All" style="font-size:20px"/>
                    <input name="select_none" type="submit" value="Select None" style="font-size:20px"/>
                    <input name="pending_add" type="submit" value="Add Selected" style="font-size:20px"/>
            </p>
        </td>
    </tr>
    </form>
    
    <?php EndTable(); ?> 

<br/><a href="index.php">Go Back</a>