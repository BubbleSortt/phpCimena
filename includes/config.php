<?php

$config = array(
    'title' => 'ФильмоПоиск',
    'db' => array(
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'name' => 'cinema_db',
    ),
    'vk' => array(
        'app_id' => '7705739',
        'url_script' => 'http://cinema.loc/Login/signinVk.php',
        'secret_key' => 't1RWhVZxLq9UKaWOfKvs'
    )
);
$vk_auth_link =
//Development

/*
$config = array(
    'title' => 'ФильмоПоиск',
    'db' => array(
        'server' => 'localhost',
        'username' => 'id15064873_makesh_db',
        'password' => '?6&()pnQ/([~SLQ8',
        'name' => 'id15064873_cinema_db',
    ),
);
//Production
*/
require "db.php";