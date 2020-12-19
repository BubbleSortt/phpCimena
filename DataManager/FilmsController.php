<?php
    require_once 'FilmsCRUD.php';

    //Проверяем в строке запроса значение action
    if(isset($_REQUEST['action']))
    {
        //Заголовки необходимые для того чтобы вернуть json, и установить доступ для всех адресов
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=utf-8');

        $film_controller = new FilmsCRUD();
        //Проверяем action и вызываем функцию
        switch($_REQUEST['action'])
        {
            case 'add':
                $film_controller->AddFilm($pdo);
                break;
            case 'getLast':
                $film_controller->GetLastAdedd($pdo);
                break;
            case 'getAll':
                $film_controller->GetAllFilms($pdo);
                break;
            case 'delete':
                $film_controller->DeleteFilm($pdo);
                break;
            default:
                //Возвращаем ошибку, если action не стандартный
                $error_json['error']= "unknown action";
                http_response_code(500);
                echo json_encode($error_json);
                break;
        }
    }
    else {
        //Если action вообще не пришел так же возвращаем ошибку
        $error_json['error'] = "undefined action";
        http_response_code(500);
        echo json_encode($error_json);
        exit();
    }


?>