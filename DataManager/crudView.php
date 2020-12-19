<!doctype html>
<html lang="ru">
<head>
    <?php require_once "../Shared/metaInf.php" ?>
    <?php require_once "../Shared/links.php" ?>
</head>
<body>
<?php
include "../Shared/header.php";
?>
<main crud="true">
    <div class="container">
        <div class="films">
            <button class="films__add crud-add" data-film-id="">Добaвить фильм</button>
            <div class="error error-films">
                <span></span>
            </div>
            <div class="films__table">
                <div class="films__header">
                    <p class="id films__nav">id</p>
                    <p class="films__nav">title</p>
                    <p class="films__nav">description</p>
                    <p class="films__nav">categorie_id</p>
                    <p class="films__nav">image</p>
                </div>
                <div class="films__body">



















                </div>

            </div>
        </div>
        <div class="categories">
            <button class="categories__add" data-category-id="">Добавить категорию</button>
            <div class="error error-categories">
                <span></span>
            </div>
            <div class="categories__table">
                <div class="categories__header">
                    <p class="id categories__nav">id</p>
                    <p class="categories__nav categories-description">description</p>
                </div>
                <div class="categories__body">




                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once "../Shared/js.php" ?></body>
</html>