<?php
session_start();
require $_SERVER['DOCUMENT_ROOT']."/includes/config.php";
?>

<!doctype html>
<html lang="ru">
<head>
    <?php require_once  "includes/metaInf.php"?>
    <?php require_once "includes/links.php"?>
<body>
<?php
include "includes/header.php";
?>
<main class="main">
    <section class="popular">
        <div class="container">
            <div class="popular__header section-header">
                <h2 class="popular__title section-header__title title">Популярное</h2>
                <a class="popular__go-to section-header__go-to" href="#">Перейти</a>
            </div>
            <div class="popular__list">
            <?php
                $popular_films = mysqli_query($connection,
                "SELECT * FROM `films` WHERE `categorie_id`= 1 ORDER BY `id` DESC LIMIT 4 ");
            ?>
            <?php
                while ($p_film = mysqli_fetch_assoc($popular_films))
                {
                    ?>
                <div class="item-wrapper">
                    <div class="popular__item item">
                        <a href="#" class="item__img-wrap">
                            <img src="<?php echo $p_film['image']?>" alt="">
                        </a>
                        <div class="item__info">
                            <a href="#" class="item__title"><?php echo $p_film['title']?></a>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>

            </div>
        </div>

    </section>
    <section class="news">
        <div class="container">
            <div class="news__header section-header">
                <h2 class="news__title section-header__title title">Новости</h2>
                <a class="section-header__go-to" href="#">Перейти</a>
            </div>
            <div class="news__content">
                <div class="big-half">
                    <img src="static/img/news_img.jpg" alt="">
                </div>
                <div class="small-half">
                    <div class="article">
                        <h3 class="article__title">Хороший довод сходить в кино: обзор фильма "Довод"</h3>
                        <span class="article__date">Feb 12, 2020</span>
                        <p class="article__preview">
                            G-Star Raw is pushing the sustainability movement forward with a collection
                            of stretch denim items for spring 2020 which is particular which is particularly designed for reuse...
                        </p>
                        <a class="article__link" href="">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="new-item">
        <div class="container">
            <div class="new-item__header section-header">
                <h2 class="new-item__title section-header__title title">Новинки</h2>
                <a class="section-header__go-to" href="#">Перейти</a>
            </div>
            <div class="new-item__list">
                <?php
                $new_films = mysqli_query($connection,
                    "SELECT * FROM `films` WHERE `categorie_id`= 2 ORDER BY `id` DESC LIMIT 5 ");
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
<footer class="footer">

</footer>
<?php include_once "includes/js.php"?>
</body>
</html>