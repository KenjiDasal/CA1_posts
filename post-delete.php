<?php require_once 'config.php';

try {
  // post_id is valid of it is present, is an integer with a minimum value of 1
  $rules = [
    'post_id' => 'present|integer|min:1'
  ];

  // you can have a look at the validate() function in HttpRequest.php (line 415)
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }
  //the post_id was passed in from post-index.php
  // now you extract it from the request object and assign the value to the variable $post_id  
  $post_id = $request->input('post_id');


  /*Retrieving the correct post object ($post) from the Database*/
  //Call findById(id) function to check if this post exists in the Database
  $post = Post::findById($post_id);
  // if post does not exist display error message 
  if ($post === null) {
    throw new Exception("No post exists or Illegal request parameter");
  }

  // $post is an object - created from the post class
  // calling $post->delete() calls the delete function in the post class
  $post->delete();

  // Display redirect to the list of posts and display the correct message - Deleted or Not Deleted
  $request->session()->set("flash_message", "The post was successfully deleted from the database");
  $request->session()->set("flash_message_class", "alert-info");
  $request->redirect("/post-index.php");
} catch (Exception $ex) {
  /*If something goes wrong, catch the message and store it as a flash message.*/
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  $request->redirect("/post-index.php");
}