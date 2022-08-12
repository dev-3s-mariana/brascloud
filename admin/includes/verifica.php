<?php
	session_start();
	$chavex = isset($_SESSION["sgc_BRASCLOUDeRlE4QsKAEOr2KOFcUce"]) ? $_SESSION["sgc_BRASCLOUDeRlE4QsKAEOr2KOFcUce"] : '';
	
	if((empty($chavex)) || ($chavex != "BRASCLOUDZ7TEcCCseZMKLC6xpNxf")){
		header("location:login.php?msg=".urlencode('Acesso Negado!'));
		exit;
	}else{
		include_once 'usuario_class.php';		
		salvaUltimaAcao($_SESSION['sgc_idusuario']);
				
		$tmpQuery = explode('&', str_replace('mod=', '', $_SERVER['QUERY_STRING']));
		$MODULOACESSO['modulo'] = $tmpQuery[0];
		$MODULOACESSO['usuario'] = $_SESSION['sgc_idusuario'];
	}
?>