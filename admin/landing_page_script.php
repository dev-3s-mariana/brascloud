<?php 
	 // Versao do modulo: 3.00.010416
if(!isset($_REQUEST["ajax"]) || empty($_REQUEST["ajax"])){
    require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_LANDING_PAGE") || define("CADASTRO_LANDING_PAGE","cadastroLanding_page");
defined("EDIT_LANDING_PAGE") || define("EDIT_LANDING_PAGE","editLanding_page");
defined("DELETA_LANDING_PAGE") || define("DELETA_LANDING_PAGE","deletaLanding_page");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("LISTA_LANDING_PAGE") || define("LISTA_LANDING_PAGE", "listaLanding_page");
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
	case CADASTRO_LANDING_PAGE:
		include_once 'landing_page_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

	    $dados = $_REQUEST;
        $imagens = $_FILES;

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("landing_page", "", '', $imagens['icone_upload'], 96, 96, 'inside');
            $dados['icone'] = $nomeicone;
        }

        $caminhopasta = "files/imagem";

        if(!file_exists($caminhopasta)){
            mkdir($caminhopasta, 0777);
        }

        //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['itens'])){
            foreach($imagens['itens'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg as $img){
                $nomeimagem[] = fileImage("itens", "", "", $img, 70, 70, 'inside');
            }
            foreach($dados['itens'] as $keys => $imagem){
                foreach($nomeimagem as $key => $names){
                    $dados['itens'][$key]['imagem'] = $names;
                }
            }
        }

        $arrayImg2 = array();
        if(!empty($imagens['difs'])){
            foreach($imagens['difs'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg2[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg2 as $img){
                $nomeimagem2[] = fileImage("difs", "", "", $img, 50, 50, 'inside');
            }
            foreach($dados['difs'] as $keys => $imagem){
                foreach($nomeimagem2 as $key => $names){
                    $dados['difs'][$key]['imagem'] = $names;
                }
            }
        }

        $arrayImg3 = array();
        if(!empty($imagens['indicacoes'])){
            foreach($imagens['indicacoes'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg3[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg3 as $img){
                $nomeimagem3[] = fileImage("indicacoes", "", "", $img, 50, 50, 'inside');
            }
            foreach($dados['indicacoes'] as $keys => $imagem){
                foreach($nomeimagem3 as $key => $names){
                    $dados['indicacoes'][$key]['imagem'] = $names;
                }
            }
        }

        $arrayImg4 = array();
        if(!empty($imagens['abrangencia'])){
            foreach($imagens['abrangencia'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg4[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg4 as $img){
                $nomeimagem4[] = fileImage("abrangencia", "", "", $img, 47, 47, 'inside');
            }
            foreach($dados['abrangencia'] as $keys => $imagem){
                foreach($nomeimagem4 as $key => $names){
                    $dados['abrangencia'][$key]['imagem'] = $names;
                }
            }
        }

        $idLanding_page = cadastroLanding_page($dados);

		if (is_int($idLanding_page)) {

            foreach ($dados['idlanding_page_imagem'] as $key => $idpi) {
                editIdLanding_page_imagem(array('idlanding_page'=>$idLanding_page,'idlanding_page_imagem'=>$idpi));
            }


            foreach($dados['itens'] as $keys => $rec){
                $dados['itens'][$keys]['idlanding_page'] = $idLanding_page;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroItens($dados['itens'][$keys]);
            }

            foreach($dados['difs'] as $keys => $rec){
                $dados['difs'][$keys]['idlanding_page'] = $idLanding_page;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroDifs($dados['difs'][$keys]);
            }

            foreach($dados['indicacoes'] as $keys => $rec){
                $dados['indicacoes'][$keys]['idlanding_page'] = $idLanding_page;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroIndicacoes($dados['indicacoes'][$keys]);
            }

            foreach($dados['abrangencia'] as $keys => $rec){
                $dados['abrangencia'][$keys]['idlanding_page'] = $idLanding_page;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroAbrangencia($dados['abrangencia'][$keys]);
            }

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'landing_page';
			$log['descricao'] = 'Cadastrou landing_page ID('.$idLanding_page.') nome ('.$dados['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemalerta='.urlencode('Landing_page criado com sucesso!'));
		} else {
			header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemerro='.urlencode('ERRO ao criar novo Landing_page!'));
		}

	break;

	case EDIT_LANDING_PAGE:
		include_once 'landing_page_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';
      
		$dados = $_REQUEST;
        $imagens = $_FILES;

		$antigo = buscaLanding_page(array('idlanding_page'=>$dados['idlanding_page']));
		$antigo = $antigo[0];

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("landing_page", "", '', $imagens['icone_upload'], 96, 96, 'inside');
            apagarImagemLanding_page($antigo['icone']);  
            $dados['icone'] = $nomeicone;
        }

      //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['itens'])){
            foreach($imagens['itens'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $arrayImg2 = array();
        if(!empty($imagens['difs'])){
            foreach($imagens['difs'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg2[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $arrayImg3 = array();
        if(!empty($imagens['indicacoes'])){
            foreach($imagens['indicacoes'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg3[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $arrayImg4 = array();
        if(!empty($imagens['abrangencia'])){
            foreach($imagens['abrangencia'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg4[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $idLanding_page = editLanding_page($dados);

      //=============Grid com Imagem===============//

         if(!empty($arrayImg)){
           foreach($arrayImg as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemItens($dados['itens'][$key]['imagem']);
                   $nomeimagem[] = fileImage("itens", "", "", $imgsUpload, 70, 70, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['itens'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['itens'][$key]['iditens'] != 0){
                  // $antigoRecurso = buscaItens(array('iditens'=>$dados['itens'][$key]['iditens'], 'idlanding_page' => $idLanding_page));
                  // $dados['itens'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['itens'][$key]['imagem'] = $dados['itens'][$key]['imagem'];
               }
           }
         }

         if(!empty($arrayImg2)){
           foreach($arrayImg2 as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemDifs($dados['difs'][$key]['imagem']);
                   $nomeimagem[] = fileImage("difs", "", "", $imgsUpload, 40, 40, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['difs'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['difs'][$key]['iddifs'] != 0){
                  // $antigoRecurso = buscaItens(array('iddifs'=>$dados['difs'][$key]['iddifs'], 'idlanding_page' => $idLanding_page));
                  // $dados['difs'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['difs'][$key]['imagem'] = $dados['difs'][$key]['imagem'];
               }
           }
         }

         if(!empty($arrayImg3)){
           foreach($arrayImg3 as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemIndicacoes($dados['indicacoes'][$key]['imagem']);
                   $nomeimagem[] = fileImage("indicacoes", "", "", $imgsUpload, 40, 40, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['indicacoes'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['indicacoes'][$key]['idindicacoes'] != 0){
                  // $antigoRecurso = buscaItens(array('idindicacoes'=>$dados['indicacoes'][$key]['idindicacoes'], 'idlanding_page' => $idLanding_page));
                  // $dados['indicacoes'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['indicacoes'][$key]['imagem'] = $dados['indicacoes'][$key]['imagem'];
               }
           }
         }

         if(!empty($arrayImg4)){
           foreach($arrayImg4 as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemAbrangencia($dados['abrangencia'][$key]['imagem']);
                   $nomeimagem[] = fileImage("abrangencia", "", "", $imgsUpload, 47, 47, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['abrangencia'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['abrangencia'][$key]['idabrangencia'] != 0){
                  // $antigoRecurso = buscaItens(array('idabrangencia'=>$dados['abrangencia'][$key]['idabrangencia'], 'idlanding_page' => $idLanding_page));
                  // $dados['abrangencia'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['abrangencia'][$key]['imagem'] = $dados['abrangencia'][$key]['imagem'];
               }
           }
         }

        if(!empty($dados['itens'])){
            foreach($dados['itens'] as $keys => $itens){
                if($dados['itens'][$keys]['iditens'] == 0 && $dados['itens'][$keys]['excluirRecurso'] != 0){
                    $dados['itens'][$keys]['idlanding_page'] = $idLanding_page;
                    cadastroItens($dados['itens'][$keys]);
                }
                elseif($dados['itens'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaItens(array('iditens'=>$dados['itens'][$keys]['iditens']));
                    apagarImagemItens($antigoRecurso[0]['imagem']);
                    deletaItens2($idLanding_page,$dados['itens'][$keys]['iditens']);
                }
                else{
                    $dados['itens'][$keys]['idlanding_page'] = $idLanding_page;
                    editItens($dados['itens'][$keys]);
                }
            }
        }

        if(!empty($dados['difs'])){
            foreach($dados['difs'] as $keys => $difs){
                if($dados['difs'][$keys]['iddifs'] == 0 && $dados['difs'][$keys]['excluirRecurso'] != 0){
                    $dados['difs'][$keys]['idlanding_page'] = $idLanding_page;
                    cadastroDifs($dados['difs'][$keys]);
                }
                elseif($dados['difs'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaDifs(array('iddifs'=>$dados['difs'][$keys]['iddifs']));
                    apagarImagemDifs($antigoRecurso[0]['imagem']);
                    deletaDifs2($idLanding_page,$dados['difs'][$keys]['iddifs']);
                }
                else{
                    $dados['difs'][$keys]['idlanding_page'] = $idLanding_page;
                    editDifs($dados['difs'][$keys]);
                }
            }
        }

        if(!empty($dados['indicacoes'])){
            foreach($dados['indicacoes'] as $keys => $indicacoes){
                if($dados['indicacoes'][$keys]['idindicacoes'] == 0 && $dados['indicacoes'][$keys]['excluirRecurso'] != 0){
                    $dados['indicacoes'][$keys]['idlanding_page'] = $idLanding_page;
                    cadastroIndicacoes($dados['indicacoes'][$keys]);
                }
                elseif($dados['indicacoes'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaIndicacoes(array('idindicacoes'=>$dados['indicacoes'][$keys]['idindicacoes']));
                    apagarImagemIndicacoes($antigoRecurso[0]['imagem']);
                    deletaIndicacoes2($idLanding_page,$dados['indicacoes'][$keys]['idindicacoes']);
                }
                else{
                    $dados['indicacoes'][$keys]['idlanding_page'] = $idLanding_page;
                    editIndicacoes($dados['indicacoes'][$keys]);
                }
            }
        }

        if(!empty($dados['abrangencia'])){
            foreach($dados['abrangencia'] as $keys => $abrangencia){
                if($dados['abrangencia'][$keys]['idabrangencia'] == 0 && $dados['abrangencia'][$keys]['excluirRecurso'] != 0){
                    $dados['abrangencia'][$keys]['idlanding_page'] = $idLanding_page;
                    cadastroAbrangencia($dados['abrangencia'][$keys]);
                }
                elseif($dados['abrangencia'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaAbrangencia(array('idabrangencia'=>$dados['abrangencia'][$keys]['idabrangencia']));
                    apagarImagemAbrangencia($antigoRecurso[0]['imagem']);
                    deletaAbrangencia2($idLanding_page,$dados['abrangencia'][$keys]['idabrangencia']);
                }
                else{
                    $dados['abrangencia'][$keys]['idlanding_page'] = $idLanding_page;
                    editAbrangencia($dados['abrangencia'][$keys]);
                }
            }
        }

		if ($idLanding_page != FALSE) {

            if(!empty($nomeArquivo)){
                $nomeArquivo = "files/landing_page/arquivos/".$nomeArquivo;
                if(!file_exists("files/landing_page/arquivos/")){
                    mkdir("files/landing_page/arquivos/",0777);
                }

                if(move_uploaded_file($arquivos['tmp_name'], $nomeArquivo)){ 
                    $dados['arquivo'] = $nomeArquivo;
                    $edit = editLanding_page($dados);
                    apagarArquivoLanding_page($antigo['arquivo']);
                } 
            }

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'landing_page';
			$log['descricao'] = 'Editou landing_page ID('.$idLanding_page.') DE  nome ('.$antigo['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$antigo['status'].') PARA nome ('.$dados['nome'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemalerta='.urlencode('Landing_page salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemerro='.urlencode('ERRO ao salvar Landing_page!'));
		}

	break;

	case DELETA_LANDING_PAGE:
		include_once 'landing_page_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('landing_page_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaLanding_page(array('idlanding_page'=>$dados['idu']));

            apagarImagemLanding_page($antigo[0]['thumbs']);
            apagarImagemLanding_page($antigo[0]['banner_topo']);

            $antigoItens = buscaItens(array('idlanding_page'=>$dados['idu']));
            $antigoDifs = buscaDifs(array('idlanding_page'=>$dados['idu']));
            $antigoIndicacoes = buscaIndicacoes(array('idlanding_page'=>$dados['idu']));
            $antigoAbrangencia = buscaAbrangencia(array('idlanding_page'=>$dados['idu']));

            foreach ($antigoItens as $key => $oldRec) {
                apagarImagemItens($oldRec['imagem']);
            }

            foreach ($antigoDifs as $key => $oldRec) {
                apagarImagemDifs($oldRec['imagem']);
            }

            foreach ($antigoIndicacoes as $key => $oldRec) {
                apagarImagemIndicacoes($oldRec['imagem']);
            }

            foreach ($antigoAbrangencia as $key => $oldRec) {
                apagarImagemAbrangencia($oldRec['imagem']);
            }

			if (deletaLanding_page($dados['idu']) == 1) {
                deletaItens($dados['idu']);
                deletaDifs($dados['idu']);
                deletaIndicacoes($dados['idu']);
                deletaAbrangencia($dados['idu']);
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'landing_page';
				$log['descricao'] = 'Deletou landing_page ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemalerta='.urlencode('Landing_page deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=landing_page&acao=listarLanding_page&mensagemerro='.urlencode('ERRO ao deletar Landing_page!'));
			}
		}

	break;

    case SALVA_IMAGEM:
        include_once('landing_page_class.php');
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

        $dados = $_POST;
        $imagem = $_FILES;

        if(!empty($dados['idlanding_page'])){
            $landing_pageOld = buscaLanding_page(array('idlanding_page'=>$dados['idlanding_page']));
            $landing_pageOld = $landing_pageOld[0];
        }

        if (isset($imagem['imagemCadastrar']) && $imagem['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $dados['coordenadas']);
            $nomeimagem = fileImage("landing_page", "", '', $imagem['imagemCadastrar'], $dados['dimensaoWidth'], $dados['dimensaoHeight'], 'cropped', $coordenadas);
            // fileImage("landing_page", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/landing_page/'.$nomeimagem;
            compressImage($caminho);

            if(!empty($dados['idlanding_page'])){
                if(!empty($landing_pageOld[$dados['tipo']])){
                    $apgImage = deleteFiles('files/landing_page/', $landing_pageOld[$dados['tipo']], array('', 'thumb_', 'original_'));
                    $landing_pageOld[$dados['tipo']] = $nomeimagem;
                    $edit = editLanding_page($landing_pageOld);
                }else{
                    $landing_pageOld[$dados['tipo']] = $nomeimagem;
                    $edit = editLanding_page($landing_pageOld);
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
        include_once 'landing_page_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $landing_page = buscaLanding_page(array('landing_page'=>$id));
        $landing_page = $landing_page[0];

        $imgAntigo = $landing_page[$tipo];
        deleteFiles('files/landing_page/', $imgAntigo, array('','thumb_','original_'));
        $landing_page[$tipo] = '';
        editLanding_page($landing_page);

        echo json_encode(array('status'=>true));

    break;

   case LISTA_LANDING_PAGE:
      include_once 'landing_page_class.php';

      $dados = $_REQUEST;
      $listalanding_page = buscaLanding_page($dados);

      echo json_encode($listalanding_page);

   break;

   case LISTA_SUBCATEGORIAS:
      include_once 'landing_page_class.php';
      include_once 'subcategoria_landing_page_class.php';

      $dados = $_REQUEST;
      $listasubcategoria_landing_page = buscaSubcategoria_landing_page($dados);

      echo json_encode($listasubcategoria_landing_page);

   break;

   //SALVA IMAGENS DA GALERIA 
   case SALVA_GALERIA:
      include_once ('landing_page_class.php');
      include_once 'includes/fileImage.php';
      include_once 'includes/functions.php';

      $dados = $_POST;
      $idlanding_page = $dados['idlanding_page'];
      $posicao = $dados['posicao']; 

      $imagem = $_FILES;

      $caminhopasta = "files/landing_page/galeria";
      if(!file_exists($caminhopasta)){
         mkdir($caminhopasta, 0777);
      }  

      //galeria grande
      $nomeimagem = fileImage("landing_page/galeria", "", "", $imagem['imagem'], 294, 343, 'resize');
      // $thumb = fileImage("landing_page/galeria", $nomeimagem, "thumb", $imagem['imagem'], 100, 100, 'crop');
      // fileImage("landing_page/galeria", $nomeimagem, "small", $imagem['imagem'], 64, 79, 'crop'); 

      $caminho = $caminhopasta.'/'.$nomeimagem;

      compressImage($caminho);

      //vai cadastrar se já tiver o id do landing_page, senao so fica na pasta.
      $idlanding_page_imagem = $nomeimagem; 

      if(is_numeric($idlanding_page)){
         //CADASTRAR IMAGEM NO BANCO E TRAZER O ID - EDITANDO GALERIA
         $imagem['idlanding_page'] = $idlanding_page;
         $imagem['descricao_imagem'] = "";
         $imagem['posicao_imagem'] = $posicao;
         $imagem['nome_imagem'] = $nomeimagem; 
         $idlanding_page_imagem = salvaImagemLanding_page($imagem); 
      } 

      print '{"status":true, "caminho":"'.$caminho.'", "idlanding_page":"'.$idlanding_page.'", "idlanding_page_imagem":"'.$idlanding_page_imagem.'", "nome_arquivo":"'.$nomeimagem.'"}'; 
   break; 

   case SALVAR_DESCRICAO_IMAGEM:
      include_once('landing_page_class.php');
      $dados = $_POST;

      $imagem = buscaLanding_page_imagem(array("idlanding_page_imagem"=>$dados['idImagem']));
      $imagem = $imagem[0];
      if($imagem){
         $imagem['descricao_imagem'] = $dados['descricao'];
         if(editLanding_page_imagem($imagem)){
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

      include_once('landing_page_class.php');

      $dados = $_POST;  
      $idlanding_page = $dados['idlanding_page'];
      $idlanding_page_imagem = $dados['idlanding_page_imagem'];
      $imagem = $dados['imagem']; 

      if(is_numeric($idlanding_page) && $idlanding_page > 0){ 
         //esta editando, apagar a imagem da pasta e do banco
         deletarImagemBlogGaleriaLanding_page($idlanding_page_imagem, $idlanding_page);
         $retorno['status'] = apagarImagemBlogLanding_page($imagem);
      }else{
         //apagar somente a imagem da pastar
         $retorno['status'] = apagarImagemBlogLanding_page($imagem);
      }  
      print json_encode($retorno);   

   break;

   //ALTERAR POSICAO DA IMAGEM
   case ALTERAR_POSICAO_IMAGEM:

      include_once('landing_page_class.php');
      $dados = $_POST;  
      alterarPosicaoImagemLanding_page($dados);
      print '{"status":true}';

   break; 


   //EXCLUI TODAS AS IMAGENS FEITO NA CADASTRO CANCELADAS
   case EXCLUIR_IMAGENS_TEMPORARIAS: 
      include_once('landing_page_class.php');
      $dados = $_POST;  

      if(isset($dados['imagem_landing_page'])){
         $imgs = $dados['imagem_landing_page'];
         foreach ($imgs as $key => $value) { 
            apagarImagemBlogLanding_page($value);
         }
      } 
      print '{"status":true}'; 
   break; 

   case EXCLUIR_ARQUIVO: 
        include_once('landing_page_class.php');
        $dados = $_POST;

        // print_r($dados);die;
        $return = excluirArquivoLanding_page($dados);

        if($return == true){
           echo json_encode(array('status'=>true));
        }else{
           echo json_encode(array('status'=>false));
        }
    break; 

    case VERIFICAR_URLREWRITE:

        include_once('landing_page_class.php');
        include_once('includes/functions.php');

        $dados = $_POST;

        $urlrewrite = converteUrl(utf8_encode(str_replace(" - ", " ", $dados['urlrewrite'])));
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $urlrewrite)));
        // echo $urlrewrite;
        // exit;
        $url = buscaLanding_page(array("urlrewrite" => $urlrewrite, "not_idlanding_page" => $dados['idlanding_page']));

        if (empty($url)) {
            print '{"status":true,"url":"' . $urlrewrite . '"}';
        } else {
            print '{"status":false,"msg":"Url já cadastrada para outro tratamento"}';
        }

    break;


    case INVERTE_STATUS:
        include_once("landing_page_class.php");
        $dados = $_REQUEST;
        // inverteStatus($dados);
        $resultado['status'] = 'sucesso';
        include_once("includes/functions.php");
        $tabela = 'landing_page';
        $id = 'idlanding_page';

        try {
            $landing_page = buscaLanding_page(array('idlanding_page' => $dados['idlanding_page']));
            $landing_page = $landing_page[0];

            // print_r($landing_page);
            if($landing_page['status'] == 1){
                $status = 0;
            }
            else{
                $status = 1;
            }

            $dadosUpdate = array();
            $dadosUpdate['idlanding_page'] = $dados['idlanding_page'];
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
