<?php 
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST["opx"]) || $_REQUEST["opx"] != "listarBlog_tags"){
	require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_BLOG_TAGS") || define("CADASTRO_BLOG_TAGS","cadastroBlog_tags");
defined("EDIT_BLOG_TAGS") || define("EDIT_BLOG_TAGS","editBlog_tags");
defined("DELETA_BLOG_TAGS") || define("DELETA_BLOG_TAGS","deletaBlog_tags");
defined("LISTAR_BLOG_TAGS") || define("LISTAR_BLOG_TAGS","listarBlog_tags");
defined("PESQUISA_ICONE") || define("PESQUISA_ICONE", "pesquisaIcone");
defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA", "alteraOrdemCima");
defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO", "alteraOrdemBaixo");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");
defined("VERIFICAR_URLREWRITE") || define("VERIFICAR_URLREWRITE", "verificarUrlRewrite");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

switch ($opx) {

	case CADASTRO_BLOG_TAGS:
		include_once 'blog_tags_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		
        $imagem = $_FILES;

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("blog_tags", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/blog_tags/'.$nomeicone;
            compressImage($caminho);

            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("blog_tags", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("blog_tags", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/blog_tags/'.$nomeimagem;
            compressImage($caminho);

            $dados['imagem'] = $nomeimagem;
        }

		$idBlog_tags = cadastroBlog_tags($dados);

		if (is_int($idBlog_tags)) {  
			 
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'blog_tags';
			$log['descricao'] = 'Cadastrou blog_tags ID('.$idBlog_tags.') título ('.$dados['titulo'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemalerta='.urlencode('Blog_tags criado com sucesso!'));
		} else {
			header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemerro='.urlencode('ERRO ao criar novo Blog_tags!'));
		}

	break;

	case EDIT_BLOG_TAGS:
		include_once 'blog_tags_class.php';
		include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
        $imagem = $_FILES;
        $antigo = buscaBlog_tags(array('idblog_tags'=>$dados['idblog_tags']));
		$antigo = $antigo[0]; 

        if (isset($_FILES['icone']) && $_FILES['icone']['error'] == 0) {
            $nomeicone = fileImage("blog_tags", "", '', $imagem['icone'], $dados['aspectRatioW2'], $dados['aspectRatioH2'], 'inside');

            $caminho = 'files/blog_tags/'.$nomeicone;
            compressImage($caminho);
            
            // editarImagemBlog_tags($antigo['icone']);  
            $imgAntigo = $antigo['icone'];
            deleteFiles('files/blog_tags/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['icone'] = $nomeicone;
        }

        if (isset($_FILES['imagemCadastrar']) && $_FILES['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $_POST['coordenadas']);
            $nomeimagem = fileImage("blog_tags", "", '', $imagem['imagemCadastrar'], $dados['aspectRatioW'], $dados['aspectRatioH'], 'cropped', $coordenadas);
            fileImage("blog_tags", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/blog_tags/'.$nomeimagem;
            compressImage($caminho);

            // apagarImagemEquipe($antigo['imagem']);  
            $imgAntigo = $antigo['imagem'];
            deleteFiles('files/blog_tags/', $imgAntigo, array('','thumb_','thumb2_','original_'));
            $dados['imagem'] = $nomeimagem;
        }

		$idBlog_tags = editBlog_tags($dados);

		if ($idBlog_tags != FALSE) { 

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'blog_tags';
			$log['descricao'] = 'Editou blog_tags ID('.$idBlog_tags.') DE  título ('.$antigo['titulo'].') texto ('.$antigo['texto'].') imagem ('.$antigo['imagem'].') icone ('.$antigo['icone'].') ordem ('.$antigo['ordem'].') PARA  nome ('.$dados['nome'].') texto ('.$dados['texto'].') imagem ('.$dados['imagem'].') icone ('.$dados['icone'].') ordem ('.$dados['ordem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemalerta='.urlencode('Blog_tags salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemerro='.urlencode('ERRO ao salvar Blog_tags!'));
		}

	break;

	case DELETA_BLOG_TAGS:
		include_once 'blog_tags_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('blog_tags_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaBlog_tags(array('idblog_tags'=>$dados['idu']));

			if (deletaBlog_tags($dados['idu']) == 1) {
				// editarImagemBlog_tags($antigo[0]['icone']);  
                $imgAntigo = $antigo[0]['icone'];
                $imgAntigo2 = $antigo[0]['imagem'];
                deleteFiles('files/blog_tags/', $imgAntigo, array('','thumb_','thumb2_','original_'));
                deleteFiles('files/blog_tags/', $imgAntigo2, array('','thumb_','thumb2_','original_'));
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'blog_tags';
				$log['descricao'] = 'Deletou blog_tags ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemalerta='.urlencode('Blog_tags deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=blog_tags&acao=listarBlog_tags&mensagemerro='.urlencode('ERRO ao deletar Blog_tags!'));
			}
		}

	break; 

	case LISTAR_BLOG_TAGS: 

		include_once 'blog_tags_class.php';  
        $dados = $_REQUEST;  
        $retorno = array(); 
        $blog_tags = buscaBlog_tags($dados); 
        if(!isset($dados['filtro'])){
	        $retorno['dados'] = $blog_tags;
	        $dados['totalRecords'] = true;  
	        $total = buscaBlog_tags($dados); 
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
        include_once 'blog_tags_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $blog_tags = buscaBlog_tags(array('idblog_tags'=>$id));
        $blog_tags = $blog_tags[0];

        $imgAntigo = $blog_tags[$tipo];
        deleteFiles('files/blog_tags/', $imgAntigo, array('','thumb_','original_'));
        $blog_tags[$tipo] = '';
        editBlog_tags($blog_tags);

        echo json_encode(array('status'=>true));

    break;

    case VERIFICAR_URLREWRITE:

        include_once('blog_tags_class.php');
        include_once('blog_categoria_class.php');
        include_once('blog_post_class.php');
        include_once('includes/functions.php');

        $dados = $_POST;

        $urlrewrite = converteUrl(utf8_encode(str_replace(" - ", " ", $dados['urlrewrite'])));
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $urlrewrite)));
        // echo $urlrewrite;
        // exit;
        $url = buscaBlog_tags(array("urlrewrite" => $urlrewrite, "not_idblog_tags" => $dados['idblog_tags']));
        $url2 = buscaBlog_categoria(array("urlrewrite" => $urlrewrite));
        $url3 = buscaBlog_post(array("urlrewrite" => $urlrewrite));

        if (empty($url) && empty($url2) && empty($url3)) {
            print '{"status":true,"url":"' . $urlrewrite . '"}';
        } else {
            print '{"status":false,"msg":"Url já cadastrada"}';
        }

    break;

    case INVERTE_STATUS:
            include_once("blog_tags_class.php");
            $dados = $_REQUEST;
            // inverteStatus($dados);
            $resultado['status'] = 'sucesso';
            include_once("includes/functions.php");
            $tabela = 'blog_tags';
            $id = 'idblog_tags';

            try {
                $blog_tags = buscaBlog_tags(array('idblog_tags' => $dados['idblog_tags']));
                $blog_tags = $blog_tags[0];

                // print_r($blog_tags);
                if($blog_tags['status'] == 1){
                    $status = 2;
                }
                else{
                    $status = 1;
                }

                $dadosUpdate = array();
                $dadosUpdate['idblog_tags'] = $dados['idblog_tags'];
                $dadosUpdate['status'] = $status;
                inverteStatus($dadosUpdate,$tabela,$id);

                print json_encode($resultado);
            } catch (Exception $e) {
                $resultado['status'] = 'falha';
                print json_encode($resultado);
            }
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
		include_once("blog_tags_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$blog_tags = buscaBlog_tags(array('idblog_tags' => $dados['idblog_tags']));
			$blog_tags = $blog_tags[0];

			$ordem = $blog_tags['ordem'] - 1;

			$blog_tagsAux = buscaBlog_tags(array('order' => $ordem));

			if (!empty($blog_tagsAux)) {

				$dadosUpdate = array();
				$dadosUpdate['idblog_tags'] = $dados['idblog_tags'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemBlog_tags($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idblog_tags'] = $blog_tagsAux[0]['idblog_tags'];
				$dadosUpdate2['ordem'] = intval($blog_tags['ordem']);
				editOrdemBlog_tags($dadosUpdate2);
			}

			print json_encode($resultado);

		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

	case ALTERA_ORDEM_BAIXO:
		include_once("blog_tags_class.php");

		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';

		try {

			$blog_tags = buscaBlog_tags(array('idblog_tags' => $dados['idblog_tags']));
			$blog_tags = $blog_tags[0];

			$ordem = $blog_tags['ordem'] + 1;

			$blog_tagsAux = buscaBlog_tags(array('order' => $ordem));

			if (!empty($blog_tagsAux)) {
				$dadosUpdate = array();
				$dadosUpdate['idblog_tags'] = $dados['idblog_tags'];
				$dadosUpdate['ordem'] = $ordem;
				editOrdemBlog_tags($dadosUpdate);

				$dadosUpdate2 = array();
				$dadosUpdate2['idblog_tags'] = $blog_tagsAux[0]['idblog_tags'];
				$dadosUpdate2['ordem'] = intval($blog_tags['ordem']);
				editOrdemBlog_tags($dadosUpdate2);
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