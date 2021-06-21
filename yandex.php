<?php

header("Access-Control-Allow-Origin: *");
setlocale(LC_ALL, "ru_RU");
date_default_timezone_set("Europe/Moscow");

$opts = array(
  'http' => array(
    'method' => "GET",
    'header' => "X-Yandex-API-Key: 7ffcc384-8f62-46d4-ba8b-b951e7dd8296"
  )
);

$url = "https://api.weather.yandex.ru/v1/forecast?lat=48.4700&lon=44.4600&limit=1&hours=false&extra=false";
$context = stream_context_create($opts);
$contents = file_get_contents($url, false, $context);
$clima = json_decode($contents);

$conn = mysqli_connect('localhost', 'root', '', 'weather');
 
if (!$conn) {
  die("Ошибка соединения: " . mysqli_connect_error());
}
echo "Соединение установлено";
echo "<br>";

$sql = "INSERT INTO yandex (Temp) VALUES ('{$clima->fact->temp}')";

if (mysqli_query($conn, $sql)) {
      echo "Новая запись добавлена";
      echo "<br>";
} else {
      echo "Ошибка: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);

?>


