<?php 
    
class Item { 
    private $barcode;
    private $name;
    private $isle01;
    private $isle02;
    private $buyInBulk;
    
    private $category;
    
    private static $ItemCount = 0;
    public static function GetItemCount() { return self::$ItemCount; }
    
    public static function GetRandomBarcode() {
      $random = rand(0,9999999);
      for($i = 0; $i < (12 - 5 - strlen($random)); $i++) { //max 12 digit - "99999"
        $zeropad .= "0";
      }
      return "99999$zeropad$random";
    }

    public function __construct($barcode = '', $name = '', $isle01 = '0', $isle02 = '0') {
        $this->barcode = $barcode;
        $this->name = $name;
        $this->isle01 = $isle01;
        $this->isle02 = $isle02;
        
        self::$ItemCount++;
    }
    
    public function __destruct() {
        self::$ItemCount--;
    }
    
    public function AddToDB() {
        require_once '../database.php';
    
        if($this->IsValidBarcode()) {
            if(!$this->IsDuplicateBarcode()) {
                global $db;
                $query = "INSERT INTO items (Barcode, Name, Isle01, Isle02)
                    VALUES ('";
                $query .= $this->barcode . "'";
		$query .= ", '" . strtoupper($this->name) . "'";
                $query .= ", '" . $this->isle01 . "'";
                $query .= ", '" . $this->isle02 . "'";
                $query .= ")";
                
                $num_rows_affected = $db->exec($query);  
                
                return $num_rows_affected;
            } return 0; //double barcode
        }
    }
    
    public function RemoveFromDB() {
        require_once '../database.php';
    
        global $db;
        
        $query = "DELETE FROM items WHERE Barcode = '" . $this->barcode . "'";
        $num_rows_affected = $db->exec($query);  

        return $num_rows_affected; 
    }
    
    public function RemoveCategory() {
      require_once '../database.php';
      global $db;

      $query = "UPDATE items SET Category = NULL";
      $query .= " WHERE Barcode = '" . $this->barcode;
      $query .= "'"; 
      return $db->exec($query);
    }
    
    public function UpdateDB($barcodeOverride = '') {
      require_once '../database.php';
      global $db;

      $query = "UPDATE items SET Name = '" . $this->name;

      if($barcodeOverride) {
        $query .= "', Barcode = '" . $barcodeOverride;
      }

      $query .= "', Isle01 = '" . $this->isle01;
      $query .= "', Isle02 = '" . $this->isle02;
      $query .= "', BuyInBulk = '" . $this->buyInBulk;

      if($this->category) {
        $query .= "', Category = '" . $this->category;
      }

      $query .= "' WHERE Barcode = '" . $this->barcode;
      $query .= "'"; 
      $numrowsaffected = $db->exec($query);

      return $numrowsaffected;
    }
    
    public function UpdateCategory() {
      require_once '../database.php';
      global $db;
      
      if($this->category && $this->barcode) {
        $query = "UPDATE items SET Category = :0 WHERE Barcode = :1";
        return $db->SafeExec($query, array($this->category, $this->barcode));
      }
      
      return 0;
    }
    
    public function SetFromDB($barcode) {
        require_once '../database.php';
    
        global $db;
        $query = "SELECT * FROM items WHERE Barcode = '$barcode'";
        $item = $db->query($query);  
        $item = $item->fetch();
        
        $this->SetBarcode($barcode);
        $this->name = $item['Name'];
        $this->isle01 = $item['Isle01'];
        $this->isle02 = $item['Isle02'];
        $this->buyInBulk = $item['BuyInBulk'];
        
        return $item;
    }
    
    public function GetName() { return $this->name; }
    public function SetName($name) { $this->name = $name; }
    
    public function GetCategory() { return $this->category; }
    public function SetCategory($to) { $this->category = $to; }
    
    public function GetIsle01() { return $this->isle01; }
    public function SetIsle01($num) { $this->isle01 = $num; }
    
    public function GetIsle02() { return $this->isle02; }
    public function SetIsle02($num) { $this->isle02 = $num; }
    
    public function GetBuyInBulk() { return $this->buyInBulk; }
    public function SetBuyInBulk($val) { $this->buyInBulk = $val; }
	
    public function ChangeBarcode_In_DB($toBarcode) { //must have Barcode already set in order to change
	require_once '../database.php';
	global $db;
	
	$query = "UPDATE items SET Barcode = '" . $barcode;
        $query .= "' WHERE Barcode = '" . $this->barcode . "'";
	$numrowsaffected = $db->exec($query);

	return $numrowsaffected;
    }
    public function GetBarcode() { return $this->barcode; }
    public function SetBarcode($barcode) { 
        $this->barcode = $barcode; 
    }
    public function IsValidBarcode() { 
        if($this->barcode > 1 && $this->barcode != '') { return true; }
        else { return false; }
    }
    public function IsDuplicateBarcode() {
        require_once '../database.php';
    
        global $db;
        $query = "SELECT * FROM items";
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
}
?>

<?php $item = new Item;
