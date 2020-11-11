<?php
    require_once '../includes/db.php';

            if(isset($_REQUEST['action']))
            {
                header('Access-Control-Allow-Origin: *');
                header('Content-Type: application/json; charset=utf-8');
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
                    default:
                        $error_json = '{"error":"unknown action"}';
                        http_response_code(500);
                        echo json_encode($error_json);
                        break;
                }
            }
            else
            {
                $error_json = '{"error":"undefined action"}';
                http_response_code(500);
                echo json_encode($error_json);
            }

    function AddFilm($pdo)
    {
        $film_id = $_POST['id'];

        $film_already_exist = $pdo->prepare("SELECT `id` FROM `films` WHERE `id` = :film_id");
        $film_already_exist->bindValue(':film_id', $film_id);
        $film_already_exist->execute();

        if($film_already_exist->rowCount() > 0)
        {
            $error_json = '"error":"Film already exist"';
            http_response_code(500);
            echo json_encode($error_json);
            exit();
        }
        else
        {
            //Проверяем картинку
            if(isset($_FILES) && isset($_FILES['image']))
            {
                $image = $_FILES['image'];

                $image_format = explode('.', $image['name']);
                $image_format = $image_format[1];

                $image_full_name = $_SERVER['DOCUMENT_ROOT'].'/static/images/'.hash('crc32', date("m.d.y")).'.'.$image_format;
                $image_type = $image['type'];

                if($image_type == 'image/jpeg' || $image_type == 'image/png')
                {
                    //тут надо будет добавить какую то проверку, чтобы если не удалось сохранить получить что - то
                    move_uploaded_file($image['tmp_name'], $image_full_name);
                }
                else
                {
                    $error_json = '"error":"Image type don\'t supported';
                    http_response_code(500);
                    echo json_encode($error_json);
                    exit();
                }
            }
            else
            {
                //Как - то отметить то, что картинка не перадана
                //Саша сказал, что все поля приходят, так что не надо
                $error_json = '{"error":"File(image) not found"}';
                http_response_code(500);
                echo json_encode($error_json);
                exit();
            }

            //Получаем название категории
            $categorie_title = mb_strtolower($_POST['category']);

            //По названию категории вытаскиваем из базы ее айди
            $categorie_id_query = $pdo->prepare("SELECT `id` FROM `fim_categorie` WHERE `categorie_title`=:categorie_title");
            $categorie_id_query->bindValue(':categorie_title', $categorie_title);
            $categorie_id_query->execute();

            //Если такой категории в базе нет, то создаем новую категорию
            if($categorie_id_query->rowCount())
            {
                $categorie_id = $categorie_id_query->fetch();
            }
            else {
                //Создаем новую категорию
                $create_categorie_query = $pdo->prepare("INSERT INTO `fim_categorie` (`categorie_title`) VALUES (:new_categorie)");
                $create_categorie_query->bindValue(':new_categorie', $categorie_title);
                $create_categorie_query->execute();

                //После того как создали категорию, вытаскиваем ее Id
                $categorie_id_query->bindValue(':categorie_title', $categorie_title);
                $categorie_id_query->execute();
                $categorie_id = $categorie_id_query->fetch();
            }

            $stmt = $pdo->prepare("INSERT INTO `films` (`add_date`,`title`, `description`, `categorie_id`, `image`) 
                                VAlUES (:add_date, :title, :description, :categorie_id, :image)");
            $date = $_POST['date'];
            $stmt->bindValue(':title', $_POST['title']);
            $stmt->bindValue(':description', $_POST['description']);
            $stmt->bindValue(':categorie_id', $categorie_id['id'] );
            $stmt->bindValue(':image', $image_full_name);
            $stmt->bindValue(':add_date', date("Y-m-d", strtotime($date)));
            $stmt->execute();
        }
    }

    function GetAllFilms($pdo)
    {

        $all_films_query = $pdo->query("Select * from `films`");
        $all_films = array();
        while ($film = $all_films_query->fetch()) {
            $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
            $film_category_query->bindValue(':categorie_id',$film['categorie_id']);
            $film_category_query->execute();
            $film_category = $film_category_query->fetch();
            $film['categorie_id'] = $film_category['categorie_title'];
            array_push($all_films, $film);
        }

        $all_films_json = json_encode($all_films, JSON_UNESCAPED_UNICODE);

        if ($all_films_json === false) {
            // Avoid echo of empty string (which is invalid JSON), and
            // JSONify the error message instead:
            $all_films_json = json_encode(array("jsonError", json_last_error_msg()));
            if ($all_films_json === false) {
                // This should not happen, but we go all the way now:
                $all_films_json = '{"jsonError": "unknown"}';
            }
            // Set HTTP response status code to: 500 - Internal Server Error
            http_response_code(500);
        }
        echo $all_films_json;
    }
    function GetLastAdded($pdo)
    {
        $last_film_id_query = $pdo->query("select max(`id`) from `films`");
        $last_film_id = $last_film_id_query->fetch();

        $last_film_query = $pdo->prepare("SELECT * from `films` WHERE `id` = :id");
        $last_film_query->bindValue(':id', $last_film_id['max(`id`)']);
        $last_film_query->execute();

        $last_film = $last_film_query->fetch();

        $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
        $film_category_query->bindValue(':categorie_id',$last_film['categorie_id']);
        $film_category_query->execute();
        $film_category = $film_category_query->fetch();
        $last_film['categorie_id'] = $film_category['categorie_title'];

        echo json_encode($last_film);
    }
?>