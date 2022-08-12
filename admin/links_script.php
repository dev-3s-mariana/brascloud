<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_LINKS") || define("CADASTRO_LINKS","cadastroLinks");
defined("EDIT_LINKS") || define("EDIT_LINKS","editLinks");
defined("DELETA_LINKS") || define("DELETA_LINKS","deletaLinks");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_LINKS:
		include_once 'links_class.php';

		$dados = $_REQUEST;
		$idLinks = cadastroLinks($dados);
		
		if (is_int($idLinks)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'links';
			$log['descricao'] = 'Cadastrou links ID('.$idLinks.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=links&acao=listarLinks&mensagemalerta='.urlencode('Links criado com sucesso!'));
		} else {
			header('Location: index.php?mod=links&acao=listarLinks&mensagemerro='.urlencode('ERRO ao criar novo Links!'));
		}

	break;

	case EDIT_LINKS:
		include_once 'links_class.php';

		$dados = $_REQUEST;
		$antigo = buscaLinks(array('idlinks'=>$dados['idlinks']));
		$antigo = $antigo[0];

		$idLinks = editLinks($dados);

		if ($idLinks != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'links';
			$log['descricao'] = 'Editou links ID('.$idLinks.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=links&acao=listarLinks&mensagemalerta='.urlencode('Links salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=links&acao=listarLinks&mensagemerro='.urlencode('ERRO ao salvar Links!'));
		}

	break;

	case DELETA_LINKS:
		include_once 'links_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('links_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=links&acao=listarLinks&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaLinks(array('idlinks'=>$dados['idu']));

			if (deletaLinks($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'links';
				$log['descricao'] = 'Deletou links ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=links&acao=listarLinks&mensagemalerta='.urlencode('Links deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=links&acao=listarLinks&mensagemerro='.urlencode('ERRO ao deletar Links!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("links_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $links = buscaLinks(array('idlinks'=>$dados['idlinks']));
			$links = $links[0];

            $ordemAux = 0;
			$ordem = $links['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $linksAux = buscaLinks(array('ordenacao'=>$ordem));
				 if(!empty($linksAux)){
					$linksAux = $linksAux[0];
				 	$ordemAux = $linksAux['ordem'];
				 }
			}
			if(!empty($linksAux)){
				$linksAux['ordem'] = $links['ordem'];
				$links['ordem'] = $ordemAux;
				editLinks($links);
				editLinks($linksAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("links_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$links = buscaLinks(array('idlinks'=>$dados['idlinks']));
 			$links = $links[0];
			$ordemAux = 0;
			$ordem = $links['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $linksAux = buscaLinks(array('ordenacao'=>$ordem));
				 if(!empty($linksAux)){
				 	$linksAux = $linksAux[0];
					$ordemAux = $linksAux['ordem'];
				 }
			}
			if(!empty($linksAux)){

				$linksAux['ordem'] = $links['ordem'];
				$links['ordem'] = $ordemAux;

				editLinks($links);
				editLinks($linksAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("links_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'links';
      $id = 'idlinks';

      try {
         $links = buscaLinks(array('idlinks' => $dados['idlinks']));
         $links = $links[0];

         // print_r($links);
         if($links['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idlinks'] = $dados['idlinks'];
         $dadosUpdate['status'] = $status;
         inverteStatus($dadosUpdate,$tabela,$id);

         print json_encode($resultado);
      } catch (Exception $e) {
         $resultado['status'] = 'falha';
         print json_encode($resultado);
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