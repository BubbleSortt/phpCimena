<?php
require_once '../includes/db.php';

class FilmsCRUD
{
    private function checkImage($pdo)
    {
        //Проверяем картинку
        if(isset($_FILES) && isset($_FILES['image']))
        {
            $image = $_FILES['image'];

            $image_type = $image['type'];

            //проверяем тип файла
            if($image_type == 'image/jpeg' || $image_type == 'image/png')
            {
                //сохраняем формат изображения
                $image_format = explode('.', $image['name']);
                $image_format = $image_format[1];

                //сохраняем полное имя изображения изменяя его
                $image_full_name = $_SERVER['DOCUMENT_ROOT'].'/static/images/'.hash('crc32', date("m.d.y")).'.'.$image_format;


                //тут надо будет добавить какую то проверку, чтобы если не удалось сохранить получить что - то
                move_uploaded_file($image['tmp_name'], $image_full_name);
                return $image_full_name;
            }
            else
            {
                //Если не верный тип, возвращаем ошибку
                $error_json['error'] = "Image type don\'t supported";
                http_response_code(500);
                echo json_encode($error_json);
                exit();
            }
        }
        else
        {
            if(isset($_POST['image']))
            {
                return $_POST['image'];
            }
            else
            {
                //Как - то отметить то, что картинка не перадана
                //Саша сказал, что все поля приходят, так что не надо
                $error_json['error'] =  'file not found';
                http_response_code(500);
                echo json_encode($error_json);
                exit();
            }
        }
    }
    public function AddFilm($pdo){
        $film_id = $_POST['id'];

        //Проверяем новый это фильм или такой уже есть
        $film_already_exist = $pdo->prepare("SELECT `id` FROM `films` WHERE `id` = :film_id");
        $film_already_exist->bindValue(':film_id', $film_id);
        $film_already_exist->execute();

        if($film_already_exist->rowCount() > 0)
        {

            $image_full_name = $this->checkImage($pdo);

            //Получаем название категории
            $categorie_title = mb_strtolower($_POST['category']);

            //По названию категории вытаскиваем из второй таблицы(film_сategorie) ее айди
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

            //Все поля есть - добавляем новый фильм!
            $stmt = $pdo->prepare("UPDATE `films` 
                                    SET `add_date` = :add_date, `title` = :title, `description` = :description,
                                     `categorie_id` = :categorie_id, `image` = :image WHERE `id` = :film_id");
            $date = $_POST['date'];
            $stmt->bindValue(':film_id', $_POST['id']);
            $stmt->bindValue(':title', $_POST['title']);
            $stmt->bindValue(':description', $_POST['description']);
            $stmt->bindValue(':categorie_id', $categorie_id['id'] );
            $stmt->bindValue(':image', $image_full_name);
            $stmt->bindValue(':add_date', date("Y-m-d", strtotime($date)));
            $stmt->execute();
        }
        else
        {

            $image_full_name = $this->checkImage($pdo);
            //Получаем название категории
            $categorie_title = mb_strtolower($_POST['category']);

            //По названию категории вытаскиваем из второй таблицы(film_сategorie) ее айди
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

            //Все поля есть - добавляем новый фильм!
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
    public function GetLastAdedd($pdo){
        //выбираем из базы максимальный id
        $last_film_id_query = $pdo->query("select max(`id`) from `films`");
        $last_film_id = $last_film_id_query->fetch();

        //по этому id выбираем фильм
        $last_film_query = $pdo->prepare("SELECT * from `films` WHERE `id` = :id");
        $last_film_query->bindValue(':id', $last_film_id['max(`id`)']);
        $last_film_query->execute();

        $last_film = $last_film_query->fetch();

        //опять меняем Id, который в виде числа на id в виде строки
        $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
        $film_category_query->bindValue(':categorie_id',$last_film['categorie_id']);
        $film_category_query->execute();
        $film_category = $film_category_query->fetch();
        $last_film['categorie_id'] = $film_category['categorie_title'];

        //Здесь мне нужно было вернуть на клиент "настоящее" текущее значение автоинкремента id
        //Например, два фильма из базы удалили, теперь последний id в базе 10, а следующий фильм который добавим
        //должен иметь значение 13(автоинкремент не уменьшается)
        $autoincrement_data = $pdo->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE
            TABLE_SCHEMA = 'id15064873_cinema_db' AND TABLE_NAME = 'films'");

        $autoincrement  = $autoincrement_data->fetch();
        $last_film['next_id'] = $autoincrement['AUTO_INCREMENT'];//Добавляю значение автоинкремента в массив

        //Возвращаем последний фильм в виде json
        echo json_encode($last_film);

    }
    public function GetAllFilms($pdo){
        //Выбираем все фильмы из базы
        $all_films_query = $pdo->query("Select * from `films`");

        //Массив, который отправим в виде json в ответе
        $all_films = array();

        while ($film = $all_films_query->fetch()) {

            //Нам нужны id в виде строк, а не цифр, поэтому обращаемся к таблице с id и вытаскиваем их строковый вид
            $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
            $film_category_query->bindValue(':categorie_id', $film['categorie_id']);
            $film_category_query->execute();

            $film_category = $film_category_query->fetch();
            //меняем у фильма цифровой id на строковый.
            $film['categorie_id'] = $film_category['categorie_title'];

            array_push($all_films, $film);//складываем фильм в массив
        }

        //Делаем json из массива
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
    public function DeleteFilm($pdo)
    {
        $film_id = $_POST['id'];

        //Проверяем новый это фильм или такой уже есть
        $film_exist = $pdo->prepare("SELECT `id` FROM `films` WHERE `id` = :film_id");
        $film_exist->bindValue(':film_id', $film_id);
        $film_exist->execute();
        if($film_exist->rowCount() > 0) {
            //Удаляем картинку
            $select_film_image_query = $pdo->prepare("SELECT `image` FROM `films` WHERE `id` = :film_id");
            $select_film_image_query->bindValue(':film_id', $_POST['id']);
            $select_film_image_query->execute();

            $selected_film_image = $select_film_image_query->fetch();
            if(file_exists($selected_film_image['image']))
            {
                unlink($selected_film_image['image']);
            }
            //Удаляем запись из базы
            $delete_film_query = $pdo->prepare("DELETE FROM `films` WHERE `id` = :film_id");
            $delete_film_query->bindValue(':film_id', $_POST['id']);
            $delete_film_query->execute();
        }
        else{
            $error_json['error'] = "Film don't exist";
            http_response_code(500);
            echo json_encode($error_json);
            exit();
        }

    }

}