<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Teko" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Alegreya|Alegreya+Sans|Merriweather|Merriweather+Sans|Nunito|Nunito+Sans|Quattrocento|Quattrocento+Sans|Roboto|Roboto+Mono|Roboto+Slab"
          rel="stylesheet">

    <title>SatireWire</title>
</head>
<body>
<header class="container d-flex justify-content-center text-light">
    <div class="row navbar-dark">
        <h2 class="col-sm-6 col-md-12 col-lg-12 col-xl-12">
            SatireWire
        </h2>
    </div>
</header>

<section class="jumbotron">
    <div class="container">
        <div class="row justify-content-center">
            <a class="btn-link" id="call-to-action" href="http://michaelmedvedskiy.site/edit/">
                <h3>Admin page</h3>
            </a>
        </div>
    </div>
</section>

<?php
$host = 'localhost';
$user = 'root';
$password = 'ssvh92nyqe';
$link = mysqli_connect($host, $user, $password); //Подключаемся к MySQL при помощи данных выше.

if(! $link ) {
    die('Could not connect: ' . mysqli_error()); //Если не можем подключиться - ошибка
}
$query = 'CREATE DATABASE IF NOT EXISTS newspaper'; //Если нет базы данных - создаём
mysqli_query($link, $query); //Выполнение запроса в query
mysqli_select_db($link , 'newspaper'); //Выбираем БД
$val = mysqli_query('select 1 from papers LIMIT 1'); //Проверяем наличие таблицы, ниже смотрим если её нет, то создаём

if ($val == FALSE) {
    $query = "create table papers(
            ID INT AUTO_INCREMENT, 
            caption VARCHAR(69) NOT NULL,
            story TEXT, 
            primary key (id))";

    mysqli_query($link, $query);
}


$query = "SELECT * FROM papers" . " ORDER BY ID DESC"; //достаём все данные из таблицы, сортируем их по убыванию ID (чтобы последние новости появлялись наверху)
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) { //Проходим циклом для всех результатов и для каждого добавляем html по шаблону
        ?>
        <section class="container text-light bg-dark" id="section">
            <div class="row text-light bg-dark d-flex justify-content-center">
                <?php echo "<h1 id=\"about\">" . $row["caption"] . "</h1>";
                ?>
            </div>
            <div class="text-sm-left text-md-left text-lg-left text-xl-left">
                <?php echo "<p>" . $row["story"] . "</p>";
                ?>
                <br>
            </div>
        </section>
        <?php
    }

} else {?>
<div class="row text-light bg-dark d-flex justify-content-center">
    <?php echo "<h1>" . "no papers found" . "</h1>"; //если таблица пуста
    ?>
</div>
<?php
}
mysqli_close($link);
?>
</body>
</html>