<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_FAQ") || define("CADASTRO_FAQ","cadastroFaq");
defined("EDIT_FAQ") || define("EDIT_FAQ","editFaq");
defined("DELETA_FAQ") || define("DELETA_FAQ","deletaFaq");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_FAQ:
		include_once 'faq_class.php';

		$dados = $_REQUEST;
		$idFaq = cadastroFaq($dados);
		
		if (is_int($idFaq)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'faq';
			$log['descricao'] = 'Cadastrou faq ID('.$idFaq.') pergunta ('.$dados['pergunta'].') respota ('.$dados['respota'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=faq&acao=listarFaq&mensagemalerta='.urlencode('Faq criado com sucesso!'));
		} else {
			header('Location: index.php?mod=faq&acao=listarFaq&mensagemerro='.urlencode('ERRO ao criar novo Faq!'));
		}

	break;

	case EDIT_FAQ:
		include_once 'faq_class.php';

		$dados = $_REQUEST;
		$antigo = buscaFaq(array('idfaq'=>$dados['idfaq']));
		$antigo = $antigo[0];

		$idFaq = editFaq($dados);

		if ($idFaq != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'faq';
			$log['descricao'] = 'Editou faq ID('.$idFaq.') DE  pergunta ('.$antigo['pergunta'].') respota ('.$antigo['respota'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  pergunta ('.$dados['pergunta'].') respota ('.$dados['respota'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=faq&acao=listarFaq&mensagemalerta='.urlencode('Faq salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=faq&acao=listarFaq&mensagemerro='.urlencode('ERRO ao salvar Faq!'));
		}

	break;

	case DELETA_FAQ:
		include_once 'faq_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('faq_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=faq&acao=listarFaq&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaFaq(array('idfaq'=>$dados['idu']));

			if (deletaFaq($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'faq';
				$log['descricao'] = 'Deletou faq ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=faq&acao=listarFaq&mensagemalerta='.urlencode('Faq deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=faq&acao=listarFaq&mensagemerro='.urlencode('ERRO ao deletar Faq!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("faq_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $faq = buscaFaq(array('idfaq'=>$dados['idfaq']));
			$faq = $faq[0];

            $ordemAux = 0;
			$ordem = $faq['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $faqAux = buscaFaq(array('ordenacao'=>$ordem));
				 if(!empty($faqAux)){
					$faqAux = $faqAux[0];
				 	$ordemAux = $faqAux['ordem'];
				 }
			}
			if(!empty($faqAux)){
				$faqAux['ordem'] = $faq['ordem'];
				$faq['ordem'] = $ordemAux;
				editFaq($faq);
				editFaq($faqAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("faq_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$faq = buscaFaq(array('idfaq'=>$dados['idfaq']));
 			$faq = $faq[0];
			$ordemAux = 0;
			$ordem = $faq['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $faqAux = buscaFaq(array('ordenacao'=>$ordem));
				 if(!empty($faqAux)){
				 	$faqAux = $faqAux[0];
					$ordemAux = $faqAux['ordem'];
				 }
			}
			if(!empty($faqAux)){

				$faqAux['ordem'] = $faq['ordem'];
				$faq['ordem'] = $ordemAux;

				editFaq($faq);
				editFaq($faqAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("faq_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'faq';
      $id = 'idfaq';

      try {
         $faq = buscaFaq(array('idfaq' => $dados['idfaq']));
         $faq = $faq[0];

         // print_r($faq);
         if($faq['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idfaq'] = $dados['idfaq'];
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