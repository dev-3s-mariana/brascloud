<?php 
	 // Versao do modulo: 2.20.130114

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_BLOG_CATEGORIA") || define("CADASTRO_BLOG_CATEGORIA","cadastroBlog_categoria");
defined("EDIT_BLOG_CATEGORIA") || define("EDIT_BLOG_CATEGORIA","editBlog_categoria");
defined("DELETA_BLOG_CATEGORIA") || define("DELETA_BLOG_CATEGORIA","deletaBlog_categoria");
defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("VERIFICAR_URLREWRITE") || define("VERIFICAR_URLREWRITE", "verificarUrlRewrite");

switch ($opx) {

	case CADASTRO_BLOG_CATEGORIA:
		include_once 'blog_categoria_class.php';

		$dados = $_REQUEST;
		$idBlog_categoria = cadastroBlog_categoria($dados);

		if (is_int($idBlog_categoria)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'blog_categoria';
			$log['descricao'] = 'Cadastrou blog_categoria ID('.$idBlog_categoria.') nome ('.$dados['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemalerta='.urlencode('Blog_categoria criado com sucesso!'));
		} else {
			header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemerro='.urlencode('ERRO ao criar novo Blog_categoria!'));
		}

	break;

	case EDIT_BLOG_CATEGORIA:
		include_once 'blog_categoria_class.php';

		$dados = $_REQUEST;
		$antigo = buscaBlog_categoria(array('idblog_categoria'=>$dados['idblog_categoria']));
		$antigo = $antigo[0];

		$idBlog_categoria = editBlog_categoria($dados);

		if ($idBlog_categoria != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'blog_categoria';
			$log['descricao'] = 'Editou blog_categoria ID('.$idBlog_categoria.') DE  nome ('.$antigo['nome'].') urlrewrite ('.$antigo['urlrewrite'].') status ('.$antigo['status'].') PARA  nome ('.$dados['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemalerta='.urlencode('Blog_categoria salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemerro='.urlencode('ERRO ao salvar Blog_categoria!'));
		}

	break;

	case DELETA_BLOG_CATEGORIA:
		include_once 'blog_categoria_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('blog_categoria_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaBlog_categoria(array('idblog_categoria'=>$dados['idu']));

			if (deletaBlog_categoria($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'blog_categoria';
				$log['descricao'] = 'Deletou blog_categoria ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemalerta='.urlencode('Blog_categoria deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=blog_categoria&acao=listarBlog_categoria&mensagemerro='.urlencode('ERRO ao deletar Blog_categoria!'));
			}
		}

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
        $url = buscaBlog_categoria(array("urlrewrite" => $urlrewrite, "not_idblog_categoria" => $dados['idblog_categoria']));
        $url2 = buscaBlog_tags(array("urlrewrite" => $urlrewrite));
        $url3 = buscaBlog_post(array("urlrewrite" => $urlrewrite));

        if (empty($url) && empty($url2) && empty($url3)) {
            print '{"status":true,"url":"' . $urlrewrite . '"}';
        } else {
            print '{"status":false,"msg":"Url já cadastrada"}';
        }

    break;

	case INVERTE_STATUS:
		include_once("blog_categoria_class.php");
		$dados = $_REQUEST;
		$resultado['status'] = 'sucesso';
		include_once("includes/functions.php");
		$tabela = 'blog_categoria';
		$id = 'idblog_categoria';

		try {
			$blog_categoria = buscaBlog_categoria(array('idblog_categoria' => $dados['idblog_categoria']));
			$blog_categoria = $blog_categoria[0];

			// print_r($blog_categoria);
			if($blog_categoria['status'] == 1){
				$status = 0;
			}
			else{
				$status = 1;
			}

			$dadosUpdate = array();
			$dadosUpdate['idblog_categoria'] = $dados['idblog_categoria'];
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