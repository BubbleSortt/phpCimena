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
    <h1 class="popular-main-title">Популярное</h1>
    <section class="popular">
        <div class="container">

            <div class="popular__list">

                <?php
                $popular_films = mysqli_query($connection,
                    "SELECT * FROM `films` WHERE `categorie_id`= 1 ORDER BY `id` DESC");
                ?>
                <?php
                while ($p_film = mysqli_fetch_assoc($popular_films))
                {
                    ?>
                    <div class="item-wrapper">
                        <div class="popular__item item">
                            <a href="#>" class="item__img-wrap">
                                <img src="<?php echo $p_film['image']?>" alt="">
                            </a>
                            <div class="item__info">
                                <a href="#>" class="item__title"><?php echo $p_film['title']?></a>
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