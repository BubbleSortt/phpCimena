<?php
session_start();
require $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
?>

<!doctype html>
<html lang="ru">
<head>
    <?php require_once  "includes/metaInf.php"?>
    <?php require_once "includes/links.php"?>
</head>
<body>
<?php
include "includes/header.php";
?>
<main class="">
    <h1 class="new-item-main-title">Новинки</h1>
    <section class="new-item">
        <div class="container">

            <div class="new-item__list">
                <?php
                $new_films = mysqli_query($connection,
                    "SELECT * FROM `films` WHERE `categorie_id`= 2 ORDER BY `id` DESC ");
                ?>
                <?php
                while ($n_film = mysqli_fetch_assoc($new_films))
                {
                ?>
                    <div class="item-wrapper">
                        <div class="new__item item">
                            <a href="#" class="item__img-wrap">
                                <img src="<?php echo $n_film['image']?>" alt="">
                            </a>
                            <div class="item__info">
                                <a href="#" class="item__title"><?php echo $n_film['title']?></a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php include_once "includes/js.php"?>
</body>
</html>