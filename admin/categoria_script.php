<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_CATEGORIA") || define("CADASTRO_CATEGORIA","cadastroCategoria");
defined("EDIT_CATEGORIA") || define("EDIT_CATEGORIA","editCategoria");
defined("DELETA_CATEGORIA") || define("DELETA_CATEGORIA","deletaCategoria");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_CATEGORIA:
		include_once 'categoria_class.php';

		$dados = $_REQUEST;
		$idCategoria = cadastroCategoria($dados);
		
		if (is_int($idCategoria)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'categoria';
			$log['descricao'] = 'Cadastrou categoria ID('.$idCategoria.') nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemalerta='.urlencode('Categoria criado com sucesso!'));
		} else {
			header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemerro='.urlencode('ERRO ao criar novo Categoria!'));
		}

	break;

	case EDIT_CATEGORIA:
		include_once 'categoria_class.php';

		$dados = $_REQUEST;
		$antigo = buscaCategoria(array('idcategoria'=>$dados['idcategoria']));
		$antigo = $antigo[0];

		$idCategoria = editCategoria($dados);

		if ($idCategoria != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'categoria';
			$log['descricao'] = 'Editou categoria ID('.$idCategoria.') DE  nome ('.$antigo['nome'].') descricao ('.$antigo['descricao'].') status ('.$antigo['status'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') descricao ('.$dados['descricao'].') status ('.$dados['status'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemalerta='.urlencode('Categoria salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemerro='.urlencode('ERRO ao salvar Categoria!'));
		}

	break;

	case DELETA_CATEGORIA:
		include_once 'categoria_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('categoria_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaCategoria(array('idcategoria'=>$dados['idu']));

			if (deletaCategoria($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'categoria';
				$log['descricao'] = 'Deletou categoria ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemalerta='.urlencode('Categoria deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=categoria&acao=listarCategoria&mensagemerro='.urlencode('ERRO ao deletar Categoria!'));
			}
		}

	break;


	case ALTERA_ORDEM_BAIXO:
		include_once("categoria_class.php");

        $dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		try {
            $categoria = buscaCategoria(array('idcategoria'=>$dados['idcategoria']));
			$categoria = $categoria[0];

            $ordemAux = 0;
			$ordem = $categoria['ordem'];
			
			while($ordemAux == 0){
				 $ordem = $ordem + 1;
                 $categoriaAux = buscaCategoria(array('ordenacao'=>$ordem));
				 if(!empty($categoriaAux)){
					$categoriaAux = $categoriaAux[0];
				 	$ordemAux = $categoriaAux['ordem'];
				 }
			}
			if(!empty($categoriaAux)){
				$categoriaAux['ordem'] = $categoria['ordem'];
				$categoria['ordem'] = $ordemAux;
				editCategoria($categoria);
				editCategoria($categoriaAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
    break;
    
    case ALTERA_ORDEM_CIMA:
		include_once("categoria_class.php");

		$dados = $_REQUEST; 
		$resultado['status'] = 'sucesso';
		try {
			$categoria = buscaCategoria(array('idcategoria'=>$dados['idcategoria']));
 			$categoria = $categoria[0];
			$ordemAux = 0;
			$ordem = $categoria['ordem'];
			while($ordemAux == 0)
			{
				 $ordem = $ordem - 1;

				 $categoriaAux = buscaCategoria(array('ordenacao'=>$ordem));
				 if(!empty($categoriaAux)){
				 	$categoriaAux = $categoriaAux[0];
					$ordemAux = $categoriaAux['ordem'];
				 }
			}
			if(!empty($categoriaAux)){

				$categoriaAux['ordem'] = $categoria['ordem'];
				$categoria['ordem'] = $ordemAux;

				editCategoria($categoria);
				editCategoria($categoriaAux);
			 }

			print json_encode($resultado);

		} catch (Exception $e) {
    		$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
      include_once("categoria_class.php");
      $dados = $_REQUEST;
      // inverteStatus($dados);
      $resultado['status'] = 'sucesso';
      include_once("includes/functions.php");
      $tabela = 'categoria';
      $id = 'idcategoria';

      try {
         $categoria = buscaCategoria(array('idcategoria' => $dados['idcategoria']));
         $categoria = $categoria[0];

         // print_r($categoria);
         if($categoria['status'] == 'A'){
            $status = 'I';
         }
         else{
            $status = 'A';
         }

         $dadosUpdate = array();
         $dadosUpdate['idcategoria'] = $dados['idcategoria'];
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