<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_TECNOLOGIAS") || define("CADASTRO_TECNOLOGIAS","cadastroTecnologias");
defined("EDIT_TECNOLOGIAS") || define("EDIT_TECNOLOGIAS","editTecnologias");
defined("DELETA_TECNOLOGIAS") || define("DELETA_TECNOLOGIAS","deletaTecnologias");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_TECNOLOGIAS:
		include_once 'tecnologias_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;

        $imagem = $_FILES;

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("tecnologias", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("tecnologias", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/tecnologias/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idTecnologias = cadastroTecnologias($dados);

		if (is_int($idTecnologias)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'tecnologias';
			$log['avaliacao'] = 'Cadastrou tecnologias ID('.$idTecnologias.') titulo ('.$dados['titulo'].') nome_autor ('.$dados['nome_autor'].') avaliacao ('.$dados['avaliacao'].') valor ('.$dados['valor'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemalerta='.urlencode('Tecnologias criado com sucesso!'));
		} else {
			header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemerro='.urlencode('ERRO ao criar novo Tecnologias!'));
		}

		break;

	case EDIT_TECNOLOGIAS:
		include_once 'tecnologias_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaTecnologias(array('idtecnologias'=>$dados['idtecnologias']));
		$antigo = $antigo[0];

        $imagem = $_FILES;
        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("tecnologias", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("tecnologias", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/tecnologias/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/tecnologias/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idTecnologias = editTecnologias($dados);

		if ($idTecnologias != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'tecnologias';
			$log['avaliacao'] = 'Editou tecnologias ID('.$idTecnologias.') DE  titulo ('.$antigo['titulo'].') imagem ('.$antigo['imagem'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemalerta='.urlencode('Tecnologias salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemerro='.urlencode('ERRO ao salvar Tecnologias!'));
		}

		break;

	case DELETA_TECNOLOGIAS:
		include_once 'tecnologias_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('tecnologias_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaTecnologias(array('idtecnologias'=>$dados['idu']));

            $imgAntigo = $antigo[0]['imagem'];

			// apagarImagemTecnologias($antigo[0]['imagem']);
            deleteFiles('files/tecnologias/', $imgAntigo, array('','thumb_','original_'));

			if (deletaTecnologias($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'tecnologias';
				$log['avaliacao'] = 'Deletou tecnologias ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemalerta='.urlencode('Tecnologias deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=tecnologias&acao=listarTecnologias&mensagemerro='.urlencode('ERRO ao deletar Tecnologias!'));
			}
		}

	break;
    
	case INVERTE_STATUS:
		include_once("tecnologias_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'tecnologias';
		$id = 'idtecnologias';

		try {
			$tecnologias = buscaTecnologias(array('idtecnologias' => $dados['idtecnologias']));
			$tecnologias = $tecnologias[0];

			// print_r($tecnologias);
			if($tecnologias['status'] == 'A'){
				$status = 'I';
			}
			else{
				$status = 'A';
			}

			$dadosUpdate = array();
			$dadosUpdate['idtecnologias'] = $dados['idtecnologias'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'tecnologias_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $tecnologias = buscaTecnologias(array('idtecnologias'=>$id));
        $tecnologias = $tecnologias[0];

        $imgAntigo = $tecnologias[$tipo];
        deleteFiles('files/tecnologias/', $imgAntigo, array('','thumb_','original_'));
        $tecnologias[$tipo] = '';
        editTecnologias($tecnologias);

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
?>