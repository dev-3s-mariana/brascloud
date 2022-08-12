<?php 
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST["opx"]) || $_REQUEST["opx"] != "listarCategoria_suporte"){
	require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_CATEGORIA_SUPORTE") || define("CADASTRO_CATEGORIA_SUPORTE","cadastroCategoria_suporte");
defined("EDIT_CATEGORIA_SUPORTE") || define("EDIT_CATEGORIA_SUPORTE","editCategoria_suporte");
defined("DELETA_CATEGORIA_SUPORTE") || define("DELETA_CATEGORIA_SUPORTE","deletaCategoria_suporte");
defined("LISTAR_CATEGORIA_SUPORTE") || define("LISTAR_CATEGORIA_SUPORTE","listarCategoria_suporte");
defined("PESQUISA_ICONE") || define("PESQUISA_ICONE", "pesquisaIcone");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA", "alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO", "alteraOrdemBaixo");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");

switch ($opx) {

	case CADASTRO_CATEGORIA_SUPORTE:
		include_once 'categoria_suporte_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		
        $imagem = $_FILES;

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("categoria_suporte", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/categoria_suporte/'.$nomeicone;
            compressImage($caminho);

            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("categoria_suporte", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("categoria_suporte", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/categoria_suporte/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idCategoria_suporte = cadastroCategoria_suporte($dados);

		if (is_int($idCategoria_suporte)) {  
			 
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'categoria_suporte';
			$log['descricao'] = 'Cadastrou categoria_suporte ID('.$idCategoria_suporte.') título ('.$dados['titulo'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemalerta='.urlencode('Categoria_suporte criado com sucesso!'));
		} else {
			header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemerro='.urlencode('ERRO ao criar novo Categoria_suporte!'));
		}

	break;

	case EDIT_CATEGORIA_SUPORTE:
		include_once 'categoria_suporte_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
        $imagem = $_FILES;
        $antigo = buscaCategoria_suporte(array('idcategoria_suporte'=>$dados['idcategoria_suporte']));
		$antigo = $antigo[0]; 

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("categoria_suporte", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/categoria_suporte/'.$nomeicone;
            compressImage($caminho);
            
            // editarImagemCategoria_suporte($antigo['icone']);  
            $imgAntigo = $antigo['icone'];
            deleteFiles('files/categoria_suporte/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("categoria_suporte", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("categoria_suporte", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/categoria_suporte/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/categoria_suporte/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idCategoria_suporte = editCategoria_suporte($dados);

		if ($idCategoria_suporte != FALSE) { 

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'categoria_suporte';
			$log['descricao'] = 'Editou categoria_suporte ID('.$idCategoria_suporte.') DE  título ('.$antigo['titulo'].') texto ('.$antigo['texto'].') imagem ('.$antigo['imagem'].') icone ('.$antigo['icone'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemalerta='.urlencode('Categoria_suporte salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemerro='.urlencode('ERRO ao salvar Categoria_suporte!'));
		}

	break;

	case DELETA_CATEGORIA_SUPORTE:
		include_once 'categoria_suporte_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('categoria_suporte_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaCategoria_suporte(array('idcategoria_suporte'=>$dados['idu']));

			if (deletaCategoria_suporte($dados['idu']) == 1) {
				// editarImagemCategoria_suporte($antigo[0]['icone']);  
                $imgAntigo = $antigo[0]['icone'];
                $imgAntigo2 = $antigo[0]['imagem'];
                deleteFiles('files/categoria_suporte/', $imgAntigo, array('','thumb_','thumb2_','original_'));
                deleteFiles('files/categoria_suporte/', $imgAntigo2, array('','thumb_','thumb2_','original_'));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'categoria_suporte';
				$log['descricao'] = 'Deletou categoria_suporte ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemalerta='.urlencode('Categoria_suporte deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=categoria_suporte&acao=listarCategoria_suporte&mensagemerro='.urlencode('ERRO ao deletar Categoria_suporte!'));
			}
		}

	break; 

	case LISTAR_CATEGORIA_SUPORTE: 

		include_once 'categoria_suporte_class.php';  
        $dados = $_REQUEST;  
        $retorno = array(); 
        $categoria_suporte = buscaCategoria_suporte($dados); 
        if(!isset($dados['filtro'])){
	        $retorno['dados'] = $categoria_suporte;
	        $dados['totalRecords'] = true;  
	        $total = buscaCategoria_suporte($dados); 
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
        include_once 'categoria_suporte_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $categoria_suporte = buscaCategoria_suporte(array('idcategoria_suporte'=>$id));
        $categoria_suporte = $categoria_suporte[0];

        $imgAntigo = $categoria_suporte[$tipo];
        deleteFiles('files/categoria_suporte/', $imgAntigo, array('','thumb_','original_'));
        $categoria_suporte[$tipo] = '';
        editCategoria_suporte($categoria_suporte);

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
		include_once("categoria_suporte_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$categoria_suporte = buscaCategoria_suporte(array('idcategoria_suporte' => $dados['idcategoria_suporte']));
			$categoria_suporte = $categoria_suporte[0];

			$ordem = $categoria_suporte['ordem'] - 1;

			$categoria_suporteAux = buscaCategoria_suporte(array('order' => $ordem));

			if (!empty($categoria_suporteAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idcategoria_suporte'] = $dados['idcategoria_suporte'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemCategoria_suporte($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idcategoria_suporte'] = $categoria_suporteAux[0]['idcategoria_suporte'];
				$dadosUpdate2['ordem'] = intval($categoria_suporte['ordem']);
				editOrdemCategoria_suporte($dadosUpdate2);
			}

			print json_encode($resultado);

		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case ALTERA_ORDEM_BAIXO:
		include_once("categoria_suporte_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$categoria_suporte = buscaCategoria_suporte(array('idcategoria_suporte' => $dados['idcategoria_suporte']));
			$categoria_suporte = $categoria_suporte[0];

			$ordem = $categoria_suporte['ordem'] + 1;

			$categoria_suporteAux = buscaCategoria_suporte(array('order' => $ordem));

			if (!empty($categoria_suporteAux)) {
				$dadosUpdate = array();
				$dadosUpdate['idcategoria_suporte'] = $dados['idcategoria_suporte'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemCategoria_suporte($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idcategoria_suporte'] = $categoria_suporteAux[0]['idcategoria_suporte'];
				$dadosUpdate2['ordem'] = intval($categoria_suporte['ordem']);
				editOrdemCategoria_suporte($dadosUpdate2);
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