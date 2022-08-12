<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_AREA_PRETENDIDA") || define("CADASTRO_AREA_PRETENDIDA","cadastroArea_pretendida");
defined("EDIT_AREA_PRETENDIDA") || define("EDIT_AREA_PRETENDIDA","editArea_pretendida");
defined("DELETA_AREA_PRETENDIDA") || define("DELETA_AREA_PRETENDIDA","deletaArea_pretendida");
defined("BUSCA_AREA_PRETENDIDA") || define("BUSCA_AREA_PRETENDIDA","buscaArea_pretendida");

switch ($opx) {

	case CADASTRO_AREA_PRETENDIDA:
		include_once 'area_pretendida_class.php';

		$dados = $_REQUEST;
		$idArea_pretendida = cadastroArea_pretendida($dados);

		if (is_int($idArea_pretendida)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'area_pretendida';
			$log['descricao'] = 'Cadastrou area_pretendida ID('.$idArea_pretendida.') nome ('.$dados['nome'].') email ('.$dados['email'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemalerta='.urlencode('Área Pretendida criado com sucesso!'));
		} else {
			header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemerro='.urlencode('ERRO ao criar novo Área Pretendida!'));
		}

	break;

	case EDIT_AREA_PRETENDIDA:
		include_once 'area_pretendida_class.php';

		$dados = $_REQUEST;
		$antigo = buscaArea_pretendida(array('idarea_pretendida'=>$dados['idarea_pretendida']));
		$antigo = $antigo[0];

		$idArea_pretendida = editArea_pretendida($dados);

		if ($idArea_pretendida != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'area_pretendida';
			$log['descricao'] = 'Editou area_pretendida ID('.$idArea_pretendida.') DE  nome ('.$antigo['nome'].') email ('.$antigo['email'].') status ('.$antigo['status'].') PARA  nome ('.$dados['nome'].') email ('.$dados['email'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemalerta='.urlencode('Área Pretendida salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemerro='.urlencode('ERRO ao salvar Área Pretendida!'));
		}

	break;

	case DELETA_AREA_PRETENDIDA:
		include_once 'area_pretendida_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('area_pretendida_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaArea_pretendida(array('idarea_pretendida'=>$dados['idu']));

			if (deletaArea_pretendida($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'area_pretendida';
				$log['descricao'] = 'Deletou area_pretendida ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemalerta='.urlencode('Área Pretendida deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=area_pretendida&acao=listarArea_pretendida&mensagemerro='.urlencode('ERRO ao deletar Área Pretendida!'));
			}
		}

	break;

	case BUSCA_AREA_PRETENDIDA:
		include_once 'area_pretendida_class.php';
		$dados = $_POST;
		$dados['ordem'] = "nome asc";
		$categorias = buscaArea_pretendida($dados);
		$retorno['dados'] = $categorias; 
		print json_encode($retorno);
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