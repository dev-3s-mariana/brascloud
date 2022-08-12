<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_SERVICOS_ADICIONAIS") || define("CADASTRO_SERVICOS_ADICIONAIS","cadastroServicos_adicionais");
defined("EDIT_SERVICOS_ADICIONAIS") || define("EDIT_SERVICOS_ADICIONAIS","editServicos_adicionais");
defined("DELETA_SERVICOS_ADICIONAIS") || define("DELETA_SERVICOS_ADICIONAIS","deletaServicos_adicionais");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_SERVICOS_ADICIONAIS:
		include_once 'servicos_adicionais_class.php';

		$dados = $_REQUEST;
		$idServicos_adicionais = cadastroServicos_adicionais($dados);
		
		if (is_int($idServicos_adicionais)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'servicos_adicionais';
			$log['descricao'] = 'Cadastrou servicos_adicionais ID('.$idServicos_adicionais.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemalerta='.urlencode('Servicos_adicionais criado com sucesso!'));
		} else {
			header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemerro='.urlencode('ERRO ao criar novo Servicos_adicionais!'));
		}

	break;

	case EDIT_SERVICOS_ADICIONAIS:
		include_once 'servicos_adicionais_class.php';

		$dados = $_REQUEST;
		$antigo = buscaServicos_adicionais(array('idservicos_adicionais'=>$dados['idservicos_adicionais']));
		$antigo = $antigo[0];

		$idServicos_adicionais = editServicos_adicionais($dados);

		if ($idServicos_adicionais != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'servicos_adicionais';
			$log['descricao'] = 'Editou servicos_adicionais ID('.$idServicos_adicionais.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemalerta='.urlencode('Servicos_adicionais salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemerro='.urlencode('ERRO ao salvar Servicos_adicionais!'));
		}

	break;

	case DELETA_SERVICOS_ADICIONAIS:
		include_once 'servicos_adicionais_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('servicos_adicionais_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaServicos_adicionais(array('idservicos_adicionais'=>$dados['idu']));

			if (deletaServicos_adicionais($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'servicos_adicionais';
				$log['descricao'] = 'Deletou servicos_adicionais ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemalerta='.urlencode('Servicos_adicionais deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=servicos_adicionais&acao=listarServicos_adicionais&mensagemerro='.urlencode('ERRO ao deletar Servicos_adicionais!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("servicos_adicionais_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $servicos_adicionais = buscaServicos_adicionais(array('idservicos_adicionais'=>$dados['idservicos_adicionais']));
			$servicos_adicionais = $servicos_adicionais[0];

            $ordemAux = 0;
			$ordem = $servicos_adicionais['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $servicos_adicionaisAux = buscaServicos_adicionais(array('ordenacao'=>$ordem));
				 if(!empty($servicos_adicionaisAux)){
					$servicos_adicionaisAux = $servicos_adicionaisAux[0];
				 	$ordemAux = $servicos_adicionaisAux['ordem'];
				 }
			}
			if(!empty($servicos_adicionaisAux)){
				$servicos_adicionaisAux['ordem'] = $servicos_adicionais['ordem'];
				$servicos_adicionais['ordem'] = $ordemAux;
				editServicos_adicionais($servicos_adicionais);
				editServicos_adicionais($servicos_adicionaisAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("servicos_adicionais_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$servicos_adicionais = buscaServicos_adicionais(array('idservicos_adicionais'=>$dados['idservicos_adicionais']));
 			$servicos_adicionais = $servicos_adicionais[0];
			$ordemAux = 0;
			$ordem = $servicos_adicionais['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $servicos_adicionaisAux = buscaServicos_adicionais(array('ordenacao'=>$ordem));
				 if(!empty($servicos_adicionaisAux)){
				 	$servicos_adicionaisAux = $servicos_adicionaisAux[0];
					$ordemAux = $servicos_adicionaisAux['ordem'];
				 }
			}
			if(!empty($servicos_adicionaisAux)){

				$servicos_adicionaisAux['ordem'] = $servicos_adicionais['ordem'];
				$servicos_adicionais['ordem'] = $ordemAux;

				editServicos_adicionais($servicos_adicionais);
				editServicos_adicionais($servicos_adicionaisAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("servicos_adicionais_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'servicos_adicionais';
      $id = 'idservicos_adicionais';

      try {
         $servicos_adicionais = buscaServicos_adicionais(array('idservicos_adicionais' => $dados['idservicos_adicionais']));
         $servicos_adicionais = $servicos_adicionais[0];

         // print_r($servicos_adicionais);
         if($servicos_adicionais['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idservicos_adicionais'] = $dados['idservicos_adicionais'];
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