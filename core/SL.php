<?php 
    
class SL { 
    private $barcode;
    
    private static $SLCount = 0;
    public static function GetSLCount() { return self::$SLCount; }

    public function __construct($barcode = '') {
        $this->barcode = $barcode;
        self::$SLCount++;
    }
    
    public function __destruct() {
        self::$SLCount--;
    }
    
    public function AddToDB() {
      require_once '../database.php';
      global $db;
      
      $query = "INSERT INTO current_list (Barcode) VALUES ('";
      $query .= $this->barcode . "')";

      return $db->exec($query);
    }
    
    public function RemoveFromDB() {
        require_once '../database.php';
        global $db;
        
        $query = "DELETE FROM current_list WHERE Barcode = '" . $this->barcode . "'";
        return $db->exec($query);
    }
    
    public function RemoveAllFromDB() {
        require_once '../database.php';
        global $db;
        
        $query = "DELETE FROM current_list WHERE Barcode > 0";
        return $db->exec($query);
    }
    
    public function RemoveSupermarketFromDB() {
        require_once '../database.php';
        global $db;
        
        $query = "DELETE FROM current_list WHERE Barcode IN (SELECT Barcode FROM items WHERE BuyInBulk = 0)";
        return $db->exec($query);
    }
	
	public function RemoveSamsFromDB() {
        require_once '../database.php';
        global $db;
        
        $query = "DELETE FROM current_list WHERE Barcode IN (SELECT Barcode FROM items WHERE BuyInBulk = 1)";
        return $db->exec($query);
    }
    
    public function SetFromDB($barcode, $isName = false) {
      require_once '../database.php';
      global $db;

      if($isName) {
        $query = "SELECT Barcode FROM items WHERE `Name` = :0";
      } else {
        $query = "SELECT Barcode FROM items WHERE Barcode = :0";
      }
      $SL = $db->SafeFetch($query, array($barcode));
      $this->SetBarcode($SL['Barcode']);

      return $this;
    }
    
    public function GetName() { 
      require_once '../database.php';
      global $db;

      $query = "SELECT Name FROM items WHERE Barcode = '" . $this->barcode . "'";
      $res = $db->Query($query);
      $ShoppingList = $res->fetch();

      return $ShoppingList['Name']; 
    }
    
    public function GetIsle01() {
      require_once '../database.php';
      global $db;

      $query = "SELECT Isle01 FROM items WHERE Barcode = '" . $this->barcode . "'";
      $res = $db->Query($query);
      $ShoppingList = $res->fetch();

      return $ShoppingList['Isle01'];
    }
    
    public function GetIsle02() {
      require_once '../database.php';
      global $db;

      $query = "SELECT Isle02 FROM items WHERE Barcode = '" . $this->barcode . "'";
      $res = $db->Query($query);
      $ShoppingList = $res->fetch();

      return $ShoppingList['Isle02'];
    }
    
    public function GetBuyInBulk() { 
      require_once '../database.php';
      global $db;

      $query = "SELECT BuyInBulk FROM items WHERE Barcode = '" . $this->barcode . "'";
      $res = $db->Query($query);
      $ShoppingList = $res->fetch();

      return $ShoppingList['BuyInBulk'];
    }
    
    
    public function GetBarcode() { return $this->barcode; }
    public function SetBarcode($barcode) { 
        $this->barcode = $barcode; 
    }
    
    public function IsValidBarcode() {
      require_once '../database.php';
      global $db;
      
      $query = "SELECT Barcode FROM items";
      $item = $db->Query($query);

      if($item) {
        foreach($item as $row) {
          if($row['Barcode'] == $this->barcode) {
            return true;
          }
        }
      }

      return false;
    }
    
    public function IsDuplicateBarcode() {
      require_once '../database.php';
      global $db;

      $query = "SELECT Barcode FROM current_list";
      $item = $db->Query($query); 

      if($item) {
        foreach($item as $row) {
          if($row['Barcode'] == $this->barcode) {
            return true;
          }
        }
      }

      return false;
	}
	
	public function IsValidName($needle) {
      require_once '../database.php';
      global $db;

      $name = strtoupper($needle);

      $query = "SELECT `Name`, `Barcode` FROM items WHERE `Name` = '$name'";
      $item = $db->Query($query); 

      if($item) {
        foreach($item as $row) {
          if($row['Name'] == $name) {
            return $row['Barcode'];
          }
        }
      }

      return false;
    }
        
    public function NameIsLike($needle) {
      require_once '../database.php';
      global $db;

      $name = strtoupper($needle);

      $query = "SELECT `Name`, `Barcode` FROM items WHERE `Name` LIKE '%$name%' ORDER BY Name";
      $item = $db->Query($query); 

      if($item) {
        return $item->fetchAll();
      }

      return false;
    }
}
?>

<?php $SL = new SL;
