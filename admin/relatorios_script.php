<?php 
	 // Versao do modulo: 3.00.010416
require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_RELATORIOS") || define("CADASTRO_RELATORIOS","cadastroRelatorios");
defined("EDIT_RELATORIOS") || define("EDIT_RELATORIOS","editRelatorios");
defined("DELETA_RELATORIOS") || define("DELETA_RELATORIOS","deletaRelatorios");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_ARQUIVO") || define("EXCLUIR_ARQUIVO","excluirArquivo");   

switch ($opx) {

    case EXCLUIR_ARQUIVO: 
        include_once('relatorios_class.php');
        include_once('includes/functions.php');
        $dados = $_POST;

        // print_r($dados);die;
        $return = excluirArquivoRelatorios($dados);

        if($return == true){
           echo json_encode(array('status'=>true));
        }else{
           echo json_encode(array('status'=>false));
        }
    break; 

	case CADASTRO_RELATORIOS:
		include_once 'relatorios_class.php';
        include_once('includes/functions.php');

		$dados = $_REQUEST;

        $arquivo = $_FILES['arquivo'];
        if(isset($arquivo['name']) && $arquivo['error'] == 0){
            $nome = $arquivo['name'];
            $nome = explode(".",$nome); 
            $ext = $nome[count($nome) - 1];
            $nomeArquivo1 = uniqid().'-'.converteUrl($nome[0]).".".$ext;
        } 

		$idRelatorios = cadastroRelatorios($dados);

        if(!empty($nomeArquivo1)){
            if(!file_exists("files/relatorios/arquivos/")){
                mkdir("files/relatorios/arquivos/",0777);
            }

            if(move_uploaded_file($arquivo['tmp_name'], "files/relatorios/arquivos/".$nomeArquivo1)){ 
                $dados['arquivo'] = $nomeArquivo1;
                $dados['idrelatorios'] = $idRelatorios;  
                $edit = editRelatorios($dados); 
            } 
        }

		if (is_int($idRelatorios)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'relatorios';
			$log['avaliacao'] = 'Cadastrou relatorios ID('.$idRelatorios.') titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].') data ('.$dados['data'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemalerta='.urlencode('Relatorios criado com sucesso!'));
		} else {
			header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemerro='.urlencode('ERRO ao criar novo Relatorios!'));
		}

		break;

	case EDIT_RELATORIOS:
		include_once 'relatorios_class.php';
        include_once('includes/functions.php');

		$dados = $_REQUEST;
		$antigo = buscaRelatorios(array('idrelatorios'=>$dados['idrelatorios']));
		$antigo = $antigo[0];

        $arquivo = $_FILES['arquivo'];
        if(isset($arquivo['name']) && $arquivo['error'] == 0){
            $nome = $arquivo['name'];
            $nome = explode(".",$nome); 
            $ext = $nome[count($nome) - 1];
            $nomeArquivo1 = uniqid().'-'.converteUrl($nome[0]).".".$ext;
        }
        else{
            $dados['arquivo'] = $antigo['arquivo'];
        }

        if(!empty($nomeArquivo1)){
            if(move_uploaded_file($arquivo['tmp_name'], "files/relatorios/arquivos/".$nomeArquivo1)){ 
                excluirArquivoRelatorios(array('colunaBD'=>'arquivo', 'idrelatorios'=>$dados['idrelatorios'], 'arquivo'=>$antigo['arquivo']));
                $dados['arquivo'] = $nomeArquivo1;
            } 
        }

		$idRelatorios = editRelatorios($dados);

		if ($idRelatorios != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'relatorios';
			$log['avaliacao'] = 'Editou relatorios ID('.$idRelatorios.') DE  titulo ('.$antigo['titulo'].')imagem ('.$antigo['imagem'].') status ('.$antigo['status'].') data ('.$dados['data'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].') data ('.$dados['data'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemalerta='.urlencode('Relatorios salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemerro='.urlencode('ERRO ao salvar Relatorios!'));
		}

		break;

	case DELETA_RELATORIOS:
		include_once 'relatorios_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('relatorios_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaRelatorios(array('idrelatorios'=>$dados['idu']));
			// apagarImagemRelatorios($antigo[0]['imagem']);

			if (deletaRelatorios($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'relatorios';
				$log['avaliacao'] = 'Deletou relatorios ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemalerta='.urlencode('Relatorios deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=relatorios&acao=listarRelatorios&mensagemerro='.urlencode('ERRO ao deletar Relatorios!'));
			}
		}

	break;

	case SALVA_IMAGEM:
		include_once('relatorios_class.php');
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

        $dados = $_POST;

        $idrelatorios = $dados['idrelatorios'];
        if(array_key_exists('imagem_old', $dados)){
            $imgAntigo = $dados['imagem_old'];
        }

        $imagem = $_FILES;

        $antigo = array();
        if(!empty($idplataforma) &&  $idplataforma > 0){
            $antigo = buscaRelatorios(array('idrelatorios'=>$idrelatorios));
            $antigo = $antigo[0];
        }

        $width = $dados['dimensaoWidth'];
        $height = $dados['dimensaoHeigth'];

        //imagem
        $nomeimagem = fileImage("relatorios", "", "", $imagem['imagem'], 125, 125, 'resize');
        // $nomeimagem = fileImage("relatorios", $nomeimage, "", $imagem['imagem'], $width, $height, 'crop');

        $caminho = 'files/relatorios/'.$nomeimagem;

        compressImage($caminho);

        if(file_exists($caminho)){
            //apaga os arquivos anteriores que foram salvos
            if(!empty($imgAntigo)){
                $apgImage = apagarImagemRelatorios($imgAntigo);
            }
            if(is_numeric($idrelatorios) && $idrelatorios > 0){
                //edita o nome do banner, pois se alterar e cancelar - ja trocou a imagem. // para evitar de ficar sem imagem
                $relatorios = $antigo;
                if(array_key_exists('imagem_old', $dados)){
                    $relatorios['imagem'] = $nomeimagem;
                }
            }
            echo '{"status":true, "caminho":"'.$caminho.'", "idrelatorios":"'.$idrelatorios.'", "nome_arquivo":"'.$nomeimagem.'"}';
        }else{
            echo '{"status":false, "idrelatorios":"'.$idrelatorios.'", "msg":"erro ao salvar a imagem. Tente novamente"}';
        }
	break;

	case INVERTE_STATUS:
		include_once("relatorios_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'relatorios';
		$id = 'idrelatorios';

		try {
			$relatorios = buscaRelatorios(array('idrelatorios' => $dados['idrelatorios']));
			$relatorios = $relatorios[0];

			// print_r($depoimento);
			if($relatorios['status'] == 1){
				$status = 0;
			}
			else{
				$status = 1;
			}

			$dadosUpdate = array();
			$dadosUpdate['idrelatorios'] = $dados['idrelatorios'];
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