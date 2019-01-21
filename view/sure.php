<p>Are you sure you want to clear your <?php echo $store1name; ?> shopping list? </p>
    
    <form method="post">
      <input name="action" type="hidden" value="SL_remove_supermarket"/>
      <input type="submit" value="Yes"/>
    </form>
	<form method="post">
      <input type="submit" value="Cancel"/>
    </form>
    <form method="post">
      <input name="action" type="hidden" value="SL_remove_all"/>
      <input type="submit" value="Clear ALL"/>
    </form>
<br/><a href="index.php">Go Back</a>