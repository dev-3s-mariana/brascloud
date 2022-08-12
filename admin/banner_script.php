<?php
    // Versao do modulo: 2.20.130114

    require_once 'includes/verifica.php'; // checa user logado
     
    if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

    $opx = $_REQUEST["opx"];

    defined("CADASTRO_BANNER") || define("CADASTRO_BANNER","cadastroBanner");
    defined("EDIT_BANNER") || define("EDIT_BANNER","editBanner");
    defined("DELETA_BANNER") || define("DELETA_BANNER","deletaBanner");
    defined("ALTERA_ORDEM_CIMA") || define("ALTERA_ORDEM_CIMA","alteraOrdemCima");
    defined("ALTERA_ORDEM_BAIXO") || define("ALTERA_ORDEM_BAIXO","alteraOrdemBaixo");
    defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
    defined("DELETA_CADASTRO_TEMPORARIO") || define("DELETA_CADASTRO_TEMPORARIO","deletaCadastroTemporario");
    defined("BUSCA_BANNER") || define("BUSCA_BANNER","buscaBanner");
    defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");
    defined("CANCELAR_IMAGEM") || define("CANCELAR_IMAGEM","cancelarImagem");
    defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");

    switch ($opx) {

    	case CADASTRO_BANNER:
    		include_once 'banner_class.php';

    		$dados = $_REQUEST;

    		$idtemporario = $dados['idbanner'];
    		$idbanner = cadastroBanner($dados);

    		if(!is_numeric($idtemporario) && file_exists('files/banner/'.$idtemporario.'/')){
    			rename('files/banner/'.$idtemporario, 'files/banner/'.$idbanner);
    		}

    		if ($idbanner) {
    			//salva log
    			include_once 'log_class.php';
    			$log['idusuario'] = $_SESSION['sgc_idusuario'];
    			$log['modulo'] = 'banner';
    			$log['descricao'] = 'Cadastrou banner ID('.$idbanner.') nome ('.$dados['nome'].') status('.$dados['status'].') link ('.$dados['link'].') banner_full ('.$dados['banner_full'].') banner_mobile ('.$dados['banner_mobile'].')';
    			$log['request'] = $_REQUEST;
    			novoLog($log);
    			header('Location: index.php?mod=banner&acao=listarBanner&mensagemalerta='.urlencode('banner criado com sucesso!'));
    		} else {
    			header('Location: index.php?mod=banner&acao=listarBanner&mensagemerro='.urlencode('ERRO ao criar novo banner!'));
    		}

    	break;

    	case EDIT_BANNER:

    		include_once 'banner_class.php';

    		$dados = $_REQUEST;

    		$antigo = buscaBanner(array('idbanner'=>$dados['idbanner']));
    		$antigo = $antigo[0];

    		$idbanner = editBanner($dados);

    		if ($idbanner != FALSE) {
    			//salva log
    			include_once 'log_class.php';
    			$log['idusuario'] = $_SESSION['sgc_idusuario'];
    			$log['modulo'] = 'banner';
    			$log['descricao'] = 'Editou banner ID('.$idBanner.') DE  nome ('.$antigo['nome'].') status ('.$antigo['status'].') link('.$antigo['link'].') banner_full ('.$antigo['banner_full'].') banner_mobile ('.$antigo['banner_mobile'].') PARA  nome ('.$dados['nome'].') status ('.$dados['status'].') banner_full ('.$dados['banner_full'].') banner_mobile ('.$dados['banner_mobile'].') link('.$dados['link'].')';
    			$log['request'] = $_REQUEST;
    			novoLog($log);
    			header('Location: index.php?mod=banner&acao=listarBanner&mensagemalerta='.urlencode('Banner salvo com sucesso!'));
    		} else {
    			header('Location: index.php?mod=banner&acao=listarBanner&mensagemerro='.urlencode('ERRO ao salvar Banner!'));
    		}

    	break;

    	case DELETA_BANNER:
    		include_once 'banner_class.php';
    		include_once 'usuario_class.php';
            include_once 'includes/functions.php';

    		if (!verificaPermissaoAcesso('banner_deletar', $_SESSION['sgc_idusuario'])){
    			header('Location: index.php?mod=banner&acao=listarbanner&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
    			exit;
    		} else {
    			$dados = $_REQUEST;
    			$antigo = buscaBanner(array('idbanner'=>$dados['idu']));
    			$antigo = $antigo[0];
    			$antigo['idbanner'];

    			if (deletaBanner($dados['idu']) == 1) {
                    $imgAntigo = array($antigo['banner_full'], $antigo['banner_mobile']);
                    deleteFiles('files/banner/', $imgAntigo, array('','thumb_','original_'));
    				//salva log
    				include_once 'log_class.php';
    				$log['idusuario'] = $_SESSION['sgc_idusuario'];
    				$log['modulo'] = 'banner';
    				$log['descricao'] = 'Deletou banner ID('.$dados['idu'].') ';
    				$log['request'] = $_REQUEST;
    				novoLog($log);
    				header('Location: index.php?mod=banner&acao=listarBanner&mensagemalerta='.urlencode('Banner deletado com sucesso!'));
    			} else {
    				header('Location: index.php?mod=banner&acao=listarBanner&mensagemerro='.urlencode('ERRO ao deletar Banner!'));
    			}
    		}

    	break;

        case DELETA_CADASTRO_TEMPORARIO:

    		include_once('banner_class.php');
    		$dados = $_POST; 
     		 
    		print '{"status":true}';

    	break;

        case ALTERA_ORDEM_CIMA:
    		include_once("banner_class.php");

    		$dados = $_REQUEST; 
    		$resultado['status'] = 'sucesso';

    		try {

                $banner = buscaBanner(array('idbanner'=>$dados['idbanner']));
     			$banner = $banner[0];
    			$ordemAux = 0;
    			$ordem = $banner['ordem'];

    			while($ordemAux == 0)
    			{
    				 $ordem = $ordem - 1;
    				 $bannerAux = buscaBanner(array('order'=>$ordem));

    				 if(!empty($bannerAux)){
    				 	$bannerAux = $bannerAux[0];
    				 	$ordemAux = $bannerAux['ordem'];
    				 }
    			}

    			if(!empty($bannerAux)){

    				$bannerAux['ordem'] = $banner['ordem'];
    				$banner['ordem'] = $ordemAux;

    				editBanner($banner);
    				editBanner($bannerAux);
    			 }

    			print json_encode($resultado);

    		} catch (Exception $e) {
        		$resultado['status'] = 'falha';
    			print json_encode($resultado);
    		}
    	break;

        case ALTERA_ORDEM_BAIXO:
    		include_once("banner_class.php");

    		$dados = $_REQUEST; 
    		$resultado['status'] = 'sucesso';

    		try {
    			$banner = buscaBanner(array('idbanner'=>$dados['idbanner']));
    			$banner = $banner[0];
    			$ordemAux = 0;
    			$ordem = $banner['ordem'];

    			while($ordemAux == 0)
    			{
    				 $ordem = $ordem + 1;
    				 $bannerAux = buscaBanner(array('order'=>$ordem));

    				 if(!empty($bannerAux)){
    				 	$bannerAux = $bannerAux[0];
    				 	$ordemAux = $bannerAux['ordem'];
    				 }

    			}

    			if(!empty($bannerAux)){
    				$bannerAux['ordem'] = $banner['ordem'];
    				$banner['ordem'] = $ordemAux;

    				editBanner($banner);
    				editBanner($bannerAux);
    			 }

    			print json_encode($resultado);

    		} catch (Exception $e) {
        		$resultado['status'] = 'falha';
    			print json_encode($resultado);
    		}
    	break;

        case SALVA_IMAGEM:
            include_once('banner_class.php');
            include_once 'includes/fileImage.php';
            include_once 'includes/functions.php';

            $dados = $_POST;
            $imagem = $_FILES;

            if(!empty($dados['idbanner'])){
                $bannerOld = buscaBanner(array('idbanner'=>$dados['idbanner']));
                $bannerOld = $bannerOld[0];
            }

            if (isset($imagem['imagemCadastrar']) && $imagem['imagemCadastrar']['error'] == 0) {
                $coordenadas = getDataImageCrop($imagem, $dados['coordenadas']);
                $nomeimagem = fileImage("banner", "", '', $imagem['imagemCadastrar'], $dados['dimensaoWidth'], $dados['dimensaoHeight'], 'cropped', $coordenadas);
                // fileImage("banner", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

                $caminho = 'files/banner/'.$nomeimagem;
                compressImage($caminho);

                if(!empty($dados['idbanner'])){
                    if(!empty($bannerOld[$dados['tipo']])){
                        $apgImage = deleteFiles('files/banner/', $bannerOld[$dados['tipo']], array('', 'thumb_', 'original_'));
                        $bannerOld[$dados['tipo']] = $nomeimagem;
                        $edit = editBanner($bannerOld);
                    }else{
                        $bannerOld[$dados['tipo']] = $nomeimagem;
                        $edit = editBanner($bannerOld);
                    }
                }

                echo json_encode(array('status'=>true, 'imagem'=>$nomeimagem));
            }else{
                echo json_encode(array('status'=>false, 'msg'=>'Erro ao salvar imagem. Tente novamente'));
            }
        break;

    	case BUSCA_BANNER:

    		include_once('banner_class.php');
    		$dados = $_POST;
    		$banner = buscaBanner(array("status"=>"A","ordem"=>"ordem asc"));
    		print json_encode($banner);

    		break;
    		
    	case INVERTE_STATUS:
    		include_once("banner_class.php");
    		$dados = $_REQUEST;
    		// inverteStatus($dados);
    		$resultado['status'] = 'sucesso';
    		include_once("includes/functions.php");
    		$tabela = 'banner';
    		$id = 'idbanner';

    		try {
    			$banner = buscaBanner(array('idbanner' => $dados['idbanner']));
    			$banner = $banner[0];

    			// print_r($banner);
    			if($banner['status'] == 1){
    				$status = 0;
    			}
    			else{
    				$status = 1;
    			}

    			$dadosUpdate = array();
    			$dadosUpdate['idbanner'] = $dados['idbanner'];
    			$dadosUpdate['status'] = $status;
    			inverteStatus($dadosUpdate,$tabela,$id);

    			print json_encode($resultado);
    		} catch (Exception $e) {
    			$resultado['status'] = 'falha';
    			print json_encode($resultado);
    		}
    	break;
    		
        case EXCLUIR_IMAGEM:
            include_once 'banner_class.php';
            include_once 'includes/functions.php';

            $dados = $_REQUEST;
            $id = $dados['id'];
            $tipo = $dados['tipo'];
            $img = $dados['img'];
            $banner = buscaBanner(array('idbanner'=>$id));
            $banner = $banner[0];

            $imgAntigo = $banner[$tipo];
            deleteFiles('files/banner/', $imgAntigo, array('','thumb_','original_'));
            $banner[$tipo] = '';
            editBanner($banner);

            echo json_encode(array('status'=>true));

        break;

        case CANCELAR_IMAGEM:
            $dados = $_REQUEST;

            if(file_exists('files/'.$dados['pasta'].'/'.$dados['imagem'])){
                unlink('files/'.$dados['pasta'].'/'.$dados['imagem']);
            }

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