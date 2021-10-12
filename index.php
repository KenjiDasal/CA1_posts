<?php require_once 'config.php'; ?>
<?php 
$posts = Post::findAll();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Music posts</title>

    <link href="<?= APP_URL ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= APP_URL ?>/assets/css/template.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <?php require 'include/header.php'; ?>
      <?php require 'include/navbar.php'; ?>
      <main role="main">
        <div>
          <h1>Our posts</h1>
          <div class="row">
          <?php foreach ($posts as $post) { ?>
            <div class="col mb-4">
              <div class="card py-5 px-5">
                <img src="<?= APP_URL ?>" class="card-img-top" alt="...">
                <div class="card-body">
                  <h5 class="card-title"><?= $post->title ?></h5>
                  <p class="card-text"><?= get_words($post->description, 20) ?></p>

                  <p>likes: <?= $post->likes ?></p>
                  <p> date: <?= $post->date ?></p>
                  <p>author name: <?= $post->author_name ?></p>
                  </div>
                
              </div>
            </div>
          <?php } ?>
          </div>
        </div>
      </main>
      <?php require 'include/footer.php'; ?>
    </div>
    <script src="<?= APP_URL ?>/assets/js/jquery-3.5.1.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
