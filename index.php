<?php #$root = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'); //get root folder for relative paths
    $lifetime = 60 * 60 * 24; //24 hours
    ini_set('session.use_only_cookies', true);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);
    session_set_cookie_params($lifetime, '/'); //all paths, must be called before session_start()
    session_save_path(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT') . '/sessions'); session_start();
    date_default_timezone_set('America/New_York');
    
    #$_SESSION['rootDir'] = "/";
    $_SESSION['rootDir'] = "";
    include_once $_SESSION['rootDir'] . 'core/include.php';
    
    include_once 'view/header.php';
    
    //if($_SESSION['valid_user']) :
        
	if(empty($_SESSION['admin_enabled'])) $_SESSION['admin_enabled'] = false;
	if(empty($_SESSION['debug'])) $_SESSION['debug'] = false;
	if(empty($_SESSION['error_message'])) $_SESSION['error_message'] = '';
        if(empty($_SESSION['default_store'])) $_SESSION['default_store'] = '1';
        
        if(isset($_POST['change_store'])) { $_SESSION['default_store'] = $_POST['default_store']; }
	
	if(isset($_POST['guest_mode'])) {
		if($_POST['guest_mode'] == 1) $_SESSION['admin_enabled'] = false;
	}
	
	if(isset($_GET['sortby'])) {
      $sortby = $_GET['sortby'];
	} else { $sortby = 'Name'; }
    
    if(isset($_GET['desc'])) {
      $desc = $_GET['desc'];
	} else {
      $desc = false;
    }
	
	$debug = $_SESSION['debug'];
	$admin_enabled = $_SESSION['admin_enabled'];

    

    //display error message
    if($_SESSION['error_message'] != '') { echo '<br/><b id=error>' . $_SESSION['error_message'] . '</b><br/><br/>'; }

    if(isset($_POST['action'])) {
      $action = $_POST['action'];
    } else {
      if(isset($_GET['action'])) {
        $action = $_GET['action'];
      } else {
        if($_GET['rc']) {
          $action = 'remove_category';
        } else {
          $action = 'default';
        }
      } 
	}
    
    //perform necessary action, sent by forms
    switch($action) {
		case 'print':
			$printer = "\\\\JEEZY-PRN";
			if($ph = printer_open($printer))
			{
			   // Get file contents
			   $filename = "index.php";
			   $fh = fopen($filename, "rb");
			   $content = fread($fh, filesize($filename));
			   fclose($fh);
				   
			   // Set print mode to RAW and send PDF to printer
			   printer_set_option($ph, PRINTER_MODE, "RAW");
			   printer_write($ph, $content);
			   printer_close($ph);
			}
			else {
				echo "Couldn't connect to printer...";
			}
			
			include 'view/home.php';
			break;
        case 'change_store':
                if(isset($_POST['default_store'])) {
                    $_SESSION['default_store'] = $_POST['default_store'];
                }
            break;
    case 'category_add':
      if(isset($_POST['pending_add'])) {
        $name = filter_input(INPUT_POST, "Name");
        if(isset($name)) {
          $category->SetName($name);
          $success = $category->AddToDB();
          if($success) {
            echo "<b>Category '$name' added to database.</b><br/><br/>";
          } else {
            echo "<b>Failed to add category to database.</b><br/><br/>";
          }
        } else {
          echo "<b>Name entered was invalid.</b><br/><br/>";
        }

        include 'view/category_add.php';
      } else {
        include 'view/category_add.php';
      }
      break;
	case 'item_add': 
	    if(isset($_POST['pending_add'])) {
          //code for adding
          $barcode = filter_input(INPUT_POST, "Barcode");
          if($barcode == '') {
            $barcode = Item::GetRandomBarcode();
          }

          $item->SetBarcode($barcode);
          
          if($item->IsValidBarcode()) {
            $item->SetName($_POST['Name']);
            $item->SetIsle01($_POST['Isle01']);
            $item->SetIsle02($_POST['Isle02']);

            echo "<b>" . $item->AddToDB() . " Item(s) added.</b><br/>";
          } else {
            echo "<b>Barcode entered was invalid.</b><br/>";
          }

          include 'view/home.php';
	    } else {
          include 'view/item_add.php';
        }
        break;
	case 'item_remove':
		if(isset($_POST['select_all'])) { $select_all = true; }
		if(isset($_POST['select_none'])) { $select_all = false; }
		
	    if(isset($_POST['pending_removal'])) {
			$item_amount = 0;

			$removenext = false;
			foreach($_POST as $tpost) {
				if($removenext) {
					$items = $db->GetTable('items', 'Name');
					foreach($items as $titems) {
						if($tpost == $titems['Barcode']) {
							$item->SetFromDB($titems['Barcode']);
							
							$item_amount += $item->RemoveFromDB();
						}
					}
				}


				if($tpost == 'on') { $removenext = true; } else { $removenext = false; }
			}

			echo "<b>" . $item_amount . " Item(s) removed.</b><br/>";

			include 'view/home.php';
	    } else { 
		include 'view/item_remove.php'; 
	    } break;
    case 'item_isle_change':
      if(isset($_POST['Barcode'])) {
        $item->SetFromDB($_POST['Barcode']);
        $item->SetIsle01($_POST['Isle01']);
        $item->SetIsle02($_POST['Isle02']);
        
        echo "<b>" . $item->UpdateDB() . " Item(s) edited successfully.</b><br/><br/>";
        
        include 'view/item_show.php';
      } else {
        echo "<b>Error retreiving Item.</b><br/>";
      }
      break;
    case 'remove_category':
      $barcode = filter_input(INPUT_GET, "bc");
      if(isset($barcode)) {
        $item->SetBarcode($barcode);
        echo "<b>Category removed from " . $item->RemoveCategory() . " item(s).</b><br/><br/>";
        include 'view/home.php';
      } else {
        $id = filter_input(INPUT_GET, "id");
        if(isset($id)) {
          $category->SetID($id);
          echo "<b>" . $category->RemoveFromDB() . " Category removed from database.</b><br/><br/>";
          include 'view/item_show_isle.php';
        } else {
          include 'view/home.php';
        }
      }
      break;
	case 'item_edit':
	    if(isset($_POST['Barcode'])) {
          $item->SetFromDB($_POST['Barcode']);

          if(isset($_POST['pending_update'])) {
            //update item
            $item->SetName(strtoupper($_POST['Name']));
            $item->SetIsle01($_POST['Isle01']);
            $item->SetIsle02($_POST['Isle02']);
            $item->SetCategory($_POST['Category']);
            
            if($_POST['BuyInBulk'] == 1) {
              $item->SetBuyInBulk($_POST['BuyInBulk']);
            } else {
              $item->SetBuyInBulk(0);
            }

            //$item->ChangeBarcode_In_DB($_POST['new_Barcode']);
            $newBarcode = filter_input(INPUT_POST, "show_Barcode");
            if($newBarcode) {
                echo "<b>" . $item->UpdateDB($newBarcode) . " Item(s) edited successfully. CODE 6 ($newBarcode)</b><br/>";
            } else {
                echo "<b>" . $item->UpdateDB() . " Item(s) edited successfully. CODE 5</b><br/>";
            }

            include 'view/item_show_barcodes.php';
          } else { //goto item_edit
            include 'view/item_edit.php';
          }
	    } else {
          echo "<b>Error retreiving Item.</b><br/>";
        }
	    break;
	case 'item_show':
	    //code to view the items roster
	    include 'view/item_show.php';
	    break;
	case 'item_show_barcodes':
	    //code to view the items roster
	    include 'view/item_show_barcodes.php';
	    break;
    case 'item_edit_category':
	    //code to view the items roster
	    include 'view/item_edit_category.php';
	    break;
    case 'item_save_category':
        $barcode = filter_input(INPUT_POST, "Barcode");
        if(isset($barcode)) {
          $category = filter_input(INPUT_POST, "Category");
          $item->SetBarcode($barcode);
          $item->SetCategory($category);
          echo "<b>" . $item->UpdateCategory() . " category updated.</b><br/><br/>";
        }
      
	    include 'view/item_show_category.php';
	    break;
    case 'item_show_category':
	    include 'view/item_show_category.php';
	    break;
    case 'item_edit_isle':
        $name = filter_input(INPUT_POST, "Name");
        $category->SetName($name);
      
        $isle = filter_input(INPUT_POST, "Isle01");
        if(isset($isle)) {
          $category->SetIsle01($isle);
          $category->UpdateCategoryIsle(1);
          $affected = $category->UpdateAllInCategory(1);
        } else {
          $isle = filter_input(INPUT_POST, "Isle02");
          if(isset($isle)) {
            $category->SetIsle02($isle);
            $category->UpdateCategoryIsle(2);
            $affected = $category->UpdateAllInCategory(2);
          }
        }
        
        echo "<b>" . $affected . " Items(s) changed categories (CODE 4).</b><br/><br/>";
      
	    include 'view/item_show_isle.php';
	    break;
    case 'item_show_isle':
	    include 'view/item_show_isle.php';
	    break;
	case 'default': 
	    //do default action, load home page
	    include 'view/home.php';
	    break;
        case 'force_add':
            $randomCode = Item::GetRandomBarcode();

            $item->SetBarcode($randomCode);
            $item->SetName($_POST['Barcode']);
            $item->SetIsle01(0);
            $item->SetIsle02(0);
            $successful = $item->AddToDB();
            echo "<b>" . $successful . " Item(s) added to database.</b><br/>";
            if(!$successful) { include 'view/home.php'; break; }
        case 'SL_add': 
            if(isset($_POST['select_all'])) { $select_all = true; }
            if(isset($_POST['select_none'])) { $select_all = false; }
            
            if(isset($_POST['pending_add'])) {
                $item_amount = 0;

                $addnext = false;
                foreach($_POST as $tpost) {
                  if($addnext) {
                    $items = $db->GetTable('items', 'Name');
                    foreach($items as $titems) {
                      if($tpost == $titems['Barcode']) {
                        $SL->SetBarcode($titems['Barcode']);
                        $item_amount += $SL->AddToDB();
                      }
                    }
                  }

                  if($tpost == 'on') { $addnext = true; } else { $addnext = false; }
                }

                echo "<b>" . $item_amount . " Item(s) added to shopping list (CODE 1).</b><br/><br/>";

                include 'view/home.php';
	    } else if(!isset($_POST['Barcode'])) { 
          include 'view/list.php';
          
        }
            //code for adding a single item to the shopping list
            $postBarcode = filter_input(INPUT_POST, "Barcode");
            if(isset($postBarcode)) {
              $_SESSION['TempBarcode'] = $postBarcode;
              
              if($postBarcode == '') {
                include 'view/list.php';
              } else {
                $SL->SetBarcode($postBarcode);

                if($SL->IsValidBarcode()) {
                  $SL->SetFromDB($SL->GetBarcode());
                  echo "<b>" . $SL->AddToDB() . " Item(s) added (CODE 2).</b><br/><br/>";
                } else { //barcode not in database
                  $barcode = $SL->IsValidName($postBarcode); //in this case Barcode is an item name, if found returns Barcode of item
                  if($barcode) {
                    $SL->SetFromDB($barcode);
                    echo "<b>" . $SL->AddToDB() . " Item(s) added to shopping list (CODE 3).</b><br/><br/>";
                  } else {
                    $names = $SL->NameIsLike($postBarcode); //in this case Barcode is an item name
                    if($names) {
                      $count = 0;
                      echo "<table><tr><td colspan='3' style='color: red;'><b>Did you mean?</b></td></tr>";
                      foreach($names as $row) {
                        if($count % 3 == 0) { echo "<tr><td>"; } else { echo "<td>"; }
                        $name = $row['Name'];
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='action' value='SL_add'/>";
                        echo "<input type='hidden' name='Barcode' value='$name'/>";
                        echo "<input type='submit' value='$name'/>";
                        echo "</form>";
                        if($count % 3 == 0) { echo "</td>"; } else { "</td></tr>"; }
                        ++$count;
                      }

                      if($count % 3 == 0) {
                        echo "</td></tr></table><br/>";
                      } else {
                        echo "</table><br/>";
                      }
                    } else {
                      $name = $_SESSION['TempBarcode'];
                      echo "<table width='580px'><tr><td colspan='2' style='color: red;'><b>Invalid Barcode/Name</b></td></tr><tr><td>Save '$name' to database and add to shopping list?</td><td>";
                      echo "<form method='post'>";
                      echo "<input type='hidden' name='action' value='force_add'/>";
                      echo "<input type='hidden' name='Barcode' value='$name'/>";
                      echo "<input type='submit' value='Yes'/>";
                      echo "</form></td></tr></table><br/>";
                    }
                  }
                }

                include 'view/home.php';
              }
            } 
	    
            break;
        case 'SL_remove':
            if(isset($_POST['Barcode'])) {
		$SL->SetBarcode($_POST['Barcode']);					
						
		echo "<b>" . $SL->RemoveFromDB() . " Item(s) removed.</b><br/><br/>";
            }
            
            include 'view/home.php';
	    break;
        case 'SL_remove_all':
            echo "<b>" . $SL->RemoveAllFromDB() . " Item(s) removed.</b><br/><br/>";
            
            include 'view/home.php';
	    break;
        case 'SL_remove_supermarket':
            echo "<b>" . $SL->RemoveSupermarketFromDB() . " Item(s) removed.</b><br/><br/>";
            
            include 'view/home.php';
	    break;
		case 'SL_remove_sams':
            echo "<b>" . $SL->RemoveSamsFromDB() . " Item(s) removed.</b><br/><br/>";
            
            include 'view/home.php';
	    break;
        case 'Clear':
            include 'view/sure.php';
	    break;
		case 'Clear2':
            include 'view/sure2.php';
	    break;
    } //end of switch statement
    
    include 'view/footer.php'; 
?>
