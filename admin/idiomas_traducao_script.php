<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_IDIOMAS_TRADUCAO") || define("CADASTRO_IDIOMAS_TRADUCAO","cadastroIdiomas_traducao");
defined("EDIT_IDIOMAS_TRADUCAO") || define("EDIT_IDIOMAS_TRADUCAO","editIdiomas_traducao");
defined("DELETA_IDIOMAS_TRADUCAO") || define("DELETA_IDIOMAS_TRADUCAO","deletaIdiomas_traducao");

defined("CADASTRAR_TRADUCAO") || define("CADASTRAR_TRADUCAO","cadastrarTraducao");
defined("EXCLUIR_TAG") || define("EXCLUIR_TAG","excluirTag");


switch ($opx) {

	case CADASTRO_IDIOMAS_TRADUCAO:
		include_once 'idiomas_traducao_class.php';
		include_once 'idiomas_class.php';
		include_once 'includes/functions.php';

		$dados = $_REQUEST; 

		//converte a tag para url
		$tag = converteTag(utf8_encode($dados['tag'])); 
		$dados['tag'] = $tag; 

		//verificar se a tag nao existe para o mesmo idioma
		$vTag = buscaIdiomas_traducao(array('tag'=>$tag));
	 	
	 	if(!empty($vTag)){
	 		print '{"status":false,"msg":"Tag já cadastrada"}';
	 	}else{	 

	 		$texto = $dados['texto'];
	 		$idiomas = buscaIdiomas();

	 		foreach ($idiomas as $key => $value) {
	 			$dados['texto'] = "";
	 			if($value['ididiomas'] == 1){
	 				$dados['texto'] = $texto;
	 			}
	 			$dados['ididiomas'] = $value['ididiomas'];
	 			$idIdiomas_traducao = cadastroIdiomas_traducao($dados);
	 	 	}   
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'idiomas_traducao';
			$log['descricao'] = 'Cadastrou a tag ('.$dados['tag'].')';
			$log['request'] = $_REQUEST;
			novoLog($log); 

			$retorno['status'] = true;
			$retorno['tag'] = $tag;
			$retorno['texto'] = $texto;

			print json_encode($retorno);
		}

	break;

	case CADASTRAR_TRADUCAO:
		include_once 'idiomas_traducao_class.php';
		include_once 'idiomas_class.php'; 

		$dados = $_REQUEST;  

		$idtraducao = editIdiomas_traducao($dados);
 		
 		if($idtraducao){ 
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'idiomas_traducao';
			$log['descricao'] = 'Editou a tag ('.$dados['tag'].') para texto('.$dados['texto'].')';
			$log['request'] = $_REQUEST;
			novoLog($log); 
			print '{"status":true}';
		}else{ 
			print '{"status":false,"msg":"Erro ao editar o texto, tente novamente."}';
		}

	break;

	case EDIT_IDIOMAS_TRADUCAO:
		include_once 'idiomas_traducao_class.php';

		$dados = $_REQUEST;
		$antigo = buscaIdiomas_traducao(array('ididiomas_traducao'=>$dados['ididiomas_traducao']));
		$antigo = $antigo[0];

		$idIdiomas_traducao = editIdiomas_traducao($dados);

		if ($idIdiomas_traducao != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'idiomas_traducao';
			$log['descricao'] = 'Editou idiomas_traducao ID('.$idIdiomas_traducao.') DE  ididiomas ('.$antigo['ididiomas'].') tag ('.$antigo['tag'].') texto ('.$antigo['texto'].') PARA  ididiomas ('.$dados['ididiomas'].') tag ('.$dados['tag'].') texto ('.$dados['texto'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=idiomas_traducao&acao=listarIdiomas_traducao&mensagemalerta='.urlencode('Idiomas_traducao salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=idiomas_traducao&acao=listarIdiomas_traducao&mensagemerro='.urlencode('ERRO ao salvar Idiomas_traducao!'));
		}

	break;

	case DELETA_IDIOMAS_TRADUCAO:
		include_once 'idiomas_traducao_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('idiomas_traducao_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=idiomas_traducao&acao=listarIdiomas_traducao&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaIdiomas_traducao(array('ididiomas_traducao'=>$dados['idu']));

			if (deletaIdiomas_traducao($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'idiomas_traducao';
				$log['descricao'] = 'Deletou idiomas_traducao ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=idiomas_traducao&acao=listarIdiomas_traducao&mensagemalerta='.urlencode('Idiomas_traducao deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=idiomas_traducao&acao=listarIdiomas_traducao&mensagemerro='.urlencode('ERRO ao deletar Idiomas_traducao!'));
			}
		}

	break;

	case EXCLUIR_TAG:
		include_once 'idiomas_traducao_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('idiomas_traducao_deletar', $_SESSION['sgc_idusuario'])){
			print '{"status":false, "msg":"Voce nao tem privilegios para executar esta ação"}';
		} else {
			$dados = $_POST; 
			$antigo = buscaIdiomas_traducao(array('tag'=>$dados['tag'],"ididiomas"=>1));

			if (deletaIdiomas_traducao($dados['tag'])) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'idiomas_traducao';
				$log['descricao'] = 'Deletou idiomas_traducao Tag('.$dados['tag'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				print '{"status":true, "msg":"Tag deletada com sucesso"}';
			} else {
				print '{"status":false, "msg":"Erro ao deletar a tag!"}';
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