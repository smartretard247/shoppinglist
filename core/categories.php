<?php 
    
class Category { 
    private $id;
    private $name;
    private $isle01;
    private $isle02;
    
    private static $CategoryCount = 0;
    public static function GetCategoryCount() { return self::$CategoryCount; }
    
    public function __construct($id = '', $name = '', $isle01 = '0', $isle02 = '0') {
        $this->id = $id;
        $this->name = $name;
        $this->isle01 = $isle01;
        $this->isle02 = $isle02;
        
        self::$CategoryCount++;
    }
    
    public function __destruct() {
        self::$CategoryCount--;
    }
    
    public function AddToDB() {
        require_once '../database.php';
        global $db;
        
        $query = "INSERT INTO categories (Name)
            VALUES ('";
        $query .= $this->name . "'";
        $query .= ")";
                
        return $db->exec($query);
    }
    
    public function RemoveFromDB() {
        require_once '../database.php';
        global $db;
        
        $query = "DELETE FROM categories WHERE ID = '" . $this->id . "'";
        return $db->exec($query);
    }
    
    public function UpdateDB($idOverride = '') {
        require_once '../database.php';
        global $db;
        
        $query = "UPDATE categories SET Name = '" . $this->name;
        
        if($idOverride) {
          $query .= "', ID = '" . $idOverride;
        }
        
        $query .= "', Isle01 = '" . $this->isle01;
        $query .= "', Isle02 = '" . $this->isle02;
        $query .= "' WHERE ID = '" . $this->id;
        $query .= "'"; 
        return $db->exec($query);
    }
    
    public function UpdateCategoryIsle($isle) {
        require_once '../database.php';
        global $db;
        
        if($this->name) {
          if($isle == 1) {
            $query = "UPDATE categories SET Isle01 = '$this->isle01'";
            $query .= " WHERE Name = '" . $this->name;
            $query .= "'"; 
            return $db->exec($query);
          } elseif($isle == 2) {
            $query = "UPDATE categories SET Isle02 = '$this->isle02'";
            $query .= " WHERE Name = '" . $this->name;
            $query .= "'"; 
            return $db->exec($query);
          }
        }
    }
    
    public function UpdateAllInCategory($isle) {
      require_once '../database.php';
      global $db;
      
      if($this->name) {
        if($isle == 1) {
          $query = "UPDATE items SET Isle01 = :0 WHERE Category = :1";
          return $db->SafeExec($query, array($this->isle01, $this->name));
        } elseif($isle == 2) {
          $query = "UPDATE items SET Isle02 = :0 WHERE Category = :1";
          return $db->SafeExec($query, array($this->isle02, $this->name));
        }
      }
      
      return 0;
    }
    
    public function SetFromDB($id) {
        require_once '../database.php';
        global $db;
        
        $query = "SELECT * FROM categories WHERE ID = '$id'";
        $res = $db->query($query);  
        $item = $res->fetch();
        
        $this->SetID($id);
        $this->name = $item['Name'];
        $this->isle01 = $item['Isle01'];
        $this->isle02 = $item['Isle02'];
        
        return $item;
    }
    
    public function GetName() { return $this->name; }
    public function SetName($name) { $this->name = $name; }
    
    public function GetIsle01() { return $this->isle01; }
    public function SetIsle01($num) { $this->isle01 = $num; }
    
    public function GetIsle02() { return $this->isle02; }
    public function SetIsle02($num) { $this->isle02 = $num; }
	
    public function GetID() { return $this->id; }
    public function SetID($id) { 
        $this->id = $id; 
    }
}
?>

<?php $category = new Category;
