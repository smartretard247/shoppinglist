<p>Welcome to: item_add</p>
    
    <table>
	<form name="addshop" method="post">
	<tr>
	    <td>Name :</td><td><input type="input" name="Name" maxlength="50"/></td>
	</tr>
	<tr>
	    <td>Isle at <?php echo $store1name; ?> :</td><td><input type="input" name="Isle01" maxlength="2"/></td>
	</tr>
	<tr>
	    <td>Isle at <?php echo $store2name; ?> :</td><td><input type="input" name="Isle02" maxlength="2"/></td>
	</tr>
	<tr>
	    <td>Barcode :</td><td><input type="input" name="Barcode"/></td>
	</tr>
	<tr>
	    <td colspan="2"><input name="action" type="hidden" value="item_add"/>
			    <input name="pending_add" type="submit" value="Add"/></td>
	</tr>
    </table>
    </form>
<br/><a href="index.php">Go Back</a>