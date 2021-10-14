<?php
require_once 'config.php';

try {
    // $request = new HttpRequest();

    $author_names = [
        "USA",  "Belgium", "Brazil", "UK",
        "Hungary", "Morocco", "Spain",
        "Germany", "Japan", "Netherlands",
        "Canada", "Croatia", "Philippines"
    ];

    $rules = [
        "post_id" => "present|integer|min:1",
        "title" => "present|minlength:2|maxlength:64",
        "description" => "present|minlength:20|maxlength:2000",
        "author_name" => "present|minlength:10|maxlength:10",
        "likes" => "present|minlength:10|maxlength:10",
        "date" => "present|minlength:10|maxlength:255",
        "author_name" => "present|minlength:7|maxlength:255",
        //2 or 3 digits then a -, then between 5 to 7 more numbers

    ];

    $request->validate($rules);
    if ($request->is_valid()) {
        $image = null;
        if (FileUpload::exists('profile')) {
            //If a file was uploded for profile,
            //create a FileUpload object
            $file = new FileUpload("profile");
            $filename = $file->get();
            //Create a new image object and save it.
            $image = new Image();
            $image->filename = $filename;

            // you must implement save() function in the Image.php class
            $image->save();
        }
        $post = post::findById($request->input("post_id"));
        $post->title = $request->input("title");
        $post->description = $request->input("description");
        $post->author_name = $request->input("author_name");
        $post->likes = $request->input("likes");
        $post->date = $request->input("date");
        /*If not null, the user must have uploaded an image, so reset the image id to that of the one we've just uploaded.*/
        if ($image !== null) {
            $post->img_id = $image->id;
        }

        // you must implement the save() function in the post class
        $post->save();

        $request->session()->set("flash_message", "The post was successfully updated in the database");
        $request->session()->set("flash_message_class", "alert-info");
        /*Forget any data that's already been stored in the session.*/
        $request->session()->forget("flash_data");
        $request->session()->forget("flash_errors");

        $request->redirect("/post-index.php");
    } else {
        $post_id = $request->input("post_id");
        /*Get all session data from the form and store under the key 'flash_data'.*/
        $request->session()->set("flash_data", $request->all());
        /*Do the same for errors.*/
        $request->session()->set("flash_errors", $request->errors());

        $request->redirect("/post-edit.php?post_id=" . $post_id);
    }
} catch (Exception $ex) {
    //redirect to the create page...
    $request->session()->set("flash_message", $ex->getMessage());
    $request->session()->set("flash_message_class", "alert-warning");
    $request->session()->set("flash_data", $request->all());
    $request->session()->set("flash_errors", $request->errors());

    // not yet implemented
    $request->redirect("/post-create.php");
}