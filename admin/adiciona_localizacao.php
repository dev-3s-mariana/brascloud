<?php
if($_REQUEST) {
	@session_start();
	$dados = $_REQUEST;
	if(count($dados)>0) {
		$_SESSION['localizacao']['tempo'] = $dados['tempo'];
		$_SESSION['localizacao']['cidade'] = $dados['cidade'];
		$_SESSION['localizacao']['min'] = $dados['min'];
		$_SESSION['localizacao']['max'] = $dados['max'];
		echo '{"status" : true}';
	} else {
		echo '{"status" : false}';
	}
}
?>
