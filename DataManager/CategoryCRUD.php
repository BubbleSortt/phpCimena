<?php
require_once '../includes/db.php';

class CategoryCRUD
{
    public function Add($pdo){
        $category_id = $_POST['id'];

        //Проверяем новый это фильм или такой уже есть
        $category_already_exist = $pdo->prepare("SELECT `id` FROM `fim_categorie` WHERE `id` = :category_id");
        $category_already_exist->bindValue(':category_id', $category_id);
        $category_already_exist->execute();

        if($category_already_exist->rowCount() > 0)
        {


            $update_category_query = $pdo->prepare("UPDATE `fim_categorie` SET `categorie_title` = :category_title WHERE `id` = :category_id");
            $update_category_query->bindValue(':category_title', mb_strtolower($_POST['categoryName']));
            $update_category_query->bindValue(':category_id', $_POST['id']);

            $update_category_query->execute();
        }
        else {
            $set_category_query = $pdo->prepare("INSERT INTO `fim_categorie` (`categorie_title`) VALUES (:category_title)");
            $set_category_query->bindValue(':category_title', mb_strtolower($_POST['categoryName']));

            $set_category_query->execute();
        }
    }
    public function GetLast($pdo){
        //выбираем из базы максимальный id
        $last_category_id_query = $pdo->query("select max(`id`) from `fim_categorie`");
        $last_category_id = $last_category_id_query->fetch();

        //по этому id выбираем фильм
        $last_category_query = $pdo->prepare("SELECT * from `fim_categorie` WHERE `id` = :id");
        $last_category_query->bindValue(':id', $last_category_id['max(`id`)']);
        $last_category_query->execute();

        $last_category = $last_category_query->fetch();


        //Здесь мне нужно было вернуть на клиент "настоящее" текущее значение автоинкремента id
        //Например, два фильма из базы удалили, теперь последний id в базе 10, а следующий фильм который добавим
        //должен иметь значение 13(автоинкремент не уменьшается)
        $autoincrement_data = $pdo->query("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE
            TABLE_SCHEMA = 'id15064873_cinema_db' AND TABLE_NAME = 'fim_categorie'");

        $autoincrement  = $autoincrement_data->fetch();
        $last_category['next_id'] = $autoincrement['AUTO_INCREMENT'];//Добавляю значение автоинкремента в массив

        //Возвращаем последний фильм в виде json
        echo json_encode($last_category);

    }
    public function GetAll($pdo){
        //Выбираем все фильмы из базы
        $all_categories_query = $pdo->query("Select * from `fim_categorie`");

        //Массив, который отправим в виде json в ответе
        $all_categories = array();

        while ($category = $all_categories_query->fetch()) {

            array_push($all_categories, $category);//складываем фильм в массив
        }

        //Делаем json из массива
        $all_categories_json = json_encode($all_categories, JSON_UNESCAPED_UNICODE);

        if ($all_categories_json === false) {
            // Avoid echo of empty string (which is invalid JSON), and
            // JSONify the error message instead:
            $all_categories_json = json_encode(array("jsonError", json_last_error_msg()));
            if ($all_categories_json === false) {
                // This should not happen, but we go all the way now:
                $all_categories_json = '{"jsonError": "unknown"}';
            }
            // Set HTTP response status code to: 500 - Internal Server Error
            http_response_code(500);
        }
        echo $all_categories_json;
    }
    public function Delete($pdo)
    {
        $category_id = $_POST['id'];

        //Проверяем новый это фильм или такой уже есть
        $category_exist = $pdo->prepare("SELECT `id` FROM `fim_categorie` WHERE `id` = :film_id");
        $category_exist->bindValue(':film_id', $category_id);
        $category_exist->execute();
        if($category_exist->rowCount() > 0) {

            $select_films_images_query = $pdo->prepare("SELECT `image` FROM `films` WHERE `categorie_id` = :category_id");
            $select_films_images_query->bindValue(':category_id', $category_id);
            $select_films_images_query->execute();

            while($selected_film_image = $select_films_images_query->fetch())
            {
                if(file_exists($selected_film_image['image']))
                    unlink($selected_film_image['image']);
            }
            $category_films_query = $pdo -> prepare("DELETE FROM `films` WHERE `categorie_id` = :category_id");
            $category_films_query->bindValue(':category_id', $category_id);
            $category_films_query->execute();


            $delete_category_query = $pdo->prepare("DELETE FROM `fim_categorie` WHERE `id` = :category_id");
            $delete_category_query->bindValue(':category_id', $category_id);
            $delete_category_query->execute();

        }
        else{
            $error_json['error'] = "Category don't exist";
            http_response_code(500);
            echo json_encode($error_json);
            exit();
        }

    }
}