<?php @session_start();
$opx = $_REQUEST["opx"];
switch($opx) {
  
 
  case "novaPermissao":	
		include_once("permissao_class.php");
		 
		$dados = $_POST; 

		$permissoes = '';
		foreach($dados as $k=>$v){
			if($v == 'permitido')
			$permissoes .= ' '.$k;	
		}
	   
		
		$tmp['apelido'] = strtolower($dados['apelido']);
		$tmp['permissoes'] = trim($permissoes);
		
		$dados = $tmp;
		
		
		$idpermissao = novaPermissao($dados);
		
		include_once('log_class.php');
		$log['idusuario'] = $_SESSION['sgc_idusuario'];
		$log['modulo'] = 'permissao';
		$log['descricao'] = 'Criou permissao ('.$dados['apelido'].') ID ('.$idpermissao.'), permissoes ('.$dados['permissoes'].')';		
		$log['request'] = $_REQUEST;
		novoLog($log);
		
		header('Location: index.php?mod=permissao&acao=listarPermissao&mensagemalerta='.urlencode('Permissão Criada com Sucesso!'));
	
  break;
  
  
  case "editPermissao":	
		include_once("permissao_class.php");
		 
		$dados = $_POST; 

		$resultado = buscaPermissao(array('idpermissao'=>$dados['idpermissao']));
		$resultado = $resultado[0];
		
		
		$permissoes = '';
		foreach($dados as $k=>$v){
			if($v == 'permitido')
			$permissoes .= ' '.$k;	
		}
	   
		$tmp['idpermissao'] = $dados['idpermissao'];
		$tmp['apelido'] = strtolower($dados['apelido']);
		$tmp['permissoes'] = trim($permissoes);
		
		$dados = $tmp;
		
		
		editPermissao($dados);
		
		include_once('log_class.php');
		$log['idusuario'] = $_SESSION['sgc_idusuario'];
		$log['modulo'] = 'permissao';
		$log['descricao'] = 'Editou permissao ('.$resultado['apelido'].') ID ('.$resultado['idpermissao'].'), permissoes ('.$resultado['tags'].') PARA ('.$dados['apelido'].') ID ('.$dados['idpermissao'].'), permissoes ('.$dados['permissoes'].')';		
		$log['request'] = $_REQUEST;
		novoLog($log);
		
		header('Location: index.php?mod=permissao&acao=listarPermissao&mensagemalerta='.urlencode('Permissão Salva com Sucesso!'));
	
  break;  
  
  
  case "deletaPermissao":	
		include_once("permissao_class.php");
		
		$dados = $_REQUEST;
		
		$resultado = buscaPermissao(array('idpermissao'=>$dados['idu']));
		$permissao = $resultado[0];
		
		
		deletaPermissao($dados['idu']);
		
		//salva log
		include_once('log_class.php');
		 $log['idusuario'] = $_SESSION['sgc_idusuario'];
		 $log['modulo'] = 'permissao';
		 $log['descricao'] = 'Excluiu permissao ('.$permissao['apelido'].')';		
		 $log['request'] = $_REQUEST;
		 novoLog($log);
		 
		 header('Location: index.php?mod=permissao&acao=listarPermissao&mensagemalerta='.urlencode('Permissão Deletada com Sucesso!'));

  break;    
  
}

?>