<?php  
    setlocale(LC_TIME, 'pt_BR.utf8');

    include 'verifica.php';

    if(@!empty($MODULO) && @file_exists($MODULO.'_mod.php')){
        $endereco = $MODULO;
    }else{
        $endereco = 'home';
    }

    include 'includes/head.php';
?>