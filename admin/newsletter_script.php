<?php 
	 // Versao do modulo: 3.00.010416
if (isset($_SESSION['ajax'])) {
	
}else{
require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_NEWSLETTER") || define("CADASTRO_NEWSLETTER","cadastroNewsletter");
defined("EDIT_NEWSLETTER") || define("EDIT_NEWSLETTER","editNewsletter");
defined("DELETA_NEWSLETTER") || define("DELETA_NEWSLETTER","deletaNewsletter");

switch ($opx) {

	case CADASTRO_NEWSLETTER:
		include_once 'newsletter_class.php';




		$dados = $_REQUEST;
		




		$idNewsletter = cadastroNewsletter($dados);

		if (is_int($idNewsletter)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'newsletter';
			$log['descricao'] = 'Cadastrou newsletter ID('.$idNewsletter.') email ('.$dados['email'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			echo 1;
		} else {
			echo 0;
		}

	break;

	case EDIT_NEWSLETTER:
		include_once 'newsletter_class.php';

		$dados = $_REQUEST;
		$antigo = buscaNewsletter(array('idnewsletter'=>$dados['idnewsletter']));
		$antigo = $antigo[0];

			
    	if(!empty($_FILES['arquivo']['name']))
		   {

		   	$str = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNoO1234567890pqrstuv";
		    $rand = str_shuffle($str);
		    $idcliente = $dados['idnewsletter_categoria'];
		      date_default_timezone_set("Brazil/East");
		      $tempo = date('Y-m-d H:i:s');
		      $arq = $_FILES['arquivo'];
		      $tamanho = $_FILES['arquivo']['size'];
		      $name = $_FILES['arquivo']['name'];
		      $ext = strtolower(substr($_FILES['arquivo']['name'],-4)); //Pegando extensão do arquivo
		      $new_name =$rand.$ext; //Definindo um novo nome para o arquivo
		      $diretorio = 'newsletter/'; //Diretório para uploads
		    
		   
		     move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$new_name); //Fazer upload do arquivo

		    $tamanho = sizeFilter( $tamanho );
			$url = $diretorio.$new_name;
		      $dados['arquivo'] = $url;
		      $dados['tamanho'] = $tamanho;
		      $dados['campo'] = $ext;
		      $dados['data'] = $tempo;


		    }

		$idNewsletter = editNewsletter($dados);

		if ($idNewsletter != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'newsletter';
			$log['descricao'] = 'Editou newsletter ID('.$idNewsletter.') DE  titulo ('.$antigo['titulo'].') arquivo ('.$antigo['arquivo'].') tamanho ('.$antigo['tamanho'].') PARA  titulo ('.$dados['titulo'].') arquivo ('.$dados['arquivo'].') tamanho ('.$dados['tamanho'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=newsletter&acao=listarNewsletter&mensagemalerta='.urlencode('Newsletter salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=newsletter&acao=listarNewsletter&mensagemerro='.urlencode('ERRO ao salvar Newsletter!'));
		}

	break;

	case DELETA_NEWSLETTER:
		include_once 'newsletter_class.php';
		include_once 'usuario_class.php';
		$antigo = buscaNewsletter(array('idnewsletter'=>$dados['idu']));
		$idg = $antigo[0]['idcliente'];

		if (!verificaPermissaoAcesso('newsletter_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=newsletter&acao=listarNewsletter&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {


			$dados = $_REQUEST;   		


			$antigo = buscaNewsletter(array('idnewsletter'=>$dados['idu']));

			if (deletaNewsletter($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'newsletter';
				$log['descricao'] = 'Deletou newsletter ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);

				


				header('Location: index.php?mod=newsletter&acao=listarNewsletter&mensagemalerta='.urlencode('Newsletter deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=newsletter&acao=listarNewsletter&mensagemerro='.urlencode('ERRO ao deletar Newsletter!'));
			}
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