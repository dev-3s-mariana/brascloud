<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_PRODUTOS") || define("CADASTRO_PRODUTOS","cadastroProdutos");
defined("EDIT_PRODUTOS") || define("EDIT_PRODUTOS","editProdutos");
defined("DELETA_PRODUTOS") || define("DELETA_PRODUTOS","deletaProdutos");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_PRODUTOS:
		include_once 'produtos_class.php';

		$dados = $_REQUEST;
		$idProdutos = cadastroProdutos($dados);
		
		if (is_int($idProdutos)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'produtos';
			$log['descricao'] = 'Cadastrou produtos ID('.$idProdutos.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemalerta='.urlencode('Produtos criado com sucesso!'));
		} else {
			header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemerro='.urlencode('ERRO ao criar novo Produtos!'));
		}

	break;

	case EDIT_PRODUTOS:
		include_once 'produtos_class.php';

		$dados = $_REQUEST;
		$antigo = buscaProdutos(array('idprodutos'=>$dados['idprodutos']));
		$antigo = $antigo[0];

		$idProdutos = editProdutos($dados);

		if ($idProdutos != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'produtos';
			$log['descricao'] = 'Editou produtos ID('.$idProdutos.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemalerta='.urlencode('Produtos salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemerro='.urlencode('ERRO ao salvar Produtos!'));
		}

	break;

	case DELETA_PRODUTOS:
		include_once 'produtos_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('produtos_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaProdutos(array('idprodutos'=>$dados['idu']));

			if (deletaProdutos($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'produtos';
				$log['descricao'] = 'Deletou produtos ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemalerta='.urlencode('Produtos deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=produtos&acao=listarProdutos&mensagemerro='.urlencode('ERRO ao deletar Produtos!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("produtos_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $produtos = buscaProdutos(array('idprodutos'=>$dados['idprodutos']));
			$produtos = $produtos[0];

            $ordemAux = 0;
			$ordem = $produtos['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $produtosAux = buscaProdutos(array('ordenacao'=>$ordem));
				 if(!empty($produtosAux)){
					$produtosAux = $produtosAux[0];
				 	$ordemAux = $produtosAux['ordem'];
				 }
			}
			if(!empty($produtosAux)){
				$produtosAux['ordem'] = $produtos['ordem'];
				$produtos['ordem'] = $ordemAux;
				editProdutos($produtos);
				editProdutos($produtosAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("produtos_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$produtos = buscaProdutos(array('idprodutos'=>$dados['idprodutos']));
 			$produtos = $produtos[0];
			$ordemAux = 0;
			$ordem = $produtos['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $produtosAux = buscaProdutos(array('ordenacao'=>$ordem));
				 if(!empty($produtosAux)){
				 	$produtosAux = $produtosAux[0];
					$ordemAux = $produtosAux['ordem'];
				 }
			}
			if(!empty($produtosAux)){

				$produtosAux['ordem'] = $produtos['ordem'];
				$produtos['ordem'] = $ordemAux;

				editProdutos($produtos);
				editProdutos($produtosAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("produtos_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'produtos';
      $id = 'idprodutos';

      try {
         $produtos = buscaProdutos(array('idprodutos' => $dados['idprodutos']));
         $produtos = $produtos[0];

         // print_r($produtos);
         if($produtos['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idprodutos'] = $dados['idprodutos'];
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