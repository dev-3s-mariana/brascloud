<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_CONTRATAR") || define("CADASTRO_CONTRATAR","cadastroContratar");
defined("EDIT_CONTRATAR") || define("EDIT_CONTRATAR","editContratar");
defined("DELETA_CONTRATAR") || define("DELETA_CONTRATAR","deletaContratar");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_CONTRATAR:
		include_once 'contratar_class.php';

		$dados = $_REQUEST;
		$idContratar = cadastroContratar($dados);
		
		if (is_int($idContratar)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'contratar';
			$log['descricao'] = 'Cadastrou contratar ID('.$idContratar.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=contratar&acao=listarContratar&mensagemalerta='.urlencode('Contratar criado com sucesso!'));
		} else {
			header('Location: index.php?mod=contratar&acao=listarContratar&mensagemerro='.urlencode('ERRO ao criar novo Contratar!'));
		}

	break;

	case EDIT_CONTRATAR:
		include_once 'contratar_class.php';

		$dados = $_REQUEST;
		$antigo = buscaContratar(array('idcontratar'=>$dados['idcontratar']));
		$antigo = $antigo[0];

		$idContratar = editContratar($dados);

		if ($idContratar != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'contratar';
			$log['descricao'] = 'Editou contratar ID('.$idContratar.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=contratar&acao=listarContratar&mensagemalerta='.urlencode('Contratar salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=contratar&acao=listarContratar&mensagemerro='.urlencode('ERRO ao salvar Contratar!'));
		}

	break;

	case DELETA_CONTRATAR:
		include_once 'contratar_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('contratar_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=contratar&acao=listarContratar&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaContratar(array('idcontratar'=>$dados['idu']));

			if (deletaContratar($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'contratar';
				$log['descricao'] = 'Deletou contratar ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=contratar&acao=listarContratar&mensagemalerta='.urlencode('Contratar deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=contratar&acao=listarContratar&mensagemerro='.urlencode('ERRO ao deletar Contratar!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("contratar_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $contratar = buscaContratar(array('idcontratar'=>$dados['idcontratar']));
			$contratar = $contratar[0];

            $ordemAux = 0;
			$ordem = $contratar['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $contratarAux = buscaContratar(array('ordenacao'=>$ordem));
				 if(!empty($contratarAux)){
					$contratarAux = $contratarAux[0];
				 	$ordemAux = $contratarAux['ordem'];
				 }
			}
			if(!empty($contratarAux)){
				$contratarAux['ordem'] = $contratar['ordem'];
				$contratar['ordem'] = $ordemAux;
				editContratar($contratar);
				editContratar($contratarAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("contratar_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$contratar = buscaContratar(array('idcontratar'=>$dados['idcontratar']));
 			$contratar = $contratar[0];
			$ordemAux = 0;
			$ordem = $contratar['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $contratarAux = buscaContratar(array('ordenacao'=>$ordem));
				 if(!empty($contratarAux)){
				 	$contratarAux = $contratarAux[0];
					$ordemAux = $contratarAux['ordem'];
				 }
			}
			if(!empty($contratarAux)){

				$contratarAux['ordem'] = $contratar['ordem'];
				$contratar['ordem'] = $ordemAux;

				editContratar($contratar);
				editContratar($contratarAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("contratar_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'contratar';
      $id = 'idcontratar';

      try {
         $contratar = buscaContratar(array('idcontratar' => $dados['idcontratar']));
         $contratar = $contratar[0];

         // print_r($contratar);
         if($contratar['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idcontratar'] = $dados['idcontratar'];
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