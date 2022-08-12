<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_CONTATOS") || define("CADASTRO_CONTATOS","cadastroContatos");
defined("EDIT_CONTATOS") || define("EDIT_CONTATOS","editContatos");
defined("DELETA_CONTATOS") || define("DELETA_CONTATOS","deletaContatos");

switch ($opx) {

	case CADASTRO_CONTATOS:
		include_once 'contatos_class.php';

		$dados = $_REQUEST;
		$idContatos = cadastroContatos($dados);

		if (is_int($idContatos)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'contatos';
			$log['descricao'] = 'Cadastrou contatos ID('.$idContatos.') nome ('.$dados['nome'].') email ('.$dados['email'].') ';
			$log['request'] = $_REQUEST;
			novoLog($log);
			$status = true;
			$msg = "Contato enviado com sucesso, em breve entraremos em contato!";
			//header('Location: index.php?mod=contatos&acao=listarContatos&mensagemalerta='.urlencode('Contatos criado com sucesso!'));
		} else {
			$status = false;
			$msg = "falha ao enviar mensagem!";
			
			//header('Location: index.php?mod=contatos&acao=listarContatos&mensagemerro='.urlencode('ERRO ao criar novo Contatos!'));
		}
		die(json_encode(array('status'=>$status,'msg'=>$msg)));
	break;

	case EDIT_CONTATOS:
		include_once 'contatos_class.php';

		$dados = $_REQUEST;
		$antigo = buscaContatos(array('idcontatos'=>$dados['idcontatos']));
		$antigo = $antigo[0];

		$idContatos = editContatos($dados);

		if ($idContatos != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'contatos';
			$log['descricao'] = 'Editou contatos ID('.$idContatos.') DE  nome ('.$antigo['nome'].') email ('.$antigo['email'].') telefone ('.$antigo['telefone'].')   PARA  nome ('.$dados['nome'].') email ('.$dados['email'].') telefone ('.$dados['telefone'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=contatos&acao=listarContatos&mensagemalerta='.urlencode('Contatos salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=contatos&acao=listarContatos&mensagemerro='.urlencode('ERRO ao salvar Contatos!'));
		}

	break;

	case DELETA_CONTATOS:
		include_once 'contatos_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('contatos_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=contatos&acao=listarContatos&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaContatos(array('idcontatos'=>$dados['idu']));

			if (deletaContatos($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'contatos';
				$log['descricao'] = 'Deletou contatos ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=contatos&acao=listarContatos&mensagemalerta='.urlencode('Contatos deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=contatos&acao=listarContatos&mensagemerro='.urlencode('ERRO ao deletar Contatos!'));
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