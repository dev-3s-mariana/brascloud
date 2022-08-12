<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_PRODUTOS") || define("CADASTRO_PRODUTOS","cadastroSuporte");
defined("EDIT_PRODUTOS") || define("EDIT_PRODUTOS","editSuporte");
defined("DELETA_PRODUTOS") || define("DELETA_PRODUTOS","deletaSuporte");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_PRODUTOS:
		include_once 'suporte_class.php';

		$dados = $_REQUEST;
		$idSuporte = cadastroSuporte($dados);
		
		if (is_int($idSuporte)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'suporte';
			$log['descricao'] = 'Cadastrou suporte ID('.$idSuporte.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemalerta='.urlencode('Suporte criado com sucesso!'));
		} else {
			header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemerro='.urlencode('ERRO ao criar novo Suporte!'));
		}

	break;

	case EDIT_PRODUTOS:
		include_once 'suporte_class.php';

		$dados = $_REQUEST;
		$antigo = buscaSuporte(array('idsuporte'=>$dados['idsuporte']));
		$antigo = $antigo[0];

		$idSuporte = editSuporte($dados);

		if ($idSuporte != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'suporte';
			$log['descricao'] = 'Editou suporte ID('.$idSuporte.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemalerta='.urlencode('Suporte salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemerro='.urlencode('ERRO ao salvar Suporte!'));
		}

	break;

	case DELETA_PRODUTOS:
		include_once 'suporte_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('suporte_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaSuporte(array('idsuporte'=>$dados['idu']));

			if (deletaSuporte($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'suporte';
				$log['descricao'] = 'Deletou suporte ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemalerta='.urlencode('Suporte deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=suporte&acao=listarSuporte&mensagemerro='.urlencode('ERRO ao deletar Suporte!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("suporte_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $suporte = buscaSuporte(array('idsuporte'=>$dados['idsuporte']));
			$suporte = $suporte[0];

            $ordemAux = 0;
			$ordem = $suporte['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $suporteAux = buscaSuporte(array('ordenacao'=>$ordem));
				 if(!empty($suporteAux)){
					$suporteAux = $suporteAux[0];
				 	$ordemAux = $suporteAux['ordem'];
				 }
			}
			if(!empty($suporteAux)){
				$suporteAux['ordem'] = $suporte['ordem'];
				$suporte['ordem'] = $ordemAux;
				editSuporte($suporte);
				editSuporte($suporteAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("suporte_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$suporte = buscaSuporte(array('idsuporte'=>$dados['idsuporte']));
 			$suporte = $suporte[0];
			$ordemAux = 0;
			$ordem = $suporte['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $suporteAux = buscaSuporte(array('ordenacao'=>$ordem));
				 if(!empty($suporteAux)){
				 	$suporteAux = $suporteAux[0];
					$ordemAux = $suporteAux['ordem'];
				 }
			}
			if(!empty($suporteAux)){

				$suporteAux['ordem'] = $suporte['ordem'];
				$suporte['ordem'] = $ordemAux;

				editSuporte($suporte);
				editSuporte($suporteAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("suporte_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'suporte';
      $id = 'idsuporte';

      try {
         $suporte = buscaSuporte(array('idsuporte' => $dados['idsuporte']));
         $suporte = $suporte[0];

         // print_r($suporte);
         if($suporte['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idsuporte'] = $dados['idsuporte'];
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