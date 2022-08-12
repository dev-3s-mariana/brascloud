<?php 
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST["opx"]) || $_REQUEST["opx"] != "listarSegmento"){
	require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_SEGMENTO") || define("CADASTRO_SEGMENTO","cadastroSegmento");
defined("EDIT_SEGMENTO") || define("EDIT_SEGMENTO","editSegmento");
defined("DELETA_SEGMENTO") || define("DELETA_SEGMENTO","deletaSegmento");
defined("LISTAR_SEGMENTO") || define("LISTAR_SEGMENTO","listarSegmento");
defined("PESQUISA_ICONE") || define("PESQUISA_ICONE", "pesquisaIcone");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA", "alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO", "alteraOrdemBaixo");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_SEGMENTO:
		include_once 'segmento_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		
        $imagem = $_FILES;

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("segmento", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/segmento/'.$nomeicone;
            compressImage($caminho);

            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("segmento", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("segmento", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/segmento/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idSegmento = cadastroSegmento($dados);

		if (is_int($idSegmento)) {  
			 
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'segmento';
			$log['descricao'] = 'Cadastrou segmento ID('.$idSegmento.') título ('.$dados['titulo'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemalerta='.urlencode('Segmento criado com sucesso!'));
		} else {
			header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemerro='.urlencode('ERRO ao criar novo Segmento!'));
		}

	break;

	case EDIT_SEGMENTO:
		include_once 'segmento_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
        $imagem = $_FILES;
        $antigo = buscaSegmento(array('idsegmento'=>$dados['idsegmento']));
		$antigo = $antigo[0]; 

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("segmento", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/segmento/'.$nomeicone;
            compressImage($caminho);
            
            // editarImagemSegmento($antigo['icone']);  
            $imgAntigo = $antigo['icone'];
            deleteFiles('files/segmento/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("segmento", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("segmento", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/segmento/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/segmento/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idSegmento = editSegmento($dados);

		if ($idSegmento != FALSE) { 

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'segmento';
			$log['descricao'] = 'Editou segmento ID('.$idSegmento.') DE  título ('.$antigo['titulo'].') texto ('.$antigo['texto'].') imagem ('.$antigo['imagem'].') icone ('.$antigo['icone'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemalerta='.urlencode('Segmento salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemerro='.urlencode('ERRO ao salvar Segmento!'));
		}

	break;

	case DELETA_SEGMENTO:
		include_once 'segmento_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('segmento_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaSegmento(array('idsegmento'=>$dados['idu']));

			if (deletaSegmento($dados['idu']) == 1) {
				// editarImagemSegmento($antigo[0]['icone']);  
                $imgAntigo = $antigo[0]['icone'];
                $imgAntigo2 = $antigo[0]['imagem'];
                deleteFiles('files/segmento/', $imgAntigo, array('','thumb_','thumb2_','original_'));
                deleteFiles('files/segmento/', $imgAntigo2, array('','thumb_','thumb2_','original_'));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'segmento';
				$log['descricao'] = 'Deletou segmento ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemalerta='.urlencode('Segmento deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=segmento&acao=listarSegmento&mensagemerro='.urlencode('ERRO ao deletar Segmento!'));
			}
		}

	break; 

	case LISTAR_SEGMENTO: 

		include_once 'segmento_class.php';  
        $dados = $_REQUEST;  
        $retorno = array(); 
        $segmento = buscaSegmento($dados); 
        if(!isset($dados['filtro'])){
	        $retorno['dados'] = $segmento;
	        $dados['totalRecords'] = true;  
	        $total = buscaSegmento($dados); 
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
        include_once 'segmento_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $segmento = buscaSegmento(array('idsegmento'=>$id));
        $segmento = $segmento[0];

        $imgAntigo = $segmento[$tipo];
        deleteFiles('files/segmento/', $imgAntigo, array('','thumb_','original_'));
        $segmento[$tipo] = '';
        editSegmento($segmento);

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
		include_once("segmento_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$segmento = buscaSegmento(array('idsegmento' => $dados['idsegmento']));
			$segmento = $segmento[0];

			$ordem = $segmento['ordem'] - 1;

			$segmentoAux = buscaSegmento(array('order' => $ordem));

			if (!empty($segmentoAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idsegmento'] = $dados['idsegmento'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemSegmento($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idsegmento'] = $segmentoAux[0]['idsegmento'];
				$dadosUpdate2['ordem'] = intval($segmento['ordem']);
				editOrdemSegmento($dadosUpdate2);
			}

			print json_encode($resultado);

		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case ALTERA_ORDEM_BAIXO:
		include_once("segmento_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$segmento = buscaSegmento(array('idsegmento' => $dados['idsegmento']));
			$segmento = $segmento[0];

			$ordem = $segmento['ordem'] + 1;

			$segmentoAux = buscaSegmento(array('order' => $ordem));

			if (!empty($segmentoAux)) {
				$dadosUpdate = array();
				$dadosUpdate['idsegmento'] = $dados['idsegmento'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemSegmento($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idsegmento'] = $segmentoAux[0]['idsegmento'];
				$dadosUpdate2['ordem'] = intval($segmento['ordem']);
				editOrdemSegmento($dadosUpdate2);
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