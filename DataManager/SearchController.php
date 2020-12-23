<?php
require_once 'Search.php';

//Проверяем в строке запроса значение action
if(isset($_REQUEST['action']) && $_REQUEST['action'] == "search") {
    //Заголовки необходимые для того чтобы вернуть json, и установить доступ для всех адресов
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');

    $searcher = new Search();
    $searcher->find($pdo);
    //Проверяем action и вызываем функцию
}
