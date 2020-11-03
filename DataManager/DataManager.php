<?php
    require_once '../includes/db.php';
    if(isset($_REQUEST['action']))
    {
        switch($_REQUEST['action'])
        {
            case 'add':
                AddFilm($pdo);
                break;
            case 'edit':
                break;
            case 'getLast':
                GetLastAdded($pdo);
                break;
            case 'getAll':
                GetAllFilms($pdo);
                break;
        }
    }


    function AddFilm($pdo)
    {
        $film_id = $_POST['id'];
        settype($film_id, integer);

        $film_already_exist = $pdo->prepare("SELECT `id` FROM `films` WHERE `id` = :film_id");
        $film_already_exist->bindValue(':film_id', $film_id);
        $film_already_exist->execute();
        $film_already_exist = $film_already_exist->fetch();

        if(isset($film_already_exist))
        {
            $errors['film-added-error'] = "Фильм уже существует";
            return json_encode($errors);
        }
        else
        {
            //Проверяем картинку
            if(isset($_FILE) && isset($_FILE['image']))
            {
                $image = $_FILE['image'];

                $image_format = explode('.', $image['name']);
                $image_format = $image_format[1];

                $image_full_name = './static/images/'.hash('src32', date()).'.'.$image_format;

                $image_type = $image['type'];

                if($image_type == 'image/jpeg' || $image_type == 'image/png')
                {
                    //тут надо будет добавить какую то проверку, чтобы если не удалось сохранить получить что - то
                    move_uploaded_file($image['tmp_name'], $image_full_name);
                }
            }
            else
            {
                //Как - то отметить то, что картинка не перадана
                //Саша сказал, что все поля приходят, так что не надо
                $errors['image_not_found_error'] = "Картинка не найдена";
                return json_encode($errors);
            }

            $categorie_title = mb_strtolower($_POST['categorie_title']);

            $categorie_id = $pdo->prepare("SELECT `id` FROM `film_categorie` WHERE `categorie_title`=:categorie_title");

            $categorie_id->bindValue(':categorie_title', $categorie_title);
            $categorie_id->execute();

            $categorie_id = $categorie_id->fetch();

            $stmt = $pdo->prepare("INSERT INTO `films`(`title`, `description`, `categorie_id`, `image`) 
                                VAlUES (:title, :description, :categorie_id, :image)");

            $stmt->bindValue(':title', $_GET['title']);
            $stmt->bindValue(':description', $_GET['title']);
            $stmt->bindValue(':categorie_id', $categorie_id['id'] );
            $stmt->bindValue(':image', $image_full_name);
            $stmt->execute();
            return true;
        }
    }

    function GetAllFilms($pdo)
    {

        $all_films_query = $pdo->query("Select * from `films`");

        while($film = $all_films_query->fetch())
        {
            header('Content-Type: application/json');
            echo json_encode($film);
        }
    }

    function GetLastAdded($pdo)
    {
        $last_film_id_query = $pdo->query("select max(`id`) from `films`");
        $last_film_id = $last_film_id_query->fetch();

        $last_film_query = $pdo->prepare("SELECT * from `films` WHERE `id` = :id");
        $last_film_query->bindValue(':id', $last_film_id['max(`id`)']);
        $last_film_query->execute();

        $last_film = $last_film_query->fetch();

        header('Content-Type: application/json');
        echo json_encode($last_film);
    }
?>