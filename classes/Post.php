<?php
// the class post defines the structure of what every post object will look like. ie. each post will have an id, title, description etc...
// NOTE : For handiness I have the very same spelling as the database attributes
class Post {
  public $id;
  public $title;
  public $description;
  public $likes;
  public $date;
  public $author_name;
  public $img_id;


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
    $posts = array();

    try {
      // call DB() in DB.php to create a new database object - $db
      $db = new DB();
      $db->open();
      // $conn has a connection to the database
      $conn = $db->get_connection();
      

      // $select_sql is a variable containing the correct SQL that we want to pass to the database
      $select_sql = "SELECT * FROM posts";
      $select_stmt = $conn->prepare($select_sql);
      // $the SQL is sent to the database to be executed, and true or false is returned 
      $select_status = $select_stmt->execute();

      // if there's an error display something sensible to the screen. 
      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }
      // if we get here the select worked correctly, so now time to process the posts that were retrieved
      

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        while ($row !== FALSE) {
          // Create $post object, then put the id, title, description, likes etc into $post
          $post = new Post();
          $post->id = $row['id'];
          $post->title = $row['title'];
          $post->description = $row['description'];
          $post->likes = $row['likes'];
          $post->date = $row['date'];
          $post->author_name = $row['author_name'];
          $post->img_id = $row['img_id'];


          // $post now has all it's attributes assigned, so put it into the array $posts[] 
          $posts[] = $post;
          
          // get the next post from the list and return to the top of the loop
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
        }
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    // return the array of $posts to the calling code - index.php (about line 6)
    return $posts;
  }

  public static function findById($id) {
    throw new Exception("Not yet implemented");
  }
}
?>
