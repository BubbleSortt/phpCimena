<?php

$config = array(
    'title' => 'ФильмоПоиск',
    'db' => array(
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'name' => 'cinema_db',
    ),
);

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