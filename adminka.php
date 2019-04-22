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

    <title>Adminka</title>
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
            <a class="btn-link" id="call-to-action" href="http://michaelmedvedskiy.site/">
                <h3>Main page</h3>
            </a>
        </div>
    </div>
</section>

<section class="container text-light" id="feedback-form">
    <div class="container">
        <div class="row d-flex justify-content-center">
            <h1>News</h1>
        </div>
        <form method="POST">
            <div class="row d-flex justify-content-center">
                <label> Caption </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="caption" aria-label="caption" required>
                </div>
                <label> Story </label>
                <div class="input-group">
                    <textarea class="form-control" name="story" aria-label="story"></textarea>
                </div>
                <button class="btn btn-lg btn-outline-light" type="submit" name="publish">Submit</button>
            </div>
        </form>
    </div>
</section>

<?php
$host = 'localhost';
$user = 'root';
$password = 'ssvh92nyqe';
$link = mysqli_connect($host, $user, $password);

if(! $link ) {
    die('Could not connect: ' . mysqli_error());
}
mysqli_select_db($link , 'newspaper');

$query = "SELECT * FROM papers" . " ORDER BY ID DESC";
$result = mysqli_query($link, $query);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
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
            <div id="order-button" class="row d-flex justify-content-center">
                <form action=""  method="POST">
                    <input type="hidden" name="article-delete" value="<?php echo $row['ID'] ?>"/>
                    <button class="btn btn-outline-light d-flex justify-content-center" type="submit" name="delete">Delete
                    </button>
                </form>
            </div>
        </section>
        <?php
    }

} else {?>
    <div class="row text-light bg-dark d-flex justify-content-center">
    <?php echo "<h1>" . "no papers found" . "</h1>";
    ?>
</div>
<?php
}
mysqli_close($link);

if (isset($_POST["delete"])) { //срабатывает при нажатии копки удаления новости


    $link = mysqli_connect($host, $user, $password);
    mysqli_select_db($link , 'newspaper');
    $articleToDelete = mysqli_real_escape_string($link, $_POST['article-delete']); // prevent SQL injection
    $res = mysqli_query($link,'DELETE FROM papers WHERE id='.$articleToDelete); //удаляем запись из таблицы при помощи ID, который пришёл с формой

    if (!$res) {
        die(mysqli_error($link)); //на случай ошибки
    }
    mysqli_close($link);
    header('Location: '.$_SERVER['REQUEST_URI']); //обновляем страницу после выполнения
}

if(isset($_POST["publish"])) {

    $link = mysqli_connect($host, $user, $password);
    mysqli_select_db($link , 'newspaper');
    $caption = mysqli_real_escape_string($link, $_POST["caption"]);
    $story = mysqli_real_escape_string($link, $_POST["story"]);
    $query = "INSERT IGNORE INTO papers (caption, story) VALUES ('" . $caption . "', '" . $story . "')"; //вставляем в таблицу запись с данными из формы

    if (mysqli_query($link, $query)) {
    } else {
        echo "Error: " . $query . "" . mysqli_error($link);
    }
    mysqli_close($link);
    header('Location: '.$_SERVER['REQUEST_URI']);
}

?>

</body>
</html>