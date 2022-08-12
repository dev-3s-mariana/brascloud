<?php 
// @ob_start();
// @session_start();
// Versao do modulo: 3.00.010416

// require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_TRABALHE_CONOSCO") || define("CADASTRO_TRABALHE_CONOSCO","cadastroTrabalhe_conosco");
defined("EDIT_TRABALHE_CONOSCO") || define("EDIT_TRABALHE_CONOSCO","editTrabalhe_conosco");
defined("DELETA_TRABALHE_CONOSCO") || define("DELETA_TRABALHE_CONOSCO","deletaTrabalhe_conosco");

switch ($opx) {

	case CADASTRO_TRABALHE_CONOSCO:
		include_once 'trabalhe_conosco_class.php';
		include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$curriculum = $_FILES;
		$nomeArquivo = "";
		$HTTP = explode('/', $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);	
		// if (!empty($_SERVER['HTTPS'])){
		// 	define('ENDERECO', 'https://'.$HTTP[0].'/'.$HTTP[1].'/');
		// }else{
		// 	define('ENDERECO', 'http://'.$HTTP[0].'/'.$HTTP[1].'/');
		// }
	 	if(isset($curriculum['arquivo']['name']) && $curriculum['arquivo']['error'] == 0){
	 		$curriculum = $curriculum['arquivo'];
	 		$nome = $curriculum['name'];
	 		$nome = explode(".",$nome);	
	 		$ext = $nome[count($nome) - 1];
	 		$nomeArquivo = converteUrl($nome[0]).".".$ext;	
	 		$_FILES['arquivo']['name'] = $nomeArquivo;  
	 	} 
		$dados['arquivo'] = "";
		//$dados['telefone'] = trim($dados['ddd']).str_replace("-", "", trim($dados['telefone']));
		$idTrabalhe_conosco = cadastroTrabalhe_conosco($dados); 

		if (is_int($idTrabalhe_conosco)) {

			if(!empty($nomeArquivo)){
				$nomeArquivo = "files/curriculum/".$idTrabalhe_conosco.$nomeArquivo;
				if(!file_exists("files/curriculum/")){
					mkdir("files/curriculum/",0777);
				}  

				$opx = "trabalhe-conosco";
				$_REQUEST["opx"] = $opx;
				$link = ENDERECO.$nomeArquivo;
				include_once("email_script.php"); 

				if(move_uploaded_file($curriculum['tmp_name'], $nomeArquivo)){ 
					$dados['arquivo'] = $nomeArquivo;
					$dados['idtrabalhe_conosco'] = $idTrabalhe_conosco;  
					$edit = editTrabalhe_conosco($dados); 
				} 
			}
			// if(isset($dados['ajax'])){    
				// $_SESSION['msgtrabalhe'] = true;
				// header('Location:'.ENDERECO_IDIOMA.'careers'); 
				// echo '{"status" : true}';
			// }
			// else{ 
			// 	//salva log
			// 	include_once 'log_class.php';
			// 	$log['idusuario'] = $_SESSION['sgc_idusuario'];
			// 	$log['modulo'] = 'trabalhe_conosco';
			// 	$log['descricao'] = 'Cadastrou trabalhe_conosco ID('.$idTrabalhe_conosco.') nome ('.$dados['nome'].') email ('.$dados['email'].') telefone ('.$dados['telefone'].') interesse ('.$dados['interesse'].') arquivo ('.$dados['arquivo'].') data_hora ('.$dados['data_hora'].') ip ('.$dados['ip'].')';
			// 	$log['request'] = $_REQUEST;
			// 	novoLog($log);
			// 	header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemalerta='.urlencode('Currículo criado com sucesso!'));
			// }	
		} 
		// else if(isset($dados['ajax'])){
			// $_SESSION['msgtrabalhe'] = false;	
			// header('Location:'.ENDERECO_IDIOMA."/careers");
			// echo '{"status" : false}';
		// }
		// else{	
		// 	header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemerro='.urlencode('ERRO ao criar novo currículo!'));
		// }
	break;

	case EDIT_TRABALHE_CONOSCO:
		include_once 'trabalhe_conosco_class.php';
		include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$curriculum = $_FILES;
		$nomeArquivo = "";

		$antigo = buscaTrabalhe_conosco(array('idtrabalhe_conosco'=>$dados['idtrabalhe_conosco']));
		$antigo = $antigo[0]; 

		$dados['telefone'] = str_replace(array(" ","(",")","-"), "", trim($dados['telefone']));
		
		if(isset($curriculum['arquivo']['name']) && $curriculum['arquivo']['error'] == 0){
	 		$curriculum = $curriculum['arquivo'];
	 		$nome = $curriculum['name'];
	 		$nome = explode(".",$nome);	
	 		$ext = $nome[count($nome) - 1];
	 		$nomeArquivo = converteUrl($nome[0]).".".$ext; 
	 	}

	 	$dados['arquivo'] = $antigo['arquivo']; 

		$idTrabalhe_conosco = editTrabalhe_conosco($dados);

		if ($idTrabalhe_conosco != FALSE) {

			if(!empty($nomeArquivo)){
				$nomeArquivo = "files/curriculum/".$idTrabalhe_conosco.$nomeArquivo;
				if(!file_exists("files/curriculum/")){
					mkdir("files/curriculum/",0777);
				}

				if(move_uploaded_file($curriculum['tmp_name'], $nomeArquivo)){ 
					$dados['arquivo'] = $nomeArquivo;
					$edit = editTrabalhe_conosco($dados);  
				} 
			}

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'trabalhe_conosco';
			$log['descricao'] = 'Editou trabalhe_conosco ID('.$idTrabalhe_conosco.') DE  nome ('.$antigo['nome'].') email ('.$antigo['email'].') data_nascimento ('.$antigo['data_nascimento'].') sexo ('.$antigo['sexo'].') telefone ('.$antigo['telefone'].') interesse ('.$antigo['interesse'].') arquivo ('.$antigo['arquivo'].') data_hora ('.$antigo['data_hora'].') ip ('.$antigo['ip'].') PARA  nome ('.$dados['nome'].') email ('.$dados['email'].') data_nascimento ('.$dados['data_nascimento'].') sexo ('.$dados['sexo'].') telefone ('.$dados['telefone'].') interesse ('.$dados['interesse'].') arquivo ('.$dados['arquivo'].') data_hora ('.$dados['data_hora'].') ip ('.$dados['ip'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemalerta='.urlencode('Currículo salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemerro='.urlencode('ERRO ao salvar currículo!'));
		}

	break;

	case DELETA_TRABALHE_CONOSCO:
		include_once 'trabalhe_conosco_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('trabalhe_conosco_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaTrabalhe_conosco(array('idtrabalhe_conosco'=>$dados['idu']));

			if (deletaTrabalhe_conosco($dados['idu']) == 1) {
                $imgAntigo = $antigo[0]['arquivo'];
                deleteFiles('files/curriculum/', $imgAntigo, array(''));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'trabalhe_conosco';
				$log['descricao'] = 'Deletou trabalhe_conosco ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemalerta='.urlencode('Currículo deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco&mensagemerro='.urlencode('ERRO ao deletar currículo!'));
			}
		}

	break;

	default:
		if (!headers_sent() && (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
			header('Location: index.php?mod=home&mensagemerro='.urlencode('Nenhuma acao definida...'));
		} else {
			trigger_error('Erro...', E_USER_ERROR);
			exit;
		}
}
?>