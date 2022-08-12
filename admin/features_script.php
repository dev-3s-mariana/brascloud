<?php 
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST["opx"]) || $_REQUEST["opx"] != "listarFeatures"){
	require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_FEATURES") || define("CADASTRO_FEATURES","cadastroFeatures");
defined("EDIT_FEATURES") || define("EDIT_FEATURES","editFeatures");
defined("DELETA_FEATURES") || define("DELETA_FEATURES","deletaFeatures");
defined("LISTAR_FEATURES") || define("LISTAR_FEATURES","listarFeatures");
defined("PESQUISA_ICONE") || define("PESQUISA_ICONE", "pesquisaIcone");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA", "alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO", "alteraOrdemBaixo");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_FEATURES:
		include_once 'features_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		
        $imagem = $_FILES;

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("features", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/features/'.$nomeicone;
            compressImage($caminho);

            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("features", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("features", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/features/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idFeatures = cadastroFeatures($dados);

		if (is_int($idFeatures)) {  
			 
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'features';
			$log['descricao'] = 'Cadastrou features ID('.$idFeatures.') título ('.$dados['titulo'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=features&acao=listarFeatures&mensagemalerta='.urlencode('Features criado com sucesso!'));
		} else {
			header('Location: index.php?mod=features&acao=listarFeatures&mensagemerro='.urlencode('ERRO ao criar novo Features!'));
		}

	break;

	case EDIT_FEATURES:
		include_once 'features_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
        $imagem = $_FILES;
        $antigo = buscaFeatures(array('idfeatures'=>$dados['idfeatures']));
		$antigo = $antigo[0]; 

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("features", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/features/'.$nomeicone;
            compressImage($caminho);
            
            // editarImagemFeatures($antigo['icone']);  
            $imgAntigo = $antigo['icone'];
            deleteFiles('files/features/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("features", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("features", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/features/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/features/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idFeatures = editFeatures($dados);

		if ($idFeatures != FALSE) { 

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'features';
			$log['descricao'] = 'Editou features ID('.$idFeatures.') DE  título ('.$antigo['titulo'].') texto ('.$antigo['texto'].') imagem ('.$antigo['imagem'].') icone ('.$antigo['icone'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=features&acao=listarFeatures&mensagemalerta='.urlencode('Features salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=features&acao=listarFeatures&mensagemerro='.urlencode('ERRO ao salvar Features!'));
		}

	break;

	case DELETA_FEATURES:
		include_once 'features_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('features_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=features&acao=listarFeatures&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaFeatures(array('idfeatures'=>$dados['idu']));

			if (deletaFeatures($dados['idu']) == 1) {
				// editarImagemFeatures($antigo[0]['icone']);  
                $imgAntigo = $antigo[0]['icone'];
                $imgAntigo2 = $antigo[0]['imagem'];
                deleteFiles('files/features/', $imgAntigo, array('','thumb_','thumb2_','original_'));
                deleteFiles('files/features/', $imgAntigo2, array('','thumb_','thumb2_','original_'));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'features';
				$log['descricao'] = 'Deletou features ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=features&acao=listarFeatures&mensagemalerta='.urlencode('Features deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=features&acao=listarFeatures&mensagemerro='.urlencode('ERRO ao deletar Features!'));
			}
		}

	break; 

	case LISTAR_FEATURES: 

		include_once 'features_class.php';  
        $dados = $_REQUEST;  
        $retorno = array(); 
        $features = buscaFeatures($dados); 
        if(!isset($dados['filtro'])){
	        $retorno['dados'] = $features;
	        $dados['totalRecords'] = true;  
	        $total = buscaFeatures($dados); 
	        $total = $total[0]['totalRecords'];   
	        $retorno['total'] = $total; 
	        if($total > 0 && isset($dados['limit'])){ 
	            $paginas = ceil($total / $dados['limit']);
	            $retorno['totalPaginas'] = $paginas;
	        }
    	}
        print json_encode($retorno);
	break; 

    case EXCLUIR_IMAGEM:
        include_once 'features_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $features = buscaFeatures(array('idfeatures'=>$id));
        $features = $features[0];

        $imgAntigo = $features[$tipo];
        deleteFiles('files/features/', $imgAntigo, array('','thumb_','original_'));
        $features[$tipo] = '';
        editFeatures($features);

        echo json_encode(array('status'=>true));

    break;

   case PESQUISA_ICONE:
      include_once('servico_class.php');
      include_once('includes/functions.php');
      $dados = $_REQUEST;
      $icone = buscaFW3(array('nome' => $dados['nome'], 'ordem' => 'nome', 'dir' => 'asc'));
      if (!empty($icone)) {
         $html = '';
         foreach ($icone as $key => $i) {
            $html .= '<div style="width:6%; display: inline-block;" data-id="' . $i['idfw'] . '" data-toggle="tooltip" title="' . $i['nome'] . '">';
            $html .= '    <i class="fa fa-' . $i['nome'] . ' icone_icone" data-id="' . $i['idfw'] . '" data-nome="' . $i['nome'] . '" style="padding:11px; cursor: pointer;"></i>';
            $html .= '</div>';
         }
      } else {
         $html = '<span>Nenhum icone encontrado</span>';
      }
      echo $html;

   break;

	case ALTERA_ORDEM_CIMA:
		include_once("features_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$features = buscaFeatures(array('idfeatures' => $dados['idfeatures']));
			$features = $features[0];

			$ordem = $features['ordem'] - 1;

			$featuresAux = buscaFeatures(array('order' => $ordem));

			if (!empty($featuresAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idfeatures'] = $dados['idfeatures'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemFeatures($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idfeatures'] = $featuresAux[0]['idfeatures'];
				$dadosUpdate2['ordem'] = intval($features['ordem']);
				editOrdemFeatures($dadosUpdate2);
			}

			print json_encode($resultado);

		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case ALTERA_ORDEM_BAIXO:
		include_once("features_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$features = buscaFeatures(array('idfeatures' => $dados['idfeatures']));
			$features = $features[0];

			$ordem = $features['ordem'] + 1;

			$featuresAux = buscaFeatures(array('order' => $ordem));

			if (!empty($featuresAux)) {
				$dadosUpdate = array();
				$dadosUpdate['idfeatures'] = $dados['idfeatures'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemFeatures($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idfeatures'] = $featuresAux[0]['idfeatures'];
				$dadosUpdate2['ordem'] = intval($features['ordem']);
				editOrdemFeatures($dadosUpdate2);
			}

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