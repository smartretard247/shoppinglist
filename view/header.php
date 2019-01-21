<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <!-- the head section -->
    <head>
        <title>Shopping List</title>
        <link rel="stylesheet" type="text/css" href="/shoppinglist/format.css" />
    </head>

    <!-- the body section -->
	<?php if($_POST['action'] == "item_add") {
      $toload = "document.addshop.Name.focus();";
    } if($_POST['action'] == "category_add") {
      $toload = "document.addcat.Name.focus();";
    } else {
      $toload = "document.addshop.Barcode.focus();";
    } ?>
    <body OnLoad="<?php echo $toload; ?>">
    <div id="page">
        <div id="header">
			<?php
              $store1name='Supermarket';
              $store2name="Bulk Store";
              
              $categories = $db->GetTableWhere('categories', 'ID > 0', 'Name');
              
              /*$categories = array('Air Fresheners', 'Analgesics', 'Asian Food',
                  'Baby Formula', 'Baby Needs/Care', 'Bags/Wraps/Foils',
                  'Baking Needs', 'Batteries/Flashlights', 'Beverages',
                  'Bleach/Starch', 'Bottled Water', 'Butter/Margarine', 'Candy',
                  'Canned Fruit', 'Canned Vegetables', 'Cat Food/Treats',
                  'Charcoal/Firelogs', 'Cheese', 'Coffee/Tea', 'Cold Cereal',
                  'Cookies/Crackers', 'Cups/Plates', 'Dairy', 'Dish Detergents',
                  'Dog Food/Treats', 'Dry Pasta', 'Feminine Hygiene',
                  'Fresh Fruits/Vegetables', 'Frozen Dinners/Entrees',
                  'Frozen Fries/Potatoes', 'Frozen Fruit', 'Frozen International',
                  'Frozen Meat', 'Frozen Pizza', 'Frozen Poultry',
                  'Hot Chocolate/Milk Modifiers', 'Household Cleaners',
                  'Ice Cream', 'Jam/Jelly/Honey', 'Juice', 'Laundry Detergents',
                  'Meats', 'Microwave Meals', 'Pancake Mix/Syrup', 'Paper Towels',
                  'Pasta Sauce', 'Peanut Butter', 'Pickles/Peppers/Relish',
                  'Popcorn', 'Potato Chips', 'Powdered Beverage',
                  'Puddings/Gelatins', 'Rice', 'Salad Dressing/Croutons',
                  'Seafood', 'Shampoo/Hair Care', 'Shaving Needs',
                  'Soft Drinks/Soda', 'Soup Can/Dry', 'Spices/Sugar',
                  'Toaster Pastries', 'Toilet Tissue', 'Vitamins', 'Yogurt');*/
            ?>
          
            <table style="border-style:none;"><tr><td width="500px" style="border-style:none;">
			<h1>Shopping List</h1></td><td style="border-style:none;">
				<?php session_save_path('../sessions');
                                    ini_set('session.gc_probability', 1);
        
                                    if(!$_SESSION['admin_enabled']) : ?>
					<form method="post" action="core/admin_enable.php">
						<input type="hidden" name="admin_pass" value=""/>
                        <input type="submit" value="Goto Admin Mode" style="font-size:16px"/>
					</form>		
				<?php else : ?>
					<form method="post" action="core/admin_enable.php">
						<input type="submit" value="Goto Scan Mode" name="guest_mode" style="font-size:16px"/>
					</form>
				<?php endif; ?>
						<!--<input type="button" onClick="window.print()" value="      Print       " style="font-size:16px"/>!-->
				</td></tr></table>
        </div>
        <div id="main"><br/>