<?php
include_once("config.php");

// Function for transliteration
function transliterate($string) {
  $transliteration = array(
    'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
    'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
    'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
    'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
    'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
    'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'А' => 'A', 'Б' => 'B',
    'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo',
    'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K',
    'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P',
    'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
    'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
    'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
    'Я' => 'Ya'
  );
  $string = strtr($string, $transliteration);
  $string = preg_replace('/[^a-zA-Z0-9\-]/', '-', $string);
  $string = preg_replace('/-+/', '-', $string);
  $string = trim($string, '-');
  return $string;
}

// SQL Connect
$sql = mysqli_connect($config["MySQL_host"],$config["MySQL_user"],$config["MySQL_pass"],$config["MySQL_db"]);
if ($config["Debug"] && mysqli_connect_errno()){
  die("Failed to connect to MySQL: " . mysqli_connect_error());
}

if (!empty($_POST)) {
  // Insert into database
  $stmt = $sql->prepare("INSERT INTO stories (title, url_title, author, content, date, hits) VALUES (?, ?, ?, ?, ?, ?)");
  $title = $sql->real_escape_string($_POST["title"]);
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

  $author = $sql->real_escape_string($_POST["author"]);
  $author = !empty($author) ? $author : "Anonymous";
  $content = $_POST["content"];
  $date = date("Y-m-d H:i:s");
  $hits = 0;

$stmt->bind_param("sssssi", $title, $url_title, $author, $content, $date, $hits);
if ($stmt->execute()) {
echo $url_title;
die();
}
$stmt->close();
} else {
echo "new";
die();
}
mysqli_close($sql);
?>
