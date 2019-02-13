<?php if($_SESSION['valid_user']) : ?>
<?php $titem = $db->GetByID('items', $item->GetBarcode(), 'Name', 'Barcode'); ?>

Welcome to item_edit! <a href="index.php">Go Back</a><br/><br/>

<div style="position: relative; left: 480px; top: -22px;">
  <form method="post">
    <input name="on" type="hidden" value="on"/>
    <input name="Barcode" type="hidden" value="<?php echo $titem['Barcode']; ?>"/>
    <input type="hidden" name="action" value="item_remove"/>
    <input type="hidden" name="pending_removal" value="1"/>
    <input type="submit" value="Delete This Item"/>
  </form>
</div>

<table>
  <form method="post" action="?sortby=Barcode&desc=1">
  <tr>
    <input name="Barcode" type="hidden" value="<?php echo $titem['Barcode']; ?>"/>
    <td><b>Barcode: </b></td>
    <td>
      <?php if(substr($titem['Barcode'], 0, 5)  == "99999") : ?>
        <input name="show_Barcode" type="input" value="<?php echo $titem['Barcode']; ?>"/>
      <?php else : ?>
        <input name="show_Barcode" type="input" value="<?php echo $titem['Barcode']; ?>" disabled/>
      <?php endif; ?>
    </td>
  </tr>

  <tr><td><b>Name: </b></td><td><input name="Name" type="input" value="<?php echo $titem['Name']; ?>" maxlength="50"/></td></tr>
  <tr>
    <td><b>Category: </b></td>
    <td style="font-size: 12pt; text-align: center; vertical-align: middle;">
        <select name="Category" style="width: 110px;">
          <?php if(!$titem['Category']): ?>
            <option value="0" selected>None</option>
          <?php endif; ?>
          <?php foreach($categories as $row) : ?>
            <option value="<?php echo $row['Name']; ?>" <?php echo ($titem['Category'] == $row['Name']) ? " selected" : ""; ?>><?php echo $row['Name']; ?></option>
          <?php endforeach; ?>
        </select>
      <input type="button" value="X" onclick="window.location='?rc=1&bc=<?php echo $titem['Barcode']; ?>'" title="Remove category"/>
      
    </td>
  </tr>
  <tr><td><b>Supermarket Isle: </b></td><td><input name="Isle01" type="input" value="<?php echo $titem['Isle01']; ?>" maxlength="2"/></td></tr>
  
  <tr>
    <td><b>Bulk Store Isle/Buy Bulk?: </b></td>
    <td>
      <input size="16" name="Isle02" type="input" value="<?php echo $titem['Isle02']; ?>" maxlength="2"/>
      <input value="1" type="checkbox" name="BuyInBulk" <?php if($titem['BuyInBulk']) { echo " checked"; } ?> title="Buy in bulk?"/>
    </td>
  </tr>

  <tr>
    <td></td>
    <td>
      <input type="hidden" name="action" value="item_edit"/>
      <input type="hidden" name="pending_update" value="1"/>
      <input type="submit" value="Save"/>
    </td>
  </tr>
  </form>
</table> 

<?php else : ?>
  <b id="error">&nbsp;&nbsp;You do not have permission to view this site.</b><br/>
<?php include 'view/rightbar.php'; endif; ?>

<br/><a href="index.php">Go Back</a>
