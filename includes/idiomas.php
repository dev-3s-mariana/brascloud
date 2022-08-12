<?php
	include_once("admin/idiomas_class.php");
	include_once("admin/idiomas_traducao_class.php");
	$arrayIdiomas = buscaIdiomas(array("ordem"=>"ididiomas","dir"=>"asc","status"=>1));
	$idioma = buscaIdiomas(array("principal"=>1));
	$listaIdiomas = $arrayIdiomas; 

	if(!empty($_SESSION['modulo'])){
		$_idioma = buscaIdiomas(array("urlamigavel"=>$_SESSION['modulo']));
	 	if(empty($_idioma)){ 
			//está sem idioma  
			$link = ENDERECO.$idioma[0]['urlamigavel'];
			foreach($postArray as $k => $v){
				$link .= "/".$v;
			}   
 			// header("https/1.1 301 Moved Permanently");
			header("Location:".$link); 
		} else { 
			$idioma = $_idioma;  
		}
	}else {
		// header("https/1.1 301 Moved Permanently");
		header("Location:".ENDERECO.$idioma[0]['urlamigavel']); 
	} 
	
	$idioma = $idioma[0];
	$_SESSION['IDIOMA'] = $idioma['idioma'];
	$_SESSION['IDIOMA_URL'] = $idioma['urlamigavel'];
	$_SESSION['IDIDIOMA'] = $idioma['ididiomas'];
	$_SESSION['BANDEIRA'] = $idioma['iconbandeira'];
?>