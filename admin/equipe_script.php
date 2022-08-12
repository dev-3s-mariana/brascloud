<?php 
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST['ajax']) || empty($_REQUEST['ajax'])){
   require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_EQUIPE") || define("CADASTRO_EQUIPE","cadastroEquipe");
defined("EDIT_EQUIPE") || define("EDIT_EQUIPE","editEquipe");
defined("DELETA_EQUIPE") || define("DELETA_EQUIPE","deletaEquipe");

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA", "alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO", "alteraOrdemBaixo");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_EQUIPE:
		include_once 'equipe_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$imagem = $_FILES;

      if(empty($dados['status'])){
         $dados['status'] = 2;
      }
      if(empty($dados['imagem'])){
         $dados['imagem'] = "";
      }

		if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
			$coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
			$nomeimagem = fileImage("equipe", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
			fileImage("equipe", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/equipe/'.$nomeimagem;
            compressImage($caminho);

			$dados['imagem'] = $nomeimagem;
		}
		
		$idEquipe = cadastroEquipe($dados);

		if (is_int($idEquipe)) {
         if($dados['ajax']){
            echo '{"status":true}';
         }else{
   			//salva log
   			include_once 'log_class.php';
   			$log['idusuario'] = $_SESSION['sgc_idusuario'];
   			$log['modulo'] = 'equipe';
   			$log['descricao'] = 'Cadastrou equipe ID('.$idEquipe.') nome ('.$dados['nome'].') equipe ('.$dados['equipe'].') ordem ('.$dados['ordem'].') status ('.$dados['status'].') imagem ('.$dados['imagem'].')';
   			$log['request'] = $_REQUEST;
   			novoLog($log);
   			header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemalerta='.urlencode('Equipe criado com sucesso!'));
         }
		} else {
			header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemerro='.urlencode('ERRO ao criar novo Equipe!'));
		}

	break;

	case EDIT_EQUIPE:
		include_once 'equipe_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$imagem = $_FILES;
		$antigo = buscaEquipe(array('idequipe'=>$dados['idequipe']));
		$antigo = $antigo[0];

		if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
			$coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
			$nomeimagem = fileImage("equipe", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
			fileImage("equipe", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/equipe/'.$nomeimagem;
            compressImage($caminho);

			// apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/equipe/', $imgAntigo, array('','thumb_','thumb2_','original_'));
			$dados['imagem'] = $nomeimagem;
		}

		$idEquipe = editEquipe($dados);

		if ($idEquipe != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'equipe';
			$log['descricao'] = 'Editou equipe ID('.$idEquipe.') DE  nome ('.$antigo['nome'].') equipe ('.$antigo['equipe'].') ordem ('.$antigo['ordem'].') status ('.$antigo['status'].') imagem ('.$antigo['imagem'].') PARA  nome ('.$dados['nome'].') equipe ('.$dados['equipe'].') ordem ('.$dados['ordem'].') status ('.$dados['status'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemalerta='.urlencode('Equipe salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemerro='.urlencode('ERRO ao salvar Equipe!'));
		}

	break;

	case DELETA_EQUIPE:
		include_once 'equipe_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';
		if (!verificaPermissaoAcesso('equipe_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaEquipe(array('idequipe'=>$dados['idu']));

			if (deletaEquipe($dados['idu']) == 1) {
				// apagarImagemEquipe($antigo[0]['imagem']);  
                $imgAntigo = $antigo[0]['imagem'];
                deleteFiles('files/equipe/', $imgAntigo, array('','thumb_','thumb2_','original_'));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'equipe';
				$log['descricao'] = 'Deletou equipe ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemalerta='.urlencode('Equipe deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=equipe&acao=listarEquipe&mensagemerro='.urlencode('ERRO ao deletar Equipe!'));
			}
		}

	break;

	case ALTERA_ORDEM_CIMA:
		include_once("equipe_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$equipe = buscaEquipe(array('idequipe' => $dados['idequipe']));
			$equipe = $equipe[0];

			$ordem = $equipe['ordem'] - 1;

			$equipeAux = buscaEquipe(array('order' => $ordem));


			if (!empty($equipeAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idequipe'] = $dados['idequipe'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemEquipe($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idequipe'] = $equipeAux[0]['idequipe'];
				$dadosUpdate2['ordem'] = intval($equipe['ordem']);
				editOrdemEquipe($dadosUpdate2);
			}

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case ALTERA_ORDEM_BAIXO:
		include_once("equipe_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {
			$equipe = buscaEquipe(array('idequipe' => $dados['idequipe']));
			$equipe = $equipe[0];

			$ordem = $equipe['ordem'] + 1;

			$equipeAux = buscaEquipe(array('order' => $ordem));


			if (!empty($equipeAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idequipe'] = $dados['idequipe'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemEquipe($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idequipe'] = $equipeAux[0]['idequipe'];
				$dadosUpdate2['ordem'] = intval($equipe['ordem']);
				editOrdemEquipe($dadosUpdate2);
			}

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case INVERTE_STATUS:
		include_once("equipe_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'equipe';
		$id = 'idequipe';

		try {
			$equipe = buscaEquipe(array('idequipe' => $dados['idequipe']));
			$equipe = $equipe[0];

			// print_r($equipe);
			if($equipe['status'] == 1){
				$status = 0;
			}
			else{
				$status = 1;
			}

			$dadosUpdate = array();
			$dadosUpdate['idequipe'] = $dados['idequipe'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'equipe_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $equipe = buscaEquipe(array('idequipe'=>$id));
        $equipe = $equipe[0];

        $imgAntigo = $equipe[$tipo];
        deleteFiles('files/equipe/', $imgAntigo, array('','thumb_','original_'));
        $equipe[$tipo] = '';
        editEquipe($equipe);

        echo json_encode(array('status'=>true));

    break;

	default:
		if (!headers_sent() && (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
			header('Location: index.php?mod=home&mensagemerro='.urlencode('Nenhuma acao definida...'));
		} else {
			trigger_error('Erro...', E_USER_ERROR);
			exit;
		}

}
