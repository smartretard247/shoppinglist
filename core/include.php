<?php
    include_once 'display.php';
    include_once '../database.php'; $db = new Database('shoppinglist');
    include_once 'items.php';  
    include_once 'SL.php';
    include_once 'categories.php';
?>

<script>    
  if(typeof window.history.pushState === 'function') {
    window.history.pushState({}, "Hide", "index.php");
  }
</script>
