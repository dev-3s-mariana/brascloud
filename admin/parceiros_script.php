<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_PARCEIROS") || define("CADASTRO_PARCEIROS","cadastroParceiros");
defined("EDIT_PARCEIROS") || define("EDIT_PARCEIROS","editParceiros");
defined("DELETA_PARCEIROS") || define("DELETA_PARCEIROS","deletaParceiros");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_PARCEIROS:
		include_once 'parceiros_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;

        $imagem = $_FILES;

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("parceiros", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("parceiros", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/parceiros/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idParceiros = cadastroParceiros($dados);

		if (is_int($idParceiros)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'parceiros';
			$log['avaliacao'] = 'Cadastrou parceiros ID('.$idParceiros.') titulo ('.$dados['titulo'].') nome_autor ('.$dados['nome_autor'].') avaliacao ('.$dados['avaliacao'].') valor ('.$dados['valor'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemalerta='.urlencode('Parceiros criado com sucesso!'));
		} else {
			header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemerro='.urlencode('ERRO ao criar novo Parceiros!'));
		}

		break;

	case EDIT_PARCEIROS:
		include_once 'parceiros_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaParceiros(array('idparceiros'=>$dados['idparceiros']));
		$antigo = $antigo[0];

        $imagem = $_FILES;
        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("parceiros", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("parceiros", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/parceiros/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/parceiros/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idParceiros = editParceiros($dados);

		if ($idParceiros != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'parceiros';
			$log['avaliacao'] = 'Editou parceiros ID('.$idParceiros.') DE  titulo ('.$antigo['titulo'].') imagem ('.$antigo['imagem'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemalerta='.urlencode('Parceiros salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemerro='.urlencode('ERRO ao salvar Parceiros!'));
		}

		break;

	case DELETA_PARCEIROS:
		include_once 'parceiros_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('parceiros_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaParceiros(array('idparceiros'=>$dados['idu']));

            $imgAntigo = $antigo[0]['imagem'];

			// apagarImagemParceiros($antigo[0]['imagem']);
            deleteFiles('files/parceiros/', $imgAntigo, array('','thumb_','original_'));

			if (deletaParceiros($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'parceiros';
				$log['avaliacao'] = 'Deletou parceiros ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemalerta='.urlencode('Parceiros deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=parceiros&acao=listarParceiros&mensagemerro='.urlencode('ERRO ao deletar Parceiros!'));
			}
		}

	break;
    
	case INVERTE_STATUS:
		include_once("parceiros_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'parceiros';
		$id = 'idparceiros';

		try {
			$parceiros = buscaParceiros(array('idparceiros' => $dados['idparceiros']));
			$parceiros = $parceiros[0];

			// print_r($parceiros);
			if($parceiros['status'] == 'A'){
				$status = 'I';
			}
			else{
				$status = 'A';
			}

			$dadosUpdate = array();
			$dadosUpdate['idparceiros'] = $dados['idparceiros'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'parceiros_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $parceiros = buscaParceiros(array('idparceiros'=>$id));
        $parceiros = $parceiros[0];

        $imgAntigo = $parceiros[$tipo];
        deleteFiles('files/parceiros/', $imgAntigo, array('','thumb_','original_'));
        $parceiros[$tipo] = '';
        editParceiros($parceiros);

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