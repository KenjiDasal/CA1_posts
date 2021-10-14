<?php require_once 'config.php';

try {

  // The $rules array has 3 rules, post_id must be present, must be an integer and have a minimum value of 1.  
  // note post_id was passed in from post_index.php when you chose a post by clicking a radio button. 
  $rules = [
    'post_id' => 'present|integer|min:1'
  ];
  // $request->validate() is a function in HttpRequest(). You pass in the 3 rules above and it does it's magic. 
  $request->validate($rules);
  if (!$request->is_valid()) {
    throw new Exception("Illegal request");
  }

  // get the post_id out of the request (remember it was passed in from post_index.php)
  $post_id = $request->input('post_id');
 
  //Retrieve the post object from the database by calling findById($post_id) in the post.php class
  $post = Post::findById($post_id);
  if ($post === null) {
    throw new Exception("Illegal request parameter");
  }
} catch (Exception $ex) {
  $request->session()->set("flash_message", $ex->getMessage());
  $request->session()->set("flash_message_class", "alert-warning");

  // some exception/error occured so re-direct to the home page
  $request->redirect("/home.php");
}

?>



<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>View Customer</title>

  <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  <link href="<?= APP_URL ?>/assets/css/style.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet">


</head>

<body>
  <div class="container-fluid p-0">
    <?php require 'include/navbar.php'; ?>
    <main role="main">
      <div>
        <div class="row d-flex justify-content-center">
          <h1 class="t-peta engie-head pt-5 pb-5">View post</h1>
        </div>

        <div class="row justify-content-center pt-4">
          <div class="col-lg-10">
            <form method="get">
              <!--This is how we pass the ID-->
              <input type="hidden" name="post_id" value="<?= $post->id ?>" />

              <!--Disabled so the user can't intereact. This form is for viewing only.-->
              <div class="form-group">
                <label class="labelHidden" for="ticketPrice">Title</label>
                <input placeholder="Title" type="text" id="title" class="form-control" value="<?= $post->title ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="date">Description</label>
                <textarea name="description" rows="3" id="description" class="form-control" disabled><?= $post->description ?></textarea>
              </div>

              <div class="form-group">
                <label class="labelHidden" for="location">Location</label>
                <select class="form-control" name="location" id="location" disabled>
                  <!-- triple === means if it is equal. So is location is equal to "USA" display USA, if location is equal to "Belgium" display ...you get the idea..-->
                  <option value="USA" <?= (($post->location === "USA") ? "selected" : "") ?>>USA</option>
                  <option value="Belgium" <?= (($post->location === "Belgium") ? "selected" : "") ?>>Belgium</option>>
                  <option value="Brazil" <?= (($post->location === "Brazil") ? "selected" : "") ?>>Brazil</option>
                  <option value="UK" <?= (($post->location === "UK") ? "selected" : "") ?>>UK</option>
                  <option value="Germany" <?= (($post->location === "Germany") ? "selected" : "") ?>>Germany</option>
                  <option value="Japan" <?= (($post->location === "Japan") ? "selected" : "") ?>>Japan</option>
                  <option value="Netherlands" <?= (($post->location === "Netherlands") ? "selected" : "") ?>>Netherlands</option>
                  <option value="Hungary" <?= (($post->location === "Hungary") ? "selected" : "") ?>>Hungary</option>
                  <option value="Morocco" <?= (($post->location === "Morocco") ? "selected" : "") ?>>Morocco</option>
                  <option value="Spain" <?= (($post->location === "Spain") ? "selected" : "") ?>>Spain</option>
                  <option value="Canada" <?= (($post->location === "Canada") ? "selected" : "") ?>>Canada</option>
                  <option value="Croatia" <?= (($post->location === "Croatia") ? "selected" : "") ?>>Croatia</option>
                  <option value="Philippines" <?= (($post->location === "Philippines") ? "selected" : "") ?>>Philippines</option>
                </select>
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueCapacity">Start Date</label>
                <input placeholder="Start Date" type="date" class="form-control" id="startDate" value="<?= $post->start_date ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueCapacity">End Date</label>
                <input placeholder="End Date" type="date" class="form-control" id="endDate" value="<?= $post->end_date ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Contact Name</label>
                <input placeholder="Contact Name" type="text" id="contactName" class="form-control" value="<?= $post->contact_name ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Contact Email</label>
                <input placeholder="Contact Email" type="email" id="contactEmail" class="form-control" value="<?= $post->contact_email ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Contact Phone</label>
                <input placeholder="Contact Phone" type="text" id="contactPhone" class="form-control" value="<?= $post->contact_phone ?>" disabled />
              </div>

              <div class="form-group">
                <label class="labelHidden" for="venueDescription">Image</label>
                <?php
                try {
                  $image = Image::findById($post->image_id);
                } catch (Exception $e) {
                }
                if ($image !== null) {
                ?>
                  <img src="<?= APP_URL . "/" . $image->filename ?>" width="205px" alt="image" class="mt-2 mb-2" />
                <?php
                }
                ?>
              </div>

              <div class="form-group">
                <a class="btn btn-default" href="<?= APP_URL ?>/home.php">Cancel</a>
                <button class="btn btn-warning" formaction="<?= APP_URL ?>/post-edit.php">Edit</button>
                <button class="btn btn-danger btn-post-delete" formaction="<?= APP_URL ?>/post-delete.php">Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <?php require 'include/footer.php'; ?>
  </div>
  <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
  <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= APP_URL ?>/assets/js/post.js"></script>

  <script src="https://kit.fontawesome.com/fca6ae4c3f.js" crossorigin="anonymous"></script>

</body>

</html>