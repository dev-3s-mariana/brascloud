<?php 
	 // Versao do modulo: 3.00.010416
if(!isset($_REQUEST["ajax"]) || empty($_REQUEST["ajax"])){
    require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_PLANOS") || define("CADASTRO_PLANOS","cadastroPlanos");
defined("EDIT_PLANOS") || define("EDIT_PLANOS","editPlanos");
defined("DELETA_PLANOS") || define("DELETA_PLANOS","deletaPlanos");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("LISTA_PLANOS") || define("LISTA_PLANOS", "listaPlanos");
defined("LISTA_SUBCATEGORIAS") || define("LISTA_SUBCATEGORIAS", "listaSubcategorias");
defined("CANCELAR_IMAGEM") || define("CANCELAR_IMAGEM","cancelarImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");
//GALERIA
   defined("SALVA_GALERIA") || define("SALVA_GALERIA","salvarGaleria");
   defined("SALVAR_DESCRICAO_IMAGEM") || define("SALVAR_DESCRICAO_IMAGEM","salvarDescricao");
   defined("EXCLUIR_IMAGEM_GALERIA") || define("EXCLUIR_IMAGEM_GALERIA","excluirImagemGaleria");
   defined("ALTERAR_POSICAO_IMAGEM") || define("ALTERAR_POSICAO_IMAGEM","alterarPosicaoImagem");
   defined("EXCLUIR_IMAGENS_TEMPORARIAS") || define("EXCLUIR_IMAGENS_TEMPORARIAS","excluiImagensTemporarias");

// URLREWRITE
defined("VERIFICAR_URLREWRITE") || define("VERIFICAR_URLREWRITE", "verificarUrlRewrite");

defined("EXCLUIR_ARQUIVO") || define("EXCLUIR_ARQUIVO","excluirArquivo");   

switch ($opx) {
	case CADASTRO_PLANOS:
		include_once 'planos_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

	    $dados = $_REQUEST;
        $imagens = $_FILES;

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("planos", "", '', $imagens['icone_upload'], 70, 70, 'inside');
            $dados['icone'] = $nomeicone;
        }

        $caminhopasta = "files/imagem";

        if(!file_exists($caminhopasta)){
            mkdir($caminhopasta, 0777);
        }

        //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['itns'])){
            foreach($imagens['itns'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg as $img){
                $nomeimagem[] = fileImage("itns", "", "", $img, 30, 30, 'inside');
            }
            foreach($dados['itns'] as $keys => $imagem){
                foreach($nomeimagem as $key => $names){
                    $dados['itns'][$key]['imagem'] = $names;
                }
            }
        }

        $idPlanos = cadastroPlanos($dados);

		if (is_int($idPlanos)) {

            foreach($dados['itns'] as $keys => $rec){
                $dados['itns'][$keys]['idplanos'] = $idPlanos;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroItns($dados['itns'][$keys]);
            }

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'planos';
			$log['descricao'] = 'Cadastrou planos ID('.$idPlanos.') nome ('.$dados['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=planos&acao=listarPlanos&mensagemalerta='.urlencode('Planos criado com sucesso!'));
		} else {
			header('Location: index.php?mod=planos&acao=listarPlanos&mensagemerro='.urlencode('ERRO ao criar novo Planos!'));
		}

	break;

	case EDIT_PLANOS:
		include_once 'planos_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';
      
		$dados = $_REQUEST;
        $imagens = $_FILES;

		$antigo = buscaPlanos(array('idplanos'=>$dados['idplanos']));
		$antigo = $antigo[0];

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("planos", "", '', $imagens['icone_upload'], 70, 70, 'inside');
            apagarImagemPlanos($antigo['icone']);  
            $dados['icone'] = $nomeicone;
        }

      //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['itns'])){
            foreach($imagens['itns'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $idPlanos = editPlanos($dados);

      //=============Grid com Imagem===============//
         if(!empty($arrayImg)){
           foreach($arrayImg as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemItns($dados['itns'][$key]['imagem']);
                   $nomeimagem[] = fileImage("itns", "", "", $imgsUpload, 30, 30, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['itns'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['itns'][$key]['iditns'] != 0){
                  // $antigoRecurso = buscaItns(array('iditns'=>$dados['itns'][$key]['iditns'], 'idplanos' => $idPlanos));
                  // $dados['itns'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['itns'][$key]['imagem'] = $dados[0]['imagem'];
               }
           }
         }

        if(!empty($dados['itns'])){
            foreach($dados['itns'] as $keys => $itns){
                if($dados['itns'][$keys]['iditns'] == 0 && $dados['itns'][$keys]['excluirRecurso'] != 0){
                    $dados['itns'][$keys]['idplanos'] = $idPlanos;
                    cadastroItns($dados['itns'][$keys]);
                }
                elseif($dados['itns'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaItns(array('iditns'=>$dados['itns'][$keys]['iditns']));
                    apagarImagemItns($antigoRecurso[0]['imagem']);
                    deletaItns2($idPlanos,$dados['itns'][$keys]['iditns']);
                }
                else{
                    $dados['itns'][$keys]['idplanos'] = $idPlanos;
                    editItns($dados['itns'][$keys]);
                }
            }
        }

		if ($idPlanos != FALSE) {

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'planos';
			$log['descricao'] = 'Editou planos ID('.$idPlanos.') DE  nome ('.$antigo['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$antigo['status'].') PARA nome ('.$dados['nome'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=planos&acao=listarPlanos&mensagemalerta='.urlencode('Planos salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=planos&acao=listarPlanos&mensagemerro='.urlencode('ERRO ao salvar Planos!'));
		}

	break;

	case DELETA_PLANOS:
		include_once 'planos_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('planos_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=planos&acao=listarPlanos&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaPlanos(array('idplanos'=>$dados['idu']));

            apagarImagemPlanos($antigo[0]['thumbs']);
            apagarImagemPlanos($antigo[0]['banner_topo']);

            $antigoItns = buscaItns(array('idplanos'=>$dados['idu']));

            foreach ($antigoItns as $key => $oldRec) {
                apagarImagemItns($oldRec['imagem']);
            }

			if (deletaPlanos($dados['idu']) == 1) {
                deletaItns($dados['idu']);
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'planos';
				$log['descricao'] = 'Deletou planos ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=planos&acao=listarPlanos&mensagemalerta='.urlencode('Planos deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=planos&acao=listarPlanos&mensagemerro='.urlencode('ERRO ao deletar Planos!'));
			}
		}

	break;

    case SALVA_IMAGEM:
        include_once('planos_class.php');
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

        $dados = $_POST;
        $imagem = $_FILES;

        if(!empty($dados['idplanos'])){
            $planosOld = buscaPlanos(array('idplanos'=>$dados['idplanos']));
            $planosOld = $planosOld[0];
        }

        if (isset($imagem['imagemCadastrar']) && $imagem['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $dados['coordenadas']);
            $nomeimagem = fileImage("planos", "", '', $imagem['imagemCadastrar'], $dados['dimensaoWidth'], $dados['dimensaoHeight'], 'cropped', $coordenadas);
            // fileImage("planos", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/planos/'.$nomeimagem;
            compressImage($caminho);

            if(!empty($dados['idplanos'])){
                if(!empty($planosOld[$dados['tipo']])){
                    $apgImage = deleteFiles('files/planos/', $planosOld[$dados['tipo']], array('', 'thumb_', 'original_'));
                    $planosOld[$dados['tipo']] = $nomeimagem;
                    $edit = editPlanos($planosOld);
                }else{
                    $planosOld[$dados['tipo']] = $nomeimagem;
                    $edit = editPlanos($planosOld);
                }
            }

            echo json_encode(array('status'=>true, 'imagem'=>$nomeimagem));
        }else{
            echo json_encode(array('status'=>false, 'msg'=>'Erro ao salvar imagem. Tente novamente'));
        }
    break;

    case CANCELAR_IMAGEM:
        $dados = $_REQUEST;

        if(file_exists('files/'.$dados['pasta'].'/'.$dados['imagem'])){
            unlink('files/'.$dados['pasta'].'/'.$dados['imagem']);
        }

        echo json_encode(array('status'=>true));

    break;

    case EXCLUIR_IMAGEM:
        include_once 'planos_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $planos = buscaPlanos(array('planos'=>$id));
        $planos = $planos[0];

        $imgAntigo = $planos[$tipo];
        deleteFiles('files/planos/', $imgAntigo, array('','thumb_','original_'));
        $planos[$tipo] = '';
        editPlanos($planos);

        echo json_encode(array('status'=>true));

    break;

   case LISTA_PLANOS:
      include_once 'planos_class.php';

      $dados = $_REQUEST;
      $listaplanos = buscaPlanos($dados);

      echo json_encode($listaplanos);

   break;

   case LISTA_SUBCATEGORIAS:
      include_once 'planos_class.php';
      include_once 'subcategoria_planos_class.php';

      $dados = $_REQUEST;
      $listasubcategoria_planos = buscaSubcategoria_planos($dados);

      echo json_encode($listasubcategoria_planos);

   break;

   //SALVA IMAGENS DA GALERIA 
   case SALVA_GALERIA:
      include_once ('planos_class.php');
      include_once 'includes/fileImage.php';
      include_once 'includes/functions.php';

      $dados = $_POST;
      $idplanos = $dados['idplanos'];
      $posicao = $dados['posicao']; 

      $imagem = $_FILES;

      $caminhopasta = "files/planos/galeria";
      if(!file_exists($caminhopasta)){
         mkdir($caminhopasta, 0777);
      }  

      //galeria grande
      $nomeimagem = fileImage("planos/galeria", "", "", $imagem['imagem'], 294, 343, 'resize');
      // $thumb = fileImage("planos/galeria", $nomeimagem, "thumb", $imagem['imagem'], 100, 100, 'crop');
      // fileImage("planos/galeria", $nomeimagem, "small", $imagem['imagem'], 64, 79, 'crop'); 

      $caminho = $caminhopasta.'/'.$nomeimagem;

      compressImage($caminho);

      //vai cadastrar se já tiver o id do planos, senao so fica na pasta.
      $idplanos_imagem = $nomeimagem; 

      if(is_numeric($idplanos)){
         //CADASTRAR IMAGEM NO BANCO E TRAZER O ID - EDITANDO GALERIA
         $imagem['idplanos'] = $idplanos;
         $imagem['descricao_imagem'] = "";
         $imagem['posicao_imagem'] = $posicao;
         $imagem['nome_imagem'] = $nomeimagem; 
         $idplanos_imagem = salvaImagemPlanos($imagem); 
      } 

      print '{"status":true, "caminho":"'.$caminho.'", "idplanos":"'.$idplanos.'", "idplanos_imagem":"'.$idplanos_imagem.'", "nome_arquivo":"'.$nomeimagem.'"}'; 
   break; 

   case SALVAR_DESCRICAO_IMAGEM:
      include_once('planos_class.php');
      $dados = $_POST;

      $imagem = buscaPlanos_imagem(array("idplanos_imagem"=>$dados['idImagem']));
      $imagem = $imagem[0];
      if($imagem){
         $imagem['descricao_imagem'] = $dados['descricao'];
         if(editPlanos_imagem($imagem)){
            print '{"status":true}';
         }else{
            print '{"status":false}';
         }
      }else{
         print '{"status":false}';
      }
   break;

   //EXCLUI A IMAGEM DA GALERIA SELECIONADA
   case EXCLUIR_IMAGEM_GALERIA:

      include_once('planos_class.php');

      $dados = $_POST;  
      $idplanos = $dados['idplanos'];
      $idplanos_imagem = $dados['idplanos_imagem'];
      $imagem = $dados['imagem']; 

      if(is_numeric($idplanos) && $idplanos > 0){ 
         //esta editando, apagar a imagem da pasta e do banco
         deletarImagemBlogGaleriaPlanos($idplanos_imagem, $idplanos);
         $retorno['status'] = apagarImagemBlogPlanos($imagem);
      }else{
         //apagar somente a imagem da pastar
         $retorno['status'] = apagarImagemBlogPlanos($imagem);
      }  
      print json_encode($retorno);   

   break;

   //ALTERAR POSICAO DA IMAGEM
   case ALTERAR_POSICAO_IMAGEM:

      include_once('planos_class.php');
      $dados = $_POST;  
      alterarPosicaoImagemPlanos($dados);
      print '{"status":true}';

   break; 


   //EXCLUI TODAS AS IMAGENS FEITO NA CADASTRO CANCELADAS
   case EXCLUIR_IMAGENS_TEMPORARIAS: 
      include_once('planos_class.php');
      $dados = $_POST;  

      if(isset($dados['imagem_planos'])){
         $imgs = $dados['imagem_planos'];
         foreach ($imgs as $key => $value) { 
            apagarImagemBlogPlanos($value);
         }
      } 
      print '{"status":true}'; 
   break; 

   case EXCLUIR_ARQUIVO: 
        include_once('planos_class.php');
        $dados = $_POST;

        // print_r($dados);die;
        $return = excluirArquivoPlanos($dados);

        if($return == true){
           echo json_encode(array('status'=>true));
        }else{
           echo json_encode(array('status'=>false));
        }
    break; 

    case VERIFICAR_URLREWRITE:

        include_once('planos_class.php');
        include_once('includes/functions.php');

        $dados = $_POST;

        $urlrewrite = converteUrl(utf8_encode(str_replace(" - ", " ", $dados['urlrewrite'])));
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $urlrewrite)));
        // echo $urlrewrite;
        // exit;
        $url = buscaPlanos(array("urlrewrite" => $urlrewrite, "not_idplanos" => $dados['idplanos']));

        if (empty($url)) {
            print '{"status":true,"url":"' . $urlrewrite . '"}';
        } else {
            print '{"status":false,"msg":"Url já cadastrada para outro tratamento"}';
        }

    break;


    case INVERTE_STATUS:
        include_once("planos_class.php");
        $dados = $_REQUEST;
        // inverteStatus($dados);
        $resultado['status'] = 'sucesso';
        include_once("includes/functions.php");
        $tabela = 'planos';
        $id = 'idplanos';

        try {
            $planos = buscaPlanos(array('idplanos' => $dados['idplanos']));
            $planos = $planos[0];

            // print_r($planos);
            if($planos['status'] == 1){
                $status = 0;
            }
            else{
                $status = 1;
            }

            $dadosUpdate = array();
            $dadosUpdate['idplanos'] = $dados['idplanos'];
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
