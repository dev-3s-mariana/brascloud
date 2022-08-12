<?php @session_start();
	 // Versao do modulo: 2.20.130114

//require_once 'includes/verifica.php'; // checa user logado
	

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_BLOG_COMENTARIOS") || define("CADASTRO_BLOG_COMENTARIOS","cadastroBlog_comentarios");
defined("EDIT_BLOG_COMENTARIOS") || define("EDIT_BLOG_COMENTARIOS","editBlog_comentarios");
defined("DELETA_BLOG_COMENTARIOS") || define("DELETA_BLOG_COMENTARIOS","deletaBlog_comentarios");
defined("LISTAR_BLOG_COMENTARIOS") || define("LISTAR_BLOG_COMENTARIOS","listarBlog_comentarios");
defined("ALTERAR_STATUS_BLOG_COMENTARIOS") || define("ALTERAR_STATUS_BLOG_COMENTARIOS","editStatusBlog_comentarios");

switch ($opx) {

	case CADASTRO_BLOG_COMENTARIOS:
		include_once 'blog_comentarios_class.php';
      include_once 'includes/functions.php';
      include_once 'includes/fileImage.php';

      $dados = $_REQUEST;
		$dados['status'] = 1;
        $idg = "";
		if(isset($dados['idg'])){
			$idg = "&idg=".$dados['idg'];
		}

      $imagem = $_FILES['imagem'];

      if(isset($imagem) && $imagem['error'] == 0 && !empty($imagem['tmp_name'])){
         $nomeimagem = fileImage("blog/comentarios", '', '', $imagem, 87, 87, 'resize');

         $caminho = 'files/blog/comentarios/'.$nomeimagem;
         compressImage($caminho);

         $dados['imagem'] = $nomeimagem;
      }else{
         $dados['imagem'] = "";
      }
        
		$idBlog_comentarios = cadastroBlog_comentarios($dados); 

		if (is_int($idBlog_comentarios)) {
			if($dados['ajax']){
				echo '{"status":true}';
			}else{
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'blog_comentarios';
				$log['descricao'] = 'Cadastrou blog_comentarios ID('.$idBlog_comentarios.') nome ('.$dados['nome'].') email ('.$dados['email'].') comentario ('.$dados['comentario'].') idpost ('.$dados['idpost'].') status ('.$dados['status'].') data ('.$dados['data'].')';
				$log['request'] = $_REQUEST;
				novoLog($log);
				//exit;
				header ('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemalerta='.urlencode('Comentário criado com sucesso!'));
			} 
		} else {
			if($dados['ajax']){
				echo '{"status":false}';
			}else{
				header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('ERRO ao criar novo comentário!'));
			} 
		}


	break;

	case EDIT_BLOG_COMENTARIOS:
		include_once 'blog_comentarios_class.php';

		$dados = $_REQUEST; 
		$antigo = buscaBlog_comentarios(array('idblog_comentarios'=>$dados['idblog_comentarios']));
		$antigo = $antigo[0];

		$idBlog_comentarios = editBlog_comentarios($dados);

		echo $idBlog_comentarios;

		$idg = "";
		if(isset($dados['idg'])){
			$idg = "&idg=".$dados['idg'];
		}

		if ($idBlog_comentarios != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'blog_comentarios';
			$log['descricao'] = 'Editou blog_comentarios ID('.$idBlog_comentarios.') DE  nome ('.$antigo['nome'].') email ('.$antigo['email'].') comentario ('.$antigo['comentario'].') idpost ('.$antigo['idpost'].') status ('.$antigo['status'].') data ('.$antigo['data'].') PARA  nome ('.$dados['nome'].') email ('.$dados['email'].') comentario ('.$dados['comentario'].') idpost ('.$dados['idpost'].') status ('.$dados['status'].') data ('.$dados['data'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemalerta='.urlencode('Comentário salvo com sucesso!'));
		} else {
			header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('ERRO ao salvar comentário!'));
		}

	break;

	case DELETA_BLOG_COMENTARIOS:
		include_once 'blog_comentarios_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaBlog_comentarios(array('idblog_comentarios'=>$dados['idu']));
		$antigo = $antigo[0];

		$idg = "";
		if(isset($dados['idg'])){
			$idg = "&idg=".$dados['idg'];
		}

		if (!verificaPermissaoAcesso('blog_comentarios_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('Você não tem privilegios para executar esta ação!'));
			exit;
		} else {
			 

			if (deletaBlog_comentarios($dados['idu']) == 1) {
                $imgAntigo = $antigo['imagem'];
                deleteFiles('files/blog/comentarios/', $imgAntigo, array('','thumb_','original_'));

				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'blog_comentarios';
				$log['descricao'] = 'Deletou blog_comentarios ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemalerta='.urlencode('Comentário deletado com sucesso!'));
			} else {
				header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('ERRO ao deletar comentário!'));
			}
		}

	break;
	case ALTERAR_STATUS_BLOG_COMENTARIOS:
		include_once 'blog_comentarios_class.php';
		include_once 'usuario_class.php';

		$dados = $_REQUEST;

		$idg = "";
		if(isset($dados['idg'])){
			$idg = "&idg=".$dados['idg'];
		}

		if (!verificaPermissaoAcesso('blog_comentarios_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('Você não tem privilegios para executar esta ação!'));
			exit;
		} else { 
			//Adpataçao Para funcionamento do status pelo botão
			$dados['idblog_comentarios']= $dados['idu']; 
			if($dados['status3'] == 1 or $dados['status3'] == 3){
				$dados['status']= 2;  
			}else{
				$dados['status']= 3; 
			}  
			
			$idBlog_comentarios = alterarStatusBlog_comentarios($dados);
			if ($idBlog_comentarios !=  FALSE) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'blog_comentarios';
				$log['descricao'] = 'Editou blog_comentarios ID('.$idBlog_comentarios.') DE  status ('.$dados['status3'].') PARA  status ('.$dados['status'].')';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemalerta='.urlencode('Status atualizado com sucesso!'));
			} else {
				header('Location: ../admin/?mod=blog_comentarios&acao=listarBlog_comentarios'.$idg.'&mensagemerro='.urlencode('ERRO ao atualizar status do comentário!'));
			}
		} 
	break;

	case LISTAR_BLOG_COMENTARIOS:
        
		include_once 'blog_comentarios_class.php'; 
        
        $dados = $_REQUEST;

        $retorno = array();
        $blog_comentarios = buscaBlog_comentarios($dados); 

        if(!isset($dados['filtro'])){
	        $retorno['dados'] = $blog_comentarios;
	        $dados['totalRecords'] = true; 
	        $total = buscaBlog_comentarios($dados); 
	        $retorno['total'] = 0;  
	                      
	        if(count($total) > 0 && isset($dados['limit'])){
	            $paginas = ceil($total[0]['totalRecords']/ $dados['limit']);
	            $retorno['totalPaginas'] = $paginas;
	        } 
    	}
        print json_encode($retorno);
	break;

	default:
		if (!headers_sent() && (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
			header('Location: ../admin/?mod=home&mensagemerro='.urlencode('Nenhuma ação definida...'));
		} else {
			trigger_error('Erro...', E_USER_ERROR);
			exit;
		}

}
?>