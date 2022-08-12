<?php

    $host = "localhost";

    $user = "web_sites";
    $password = "NMzK0T&13bf;";
    //NOME DATABASE DO PROJETO
    $database = "web_brascloud";

    $ENDERECO = 'https://'.$_SERVER['HTTP_HOST'].'/brascloud/admin/';

    $conexao = mysqli_connect($host, $user, $password) or exit(mysqli_connect_error());

    mysqli_select_db($conexao, $database) || exit(mysqli_error($conexao));

    defined('ENDERECO') || define('ENDERECO', $ENDERECO);
