<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_PROJETO") || define("CADASTRO_PROJETO","cadastroProjeto");
defined("EDIT_PROJETO") || define("EDIT_PROJETO","editProjeto");
defined("DELETA_PROJETO") || define("DELETA_PROJETO","deletaProjeto");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_PROJETO:
		include_once 'projeto_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;

        $imagem = $_FILES;

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("projeto", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("projeto", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/projeto/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idProjeto = cadastroProjeto($dados);

		if (is_int($idProjeto)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'projeto';
			$log['avaliacao'] = 'Cadastrou projeto ID('.$idProjeto.') titulo ('.$dados['titulo'].') nome_autor ('.$dados['nome_autor'].') avaliacao ('.$dados['avaliacao'].') valor ('.$dados['valor'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemalerta='.urlencode('Projeto criado com sucesso!'));
		} else {
			header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemerro='.urlencode('ERRO ao criar novo Projeto!'));
		}

		break;

	case EDIT_PROJETO:
		include_once 'projeto_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaProjeto(array('idprojeto'=>$dados['idprojeto']));
		$antigo = $antigo[0];

        $imagem = $_FILES;
        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("projeto", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("projeto", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/projeto/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/projeto/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idProjeto = editProjeto($dados);

		if ($idProjeto != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'projeto';
			$log['avaliacao'] = 'Editou projeto ID('.$idProjeto.') DE  titulo ('.$antigo['titulo'].') imagem ('.$antigo['imagem'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemalerta='.urlencode('Projeto salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemerro='.urlencode('ERRO ao salvar Projeto!'));
		}

		break;

	case DELETA_PROJETO:
		include_once 'projeto_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('projeto_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaProjeto(array('idprojeto'=>$dados['idu']));

            $imgAntigo = $antigo[0]['imagem'];

			// apagarImagemProjeto($antigo[0]['imagem']);
            deleteFiles('files/projeto/', $imgAntigo, array('','thumb_','original_'));

			if (deletaProjeto($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'projeto';
				$log['avaliacao'] = 'Deletou projeto ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemalerta='.urlencode('Projeto deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=projeto&acao=listarProjeto&mensagemerro='.urlencode('ERRO ao deletar Projeto!'));
			}
		}

	break;
    
	case INVERTE_STATUS:
		include_once("projeto_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'projeto';
		$id = 'idprojeto';

		try {
			$projeto = buscaProjeto(array('idprojeto' => $dados['idprojeto']));
			$projeto = $projeto[0];

			// print_r($projeto);
			if($projeto['status'] == 'A'){
				$status = 'I';
			}
			else{
				$status = 'A';
			}

			$dadosUpdate = array();
			$dadosUpdate['idprojeto'] = $dados['idprojeto'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'projeto_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $projeto = buscaProjeto(array('idprojeto'=>$id));
        $projeto = $projeto[0];

        $imgAntigo = $projeto[$tipo];
        deleteFiles('files/projeto/', $imgAntigo, array('','thumb_','original_'));
        $projeto[$tipo] = '';
        editProjeto($projeto);

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