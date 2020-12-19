<?php

require_once 'CategoryCRUD.php';

//Проверяем в строке запроса значение action
if (isset($_REQUEST['action'])) {
    //Заголовки необходимые для того чтобы вернуть json, и установить доступ для всех адресов
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    $category_controller = new CategoryCRUD();
    //Проверяем action и вызываем функцию
    switch ($_REQUEST['action']) {
        case 'add':
            $category_controller->Add($pdo);
            break;
        case 'getLast':
            $category_controller->GetLast($pdo);
            break;
        case 'getAll':
            $category_controller->GetAll($pdo);
            break;
        case 'delete':
            $category_controller->Delete($pdo);
            break;
        default:
            //Возвращаем ошибку, если action не стандартный
            $error_json['error'] = "unknown action";
            http_response_code(500);
            echo json_encode($error_json);
            break;
    }
} else {
    //Если action вообще не пришел так же возвращаем ошибку
    $error_json['error'] = "undefined action";
    http_response_code(500);
    echo json_encode($error_json);
    exit();
}


