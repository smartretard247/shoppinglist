<table><?php if($admin_enabled) :
  if($_SESSION['valid_user']) : ?>
    <tr>
      <td>Add an item:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_add"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
      <td>Remove an item:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_remove"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
      <td>Add a category:</td>
      <td><form method="post"><input type="hidden" name="action" value="category_add"/><input value="Go" type="submit"/></form></td>
    </tr>
    <tr>
      <td>View items by isle:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_show"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
      <td>View items by barcode:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_show_barcodes"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
      <td>View items by category:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_show_category"/><input value="Go" type="submit"/></form></td> 
    </tr>
    <tr>
      <td>View categories by isle:</td>
      <td><form method="post"><input type="hidden" name="action" value="item_show_isle"/><input value="Go" type="submit"/></form></td>
    </tr>
  <?php else : ?>
    <b id="error">&nbsp;&nbsp;You do not have permission to view this site.</b><br/>
  <?php include 'view/rightbar.php'; endif; ?>
<?php else : ?>
  <tr>
    <td style="font-size:26px">Add to Shopping List:</td>
    <td>
      <form name="addshop" method="post" style="display: table-cell;">
        <input size="32" type="text" name="Barcode"/>
        <input type="hidden" name="action" value="SL_add"/>
        <input value="Add" type="submit" style="font-size:16px"/>
      </form>
    </td> 
  </tr>
</table><br/>

<?php 
  $ShoppingList1 = $db->GetTableByQuery('SELECT *, (SELECT COUNT(*) FROM current_list GROUP BY Barcode HAVING Barcode = items.Barcode) AS Quantity FROM items WHERE Barcode IN (SELECT Barcode FROM current_list WHERE items.BuyInBulk = 0) ORDER BY Isle01, Name');
  $ShoppingList2 = $db->GetTableByQuery('SELECT *, (SELECT COUNT(*) FROM current_list GROUP BY Barcode HAVING Barcode = items.Barcode) AS Quantity FROM items WHERE Barcode IN (SELECT Barcode FROM current_list WHERE items.BuyInBulk = 1) ORDER BY Isle02, Name');
?>
      
<table>
  <tr>
    <th width="380px"><?php echo $store1name; ?></th>
    <th width="25px">Isle</th>	
    <th>
      <form method="post" style="display: table-cell;">
        <input name="action" type="submit" value="Clear" style="font-size:16px"/>
      </form>
    </th>
  </tr>

  <?php if($ShoppingList1) {
    foreach ($ShoppingList1 as $tShoppingList) :
      $SL->SetBarcode($tShoppingList['Barcode']);
      if($tShoppingList['BuyInBulk'] == 0) : 
        echo "<tr><td style='font-size:22px'>"; 
        
        $name = "";
        if($tShoppingList['Quantity'] > 1) {
          $name .= $tShoppingList['Quantity'] . "x ";
        } 
        
        $name .= $tShoppingList['Name']; ?>
      
        <form method="post" style="display: table-cell;">
          <input type="hidden" name="action" value="item_edit"/>
          <input type="hidden" name="Barcode" value="<?php echo $tShoppingList['Barcode']; ?>"/>
          <input type="submit" value="<?php echo $name; ?>" style="font-size: 16pt; background-color: white; border: 0px;"/>
        </form>
      
        <?php 
        echo "</td>";
        echo "<td style='font-size:22px'>" . $tShoppingList['Isle01'] . "</td>";
        ?>
        <td>
          <form method="post" style="display: table-cell;">
            <input type="hidden" name="action" value="SL_remove"/>
            <input type="hidden" name="Barcode" value="<?php echo $tShoppingList['Barcode']; ?>"/>
            <input type="submit" value="X" style="font-size:15px;"/>
          </form>
        </td>
      <?php endif; ?>
    <?php endforeach;
    NoDataRow($tShoppingList, 3);
  }
?>
</table><br/>

<table>
  <tr>
    <th width="380px"><?php echo $store2name; ?></th>
    <th width="25px">Isle</th>
    <th>
      <form method="post" style="display: table-cell;">
        <input name="action" type="hidden" value="Clear2"/>
        <input type="submit" value="Clear" style="font-size:16px"/>
      </form>
    </th>
  </tr>
  <?php if($ShoppingList2) {
    foreach ($ShoppingList2 as $tShoppingList2) :
      $SL->SetBarcode($tShoppingList2['Barcode']);
      if($tShoppingList2['BuyInBulk'] == 1) {
        echo "<tr><td style='font-size:22px'>";
        if($tShoppingList2['Quantity'] > 1) {
          echo $tShoppingList2['Quantity'] . "x ";
        }
        echo $tShoppingList2['Name'];
        echo "</td>";
        echo "<td style='font-size:22px'>" . $tShoppingList2['Isle02'] . "</td>"; ?>
        <td>
          <form method="post" style="display: table-cell;">
            <input type="hidden" name="action" value="SL_remove"/>
            <input type="hidden" name="Barcode" value="<?php echo $tShoppingList2['Barcode']; ?>"/>
            <input type="submit" value="X" style="font-size:15px;"/>
          </form>
        </td><?php
      }
    endforeach;
    NoDataRow($tShoppingList2, 3);
  } 
  endif; ?>
</table>