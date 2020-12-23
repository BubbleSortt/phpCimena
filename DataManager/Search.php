<?php
require_once '../includes/db.php';

Interface ISearcher
{
    public function search($pdo);
}
class Search
{
    public function find($pdo)
    {

        if(!empty($_POST["referal"]))
        {
            $referal = trim(strip_tags(stripcslashes(htmlspecialchars($_POST["referal"]))));
            $all_films = array();

            $count_films_query = $pdo->prepare("SELECT COUNT(*) FROM `films` WHERE `title` LIKE :referal");
            $count_films_query->bindValue(':referal', "%$referal%");
            $count_films_query->execute();
            if($count_films_query->fetchColumn() > 0)
            {
                $select_films_query = $pdo->prepare("SELECT * FROM `films` WHERE `title` LIKE :referal");
                $select_films_query->bindValue(':referal', "%$referal%");
                $select_films_query->execute();

                while ($film = $select_films_query->fetch()) {

                    //Нам нужны id в виде строк, а не цифр, поэтому обращаемся к таблице с id и вытаскиваем их строковый вид
                    $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
                    $film_category_query->bindValue(':categorie_id', $film['categorie_id']);
                    $film_category_query->execute();
                    $film_category = $film_category_query->fetch();
                    //меняем у фильма цифровой id на строковый.
                    $film['categorie_id'] = $film_category['categorie_title'];
                    array_push($all_films, $film);//складываем фильм в массив
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
            else
            {
                //$referal = strtolower($referal);
                $select_category_query = $pdo->prepare("SELECT * FROM `fim_categorie` WHERE `categorie_title` LIKE :referal");
                $select_category_query->bindValue(':referal', "%$referal%");
                $select_category_query->execute();
                while($film_category = $select_category_query->fetch())
                {
                    $select_films_query = $pdo->prepare("SELECT * FROM `films` WHERE `categorie_id` = :id");
                    $select_films_query->bindValue(':id', $film_category['id']);
                    $select_films_query->execute();

                    while ($film = $select_films_query->fetch()) {

                        //Нам нужны id в виде строк, а не цифр, поэтому обращаемся к таблице с id и вытаскиваем их строковый вид
                        $film_category_query = $pdo->prepare("Select * from `fim_categorie` where `id` = :categorie_id");
                        $film_category_query->bindValue(':categorie_id', $film['categorie_id']);
                        $film_category_query->execute();
                        $film_category = $film_category_query->fetch();
                        //меняем у фильма цифровой id на строковый.
                        $film['categorie_id'] = $film_category['categorie_title'];
                        array_push($all_films, $film);//складываем фильм в массив
                    }
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
        }
    }

}