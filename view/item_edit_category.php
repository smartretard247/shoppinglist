<?php
  $barcode = filter_input(INPUT_POST, "Barcode");
  $titem = $db->GetByID('items', $barcode, 'Name', 'Barcode');
?>

Welcome to item_edit_category!<br/><br/>
<table>
  <form method="post">
    <input name="Barcode" type="hidden" value="<?php echo $titem['Barcode']; ?>"/>
    <tr><td><b>Name: </b></td><td><input type="input" value="<?php echo $titem['Name']; ?>" size="37" disabled=""/></td></tr>
  
  <tr>
    <td><b>Category: </b></td>
    <td>
      <select name="Category">
        <?php foreach($categories as $row) : ?>
        <option value="<?php echo $row['Name']; ?>" <?php echo ($titem['Category'] == $row['Name']) ? " selected" : ""; ?>><?php echo $row['Name']; ?></option>
        <?php endforeach; ?>
      </select>
      <input type="button" value="X" onclick="window.location='?rc=1&bc=<?php echo $titem['Barcode']; ?>'" title="Remove category"/>
      <input type="hidden" name="action" value="item_save_category"/>
      <input type="submit" value="Save"/>
    </td>
  </tr>
  </form>
</table> 

<br/><a href="index.php">Go Back</a>
