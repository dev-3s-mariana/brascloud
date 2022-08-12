<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_POLITICAS") || define("CADASTRO_POLITICAS","cadastroPoliticas");
defined("EDIT_POLITICAS") || define("EDIT_POLITICAS","editPoliticas");
defined("DELETA_POLITICAS") || define("DELETA_POLITICAS","deletaPoliticas");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_POLITICAS:
		include_once 'politicas_class.php';

		$dados = $_REQUEST;
		$idPoliticas = cadastroPoliticas($dados);
		
		if (is_int($idPoliticas)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'politicas';
			$log['descricao'] = 'Cadastrou politicas ID('.$idPoliticas.') pergunta ('.$dados['pergunta'].') respota ('.$dados['respota'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemalerta='.urlencode('Politicas criado com sucesso!'));
		} else {
			header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemerro='.urlencode('ERRO ao criar novo Politicas!'));
		}

	break;

	case EDIT_POLITICAS:
		include_once 'politicas_class.php';

		$dados = $_REQUEST;
		$antigo = buscaPoliticas(array('idpoliticas'=>$dados['idpoliticas']));
		$antigo = $antigo[0];

		$idPoliticas = editPoliticas($dados);

		if ($idPoliticas != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'politicas';
			$log['descricao'] = 'Editou politicas ID('.$idPoliticas.') DE  pergunta ('.$antigo['pergunta'].') respota ('.$antigo['respota'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  pergunta ('.$dados['pergunta'].') respota ('.$dados['respota'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemalerta='.urlencode('Politicas salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemerro='.urlencode('ERRO ao salvar Politicas!'));
		}

	break;

	case DELETA_POLITICAS:
		include_once 'politicas_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('politicas_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaPoliticas(array('idpoliticas'=>$dados['idu']));

			if (deletaPoliticas($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'politicas';
				$log['descricao'] = 'Deletou politicas ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemalerta='.urlencode('Politicas deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=politicas&acao=listarPoliticas&mensagemerro='.urlencode('ERRO ao deletar Politicas!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("politicas_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $politicas = buscaPoliticas(array('idpoliticas'=>$dados['idpoliticas']));
			$politicas = $politicas[0];

            $ordemAux = 0;
			$ordem = $politicas['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $politicasAux = buscaPoliticas(array('ordenacao'=>$ordem));
				 if(!empty($politicasAux)){
					$politicasAux = $politicasAux[0];
				 	$ordemAux = $politicasAux['ordem'];
				 }
			}
			if(!empty($politicasAux)){
				$politicasAux['ordem'] = $politicas['ordem'];
				$politicas['ordem'] = $ordemAux;
				editPoliticas($politicas);
				editPoliticas($politicasAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("politicas_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$politicas = buscaPoliticas(array('idpoliticas'=>$dados['idpoliticas']));
 			$politicas = $politicas[0];
			$ordemAux = 0;
			$ordem = $politicas['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $politicasAux = buscaPoliticas(array('ordenacao'=>$ordem));
				 if(!empty($politicasAux)){
				 	$politicasAux = $politicasAux[0];
					$ordemAux = $politicasAux['ordem'];
				 }
			}
			if(!empty($politicasAux)){

				$politicasAux['ordem'] = $politicas['ordem'];
				$politicas['ordem'] = $ordemAux;

				editPoliticas($politicas);
				editPoliticas($politicasAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("politicas_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'politicas';
      $id = 'idpoliticas';

      try {
         $politicas = buscaPoliticas(array('idpoliticas' => $dados['idpoliticas']));
         $politicas = $politicas[0];

         // print_r($politicas);
         if($politicas['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idpoliticas'] = $dados['idpoliticas'];
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