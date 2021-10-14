<?php
// the class post defines the structure of what every post object will look like. ie. each post will have an id, title, description etc...
// NOTE : For handiness I have the very same spelling as the database attributes
class Post {
  public $id;
  public $title;
  public $description;
  public $author_name;
  public $likes;
  public $date;
  public $img_id;



  public function __construct() {
    $this->id = null;
  }

  public function save() {
    try {
      //Create the usual database connection - $conn
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $params = [
        ":title" => $this->title,
        ":description" => $this->description,
        ":author_name" => $this->author_name,
        ":likes" => $this->likes,
        ":date" => $this->date,
        ":img_id" => $this->img_id
      ];

      // We will uncomment this code when we get to do the Create 
      // If there is no ID yet, then it's a new post being created for the first time
      // if ($this->id === null) {
      //   $sql = "INSERT INTO posts (" .
      //     "title, description, author_name, likes, date, contact_name, contact_email, contact_phone, img_id" .
      //     ") VALUES (" .
      //     ":title, :description, :author_name, :likes, :date, :contact_name, :contact_email, :contact_phone, :img_id" .
      //     ")";
      // } else {
        // if there is an ID then it's an update for an existing post in the database. 
        $sql = "UPDATE posts SET " .
          "title = :title, " .
          "description = :description, " .
          "author_name = :author_name, " .
          "likes = :date, " .
          "date = :date, " .
          "img_id = :img_id " .
          "WHERE id = :id";
        $params[":id"] = $this->id;
    //  }


      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to save post.");
      }

      //If the save() was a new post created it won't have an ID
      // so retrieve the ID assigned by the DB. - remember auto_increment in the Database for assigning primary keys
      // if ($this->id === null) {
      //   $this->id = $conn->lastInsertId();
      // }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }
  }

  public function delete() {
    try {
      /*Create connection.*/
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $sql = "DELETE FROM posts WHERE id = :id";
      $params = [
        ":id" => $this->id
      ];

      $stmt = $conn->prepare($sql);
      $status = $stmt->execute($params);

      if (!$status) {
        $error_info = $stmt->errorInfo();
        $message = "SQLSTATE error code = " . $error_info[0] . "; error message = " . $error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($stmt->rowCount() !== 1) {
        throw new Exception("Failed to delete post.");
      }
    } finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }
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
          // Create $post object, then put the id, title, description, author_name etc into $post
          $post = new post();
          $post->id = $row['id'];
          $post->title = $row['title'];
          $post->description = $row['description'];
          $post->author_name = $row['author_name'];
          $post->likes = $row['likes'];
          $post->date = $row['date'];
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
    $post = null;

    try {
      $db = new DB();
      $db->open();
      $conn = $db->get_connection();

      $select_sql = "SELECT * FROM posts WHERE id = :id";
      $select_params = [
          ":id" => $id
      ];
      $select_stmt = $conn->prepare($select_sql);
      $select_status = $select_stmt->execute($select_params);

      if (!$select_status) {
        $error_info = $select_stmt->errorInfo();
        $message = "SQLSTATE error code = ".$error_info[0]."; error message = ".$error_info[2];
        throw new Exception("Database error executing database query: " . $message);
      }

      if ($select_stmt->rowCount() !== 0) {
        $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
          
        $post = new post();
        $post->id = $row['id'];
        $post->title = $row['title'];
        $post->description = $row['description'];
        $post->author_name = $row['author_name'];
        $post->likes = $row['likes'];
        $post->date = $row['date'];
        $post->img_id = $row['img_id'];
      }
    }
    finally {
      if ($db !== null && $db->is_open()) {
        $db->close();
      }
    }

    return $post;
  }

  
}
?>