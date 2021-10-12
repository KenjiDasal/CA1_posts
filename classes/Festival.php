<?php
// the class Festival defines the structure of what every festival object will look like. ie. each festival will have an id, title, description etc...
// NOTE : For handiness I have the very same spelling as the database attributes
class Festival {
  public $id;
  public $title;
  public $description;
  public $location;
  public $start_date;
  public $end_date;
  public $contact_name;
  public $contact_email;
  public $contact_phone;
  public $image_id;



  public function __construct() {
    $this->id = null;
  }

  public function save() {
    throw new Exception("Not yet implemented");
  }

  public function delete() {
    throw new Exception("Not yet implemented");
  }

  public static function findAll() {
    $festivals = array();

    try {
      // call DB() in DB.php to create a new database object - $db
      $db = new DB();
      $db->open();
      // $conn has a connection to the database
      $conn = $db->get_connection();
      

      // $select_sql is a variable containing the correct SQL that we want to pass to the database
      $select_sql = "SELECT * FROM festivals";
      $select_stmt = $conn->prepare($select_sql);
      // $the SQL is sent to the database to be executed, and true or false is returned 
      $select_status = $select_stmt->execute();

      // if there's an error display something sensible to the screen. 
      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }
      // if we get here the select worked correctly, so now time to process the festivals that were retrieved
      

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        while ($row !== FALSE) {
          // Create $festival object, then put the id, title, description, location etc into $festival
          $festival = new Festival();
          $festival->id = $row['id'];
          $festival->title = $row['title'];
          $festival->description = $row['description'];
          $festival->location = $row['location'];
          $festival->start_date = $row['start_date'];
          $festival->end_date = $row['end_date'];
          $festival->contact_name = $row['contact_name'];
          $festival->contact_email = $row['contact_email'];
          $festival->contact_phone = $row['contact_phone'];
          $festival->image_id = $row['image_id'];

          // $festival now has all it's attributes assigned, so put it into the array $festivals[] 
          $festivals[] = $festival;
          
          // get the next festival from the list and return to the top of the loop
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        }
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    // return the array of $festivals to the calling code - index.php (about line 6)
    return $festivals;
  }

  public static function findById($id) {
    throw new Exception("Not yet implemented");
  }
}
?>
