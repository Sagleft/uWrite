<?php
  include_once("config.php");
  include_once("utility.php");

  // SQL Connect
  $sql = mysqli_connect($config["MySQL_host"],$config["MySQL_user"],$config["MySQL_pass"],$config["MySQL_db"]);
  if ($config["Debug"] && mysqli_connect_errno()){
    exit("Failed to connect to MySQL");
  }

  if (!empty($_POST)) {
      // Insert into database
      $stmt = $sql->prepare("INSERT INTO stories (title, url_title, author, content, date, hits) VALUES (?, ?, ?, ?, ?, ?)");
      $title = dataFilter($_POST["title"], $sql);
      $title = !empty($title) ? $title : "Untitled";

      // Generate SEO URL with transliteration
      $ut_recipe = transliterate($title);
      $ut_recipe = strlen($ut_recipe) > 30 ? substr($ut_recipe, 0, 30) . "-" . date("m-d") : $ut_recipe . "-" . date("m-d");

      $ut_result = $sql->query("SELECT url_title FROM stories WHERE url_title='$ut_recipe'");
      if ($ut_result->num_rows > 0) {
        $url_title = strlen($ut_recipe) > 30 ? substr($ut_recipe, 0, 30) . "-" . date("m-d") . $ut_result->num_rows : $ut_recipe . "-" . date("m-d") . "-" . $ut_result->num_rows;
      } else {
        $url_title = $ut_recipe;
      }

      $author = dataFilter($_POST["author"], $sql);
      $author = !empty($author) ? $author : "Anonymous";
      $content = $_POST["content"];
      $date = date("Y-m-d H:i:s");
      $hits = 0;

      $stmt->bind_param("sssssi", $title, $url_title, $author, $content, $date, $hits);
      if ($stmt->execute()) {
        exit($url_title);
      }
      $stmt->close();
  } else {
      exit("new");
  }

  mysqli_close($sql);
