<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_CLIENTE") || define("CADASTRO_CLIENTE","cadastroCliente");
defined("EDIT_CLIENTE") || define("EDIT_CLIENTE","editCliente");
defined("DELETA_CLIENTE") || define("DELETA_CLIENTE","deletaCliente");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_CLIENTE:
		include_once 'cliente_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;

        $imagem = $_FILES;

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("cliente", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("cliente", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/cliente/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idCliente = cadastroCliente($dados);

		if (is_int($idCliente)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'cliente';
			$log['avaliacao'] = 'Cadastrou cliente ID('.$idCliente.') titulo ('.$dados['titulo'].') nome_autor ('.$dados['nome_autor'].') avaliacao ('.$dados['avaliacao'].') valor ('.$dados['valor'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=cliente&acao=listarCliente&mensagemalerta='.urlencode('Cliente criado com sucesso!'));
		} else {
			header('Location: index.php?mod=cliente&acao=listarCliente&mensagemerro='.urlencode('ERRO ao criar novo Cliente!'));
		}

		break;

	case EDIT_CLIENTE:
		include_once 'cliente_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaCliente(array('idcliente'=>$dados['idcliente']));
		$antigo = $antigo[0];

        $imagem = $_FILES;
        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("cliente", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("cliente", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/cliente/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/cliente/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idCliente = editCliente($dados);

		if ($idCliente != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'cliente';
			$log['avaliacao'] = 'Editou cliente ID('.$idCliente.') DE  titulo ('.$antigo['titulo'].') imagem ('.$antigo['imagem'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=cliente&acao=listarCliente&mensagemalerta='.urlencode('Cliente salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=cliente&acao=listarCliente&mensagemerro='.urlencode('ERRO ao salvar Cliente!'));
		}

		break;

	case DELETA_CLIENTE:
		include_once 'cliente_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('cliente_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=cliente&acao=listarCliente&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaCliente(array('idcliente'=>$dados['idu']));

            $imgAntigo = $antigo[0]['imagem'];

			// apagarImagemCliente($antigo[0]['imagem']);
            deleteFiles('files/cliente/', $imgAntigo, array('','thumb_','original_'));

			if (deletaCliente($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'cliente';
				$log['avaliacao'] = 'Deletou cliente ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=cliente&acao=listarCliente&mensagemalerta='.urlencode('Cliente deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=cliente&acao=listarCliente&mensagemerro='.urlencode('ERRO ao deletar Cliente!'));
			}
		}

	break;
    
	case INVERTE_STATUS:
		include_once("cliente_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'cliente';
		$id = 'idcliente';

		try {
			$cliente = buscaCliente(array('idcliente' => $dados['idcliente']));
			$cliente = $cliente[0];

			// print_r($cliente);
			if($cliente['status'] == 'A'){
				$status = 'I';
			}
			else{
				$status = 'A';
			}

			$dadosUpdate = array();
			$dadosUpdate['idcliente'] = $dados['idcliente'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'cliente_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $cliente = buscaCliente(array('idcliente'=>$id));
        $cliente = $cliente[0];

        $imgAntigo = $cliente[$tipo];
        deleteFiles('files/cliente/', $imgAntigo, array('','thumb_','original_'));
        $cliente[$tipo] = '';
        editCliente($cliente);

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