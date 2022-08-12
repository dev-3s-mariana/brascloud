<?php  											
$opx= $_REQUEST["opx"];
switch ( $opx ) {
  
 
  case "novoLog":	
		include_once("log_class.php");
		 
		 $dados = $_REQUEST;
		 $idLog = novoLog($dados);
	break;

}

?>