<header>
    <div class="container">
        <div class="header__top header-top">
            <div class="header__nav">
                <a href="index.php" class="header__link">Главная</a>
                <a href="popular.php" class="header__link">Популярное</a>
                <a href="new-item.php" class="header__link">Новинки</a>
            </div>
            <?php
            if(isset($_SESSION['user']))
            {
             ?>
                <div class="header__auth">
                    <a href="profile.php" class="header__link">Личный кабинет</a>
                    <a href="favorite.php" class="header__link">Любимое</a>
                </div>
            <?php
            }
            else
            {
             ?>
                <div class="header__auth">
                    <a href="registration.php" class="header__link">Регистрация</a>
                    <a href="auth.php" class="header__link">Вход</a>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</header>