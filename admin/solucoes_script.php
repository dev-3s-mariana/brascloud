<?php 
	 // Versao do modulo: 3.00.010416
if(!isset($_REQUEST["ajax"]) || empty($_REQUEST["ajax"])){
    require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_SOLUCOES") || define("CADASTRO_SOLUCOES","cadastroSolucoes");
defined("EDIT_SOLUCOES") || define("EDIT_SOLUCOES","editSolucoes");
defined("DELETA_SOLUCOES") || define("DELETA_SOLUCOES","deletaSolucoes");
defined("INVERTE_STATUS") || define("INVERTE_STATUS","inverteStatus");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("LISTA_SOLUCOES") || define("LISTA_SOLUCOES", "listaSolucoes");
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
	case CADASTRO_SOLUCOES:
		include_once 'solucoes_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

	    $dados = $_REQUEST;
        $imagens = $_FILES;

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("solucoes", "", '', $imagens['icone_upload'], 96, 96, 'inside');
            $dados['icone'] = $nomeicone;
        }

        $caminhopasta = "files/imagem";

        if(!file_exists($caminhopasta)){
            mkdir($caminhopasta, 0777);
        }

        //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['recursos'])){
            foreach($imagens['recursos'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg as $img){
                $nomeimagem[] = fileImage("recursos", "", "", $img, 81, 81, 'inside');
            }
            foreach($dados['recursos'] as $keys => $imagem){
                foreach($nomeimagem as $key => $names){
                    $dados['recursos'][$key]['imagem'] = $names;
                }
            }
        }

        $arrayImg2 = array();
        if(!empty($imagens['testes'])){
            foreach($imagens['testes'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg2[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg2 as $img){
                $nomeimagem2[] = fileImage("testes", "", "", $img, 50, 50, 'inside');
            }
            foreach($dados['testes'] as $keys => $imagem){
                foreach($nomeimagem2 as $key => $names){
                    $dados['testes'][$key]['imagem'] = $names;
                }
            }
        }

        $arrayImg3 = array();
        if(!empty($imagens['diferenciais'])){
            foreach($imagens['diferenciais'] as $key => $imgArray){
                foreach($imgArray as $keyName => $img){
                    $arrayImg3[$keyName][$key] = $img['imagem'];          
                }
            }
            foreach($arrayImg3 as $img){
                $nomeimagem3[] = fileImage("diferenciais", "", "", $img, 50, 50, 'inside');
            }
            foreach($dados['diferenciais'] as $keys => $imagem){
                foreach($nomeimagem3 as $key => $names){
                    $dados['diferenciais'][$key]['imagem'] = $names;
                }
            }
        }

        $idSolucoes = cadastroSolucoes($dados);

		if (is_int($idSolucoes)) {

            foreach ($dados['idsolucoes_imagem'] as $key => $idpi) {
                editIdSolucoes_imagem(array('idsolucoes'=>$idSolucoes,'idsolucoes_imagem'=>$idpi));
            }


            foreach($dados['recursos'] as $keys => $rec){
                $dados['recursos'][$keys]['idsolucoes'] = $idSolucoes;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroRecursos($dados['recursos'][$keys]);
            }

            foreach($dados['testes'] as $keys => $rec){
                $dados['testes'][$keys]['idsolucoes'] = $idSolucoes;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroTestes($dados['testes'][$keys]);
            }

            foreach($dados['diferenciais'] as $keys => $rec){
                $dados['diferenciais'][$keys]['idsolucoes'] = $idSolucoes;
                // if(empty($rec['icone'])){
                //  $rec['icone'] = 1;
                // }
                cadastroDiferenciais($dados['diferenciais'][$keys]);
            }

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'solucoes';
			$log['descricao'] = 'Cadastrou solucoes ID('.$idSolucoes.') nome ('.$dados['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemalerta='.urlencode('Solucoes criado com sucesso!'));
		} else {
			header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemerro='.urlencode('ERRO ao criar novo Solucoes!'));
		}

	break;

	case EDIT_SOLUCOES:
		include_once 'solucoes_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';
      
		$dados = $_REQUEST;
        $imagens = $_FILES;

		$antigo = buscaSolucoes(array('idsolucoes'=>$dados['idsolucoes']));
		$antigo = $antigo[0];

        if (isset($_FILES['icone_upload']) && $_FILES['icone_upload']['error'] == 0) {
            $nomeicone = fileImage("solucoes", "", '', $imagens['icone_upload'], 96, 96, 'inside');
            apagarImagemSolucoes($antigo['icone']);  
            $dados['icone'] = $nomeicone;
        }

      //=============Grid com Imagem===============//
        $arrayImg = array();
        if(!empty($imagens['recursos'])){
            foreach($imagens['recursos'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $arrayImg2 = array();
        if(!empty($imagens['testes'])){
            foreach($imagens['testes'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg2[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $arrayImg3 = array();
        if(!empty($imagens['diferenciais'])){
            foreach($imagens['diferenciais'] as $key => $imgArray){
               foreach($imgArray as $keyName => $img){
                  if(!empty($img['imagem'])){
                     $arrayImg3[$keyName][$key] = $img['imagem'];
                  }
               }
           }
        }

        $idSolucoes = editSolucoes($dados);

      //=============Grid com Imagem===============//

         if(!empty($arrayImg)){
           foreach($arrayImg as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemRecursos($dados['recursos'][$key]['imagem']);
                   $nomeimagem[] = fileImage("recursos", "", "", $imgsUpload, 81, 81, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['recursos'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['recursos'][$key]['idrecursos'] != 0){
                  // $antigoRecurso = buscaRecursos(array('idrecursos'=>$dados['recursos'][$key]['idrecursos'], 'idsolucoes' => $idSolucoes));
                  // $dados['recursos'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['recursos'][$key]['imagem'] = $dados['recursos'][$key]['imagem'];
               }
           }
         }

         if(!empty($arrayImg2)){
           foreach($arrayImg2 as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemTestes($dados['testes'][$key]['imagem']);
                   $nomeimagem[] = fileImage("testes", "", "", $imgsUpload, 40, 40, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['testes'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['testes'][$key]['idtestes'] != 0){
                  // $antigoRecurso = buscaRecursos(array('idtestes'=>$dados['testes'][$key]['idtestes'], 'idsolucoes' => $idSolucoes));
                  // $dados['testes'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['testes'][$key]['imagem'] = $dados['testes'][$key]['imagem'];
               }
           }
         }

         if(!empty($arrayImg3)){
           foreach($arrayImg3 as $key => $imgsUpload){
               if(!empty($imgsUpload['tmp_name'])){
                   apagarImagemDiferenciais($dados['diferenciais'][$key]['imagem']);
                   $nomeimagem[] = fileImage("diferenciais", "", "", $imgsUpload, 40, 40, 'inside');
                   foreach($nomeimagem as $names){
                       $dados['diferenciais'][$key]['imagem'] = $names;
                   }
               }
               elseif($dados['diferenciais'][$key]['iddiferenciais'] != 0){
                  // $antigoRecurso = buscaRecursos(array('iddiferenciais'=>$dados['diferenciais'][$key]['iddiferenciais'], 'idsolucoes' => $idSolucoes));
                  // $dados['diferenciais'][$key]['imagem'] = $antigoRecurso[0]['imagem'];
                  $dados['diferenciais'][$key]['imagem'] = $dados['diferenciais'][$key]['imagem'];
               }
           }
         }

        if(!empty($dados['recursos'])){
            foreach($dados['recursos'] as $keys => $recursos){
                if($dados['recursos'][$keys]['idrecursos'] == 0 && $dados['recursos'][$keys]['excluirRecurso'] != 0){
                    $dados['recursos'][$keys]['idsolucoes'] = $idSolucoes;
                    cadastroRecursos($dados['recursos'][$keys]);
                }
                elseif($dados['recursos'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaRecursos(array('idrecursos'=>$dados['recursos'][$keys]['idrecursos']));
                    apagarImagemRecursos($antigoRecurso[0]['imagem']);
                    deletaRecursos2($idSolucoes,$dados['recursos'][$keys]['idrecursos']);
                }
                else{
                    $dados['recursos'][$keys]['idsolucoes'] = $idSolucoes;
                    editRecursos($dados['recursos'][$keys]);
                }
            }
        }

        if(!empty($dados['testes'])){
            foreach($dados['testes'] as $keys => $testes){
                if($dados['testes'][$keys]['idtestes'] == 0 && $dados['testes'][$keys]['excluirRecurso'] != 0){
                    $dados['testes'][$keys]['idsolucoes'] = $idSolucoes;
                    cadastroTestes($dados['testes'][$keys]);
                }
                elseif($dados['testes'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaTestes(array('idtestes'=>$dados['testes'][$keys]['idtestes']));
                    apagarImagemTestes($antigoRecurso[0]['imagem']);
                    deletaTestes2($idSolucoes,$dados['testes'][$keys]['idtestes']);
                }
                else{
                    $dados['testes'][$keys]['idsolucoes'] = $idSolucoes;
                    editTestes($dados['testes'][$keys]);
                }
            }
        }

        if(!empty($dados['diferenciais'])){
            foreach($dados['diferenciais'] as $keys => $diferenciais){
                if($dados['diferenciais'][$keys]['iddiferenciais'] == 0 && $dados['diferenciais'][$keys]['excluirRecurso'] != 0){
                    $dados['diferenciais'][$keys]['idsolucoes'] = $idSolucoes;
                    cadastroDiferenciais($dados['diferenciais'][$keys]);
                }
                elseif($dados['diferenciais'][$keys]['excluirRecurso'] == 0){
                    $antigoRecurso = buscaDiferenciais(array('iddiferenciais'=>$dados['diferenciais'][$keys]['iddiferenciais']));
                    apagarImagemDiferenciais($antigoRecurso[0]['imagem']);
                    deletaDiferenciais2($idSolucoes,$dados['diferenciais'][$keys]['iddiferenciais']);
                }
                else{
                    $dados['diferenciais'][$keys]['idsolucoes'] = $idSolucoes;
                    editDiferenciais($dados['diferenciais'][$keys]);
                }
            }
        }

		if ($idSolucoes != FALSE) {

            if(!empty($nomeArquivo)){
                $nomeArquivo = "files/solucoes/arquivos/".$nomeArquivo;
                if(!file_exists("files/solucoes/arquivos/")){
                    mkdir("files/solucoes/arquivos/",0777);
                }

                if(move_uploaded_file($arquivos['tmp_name'], $nomeArquivo)){ 
                    $dados['arquivo'] = $nomeArquivo;
                    $edit = editSolucoes($dados);
                    apagarArquivoSolucoes($antigo['arquivo']);
                } 
            }

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'solucoes';
			$log['descricao'] = 'Editou solucoes ID('.$idSolucoes.') DE  nome ('.$antigo['nome'].') urlrewrite ('.$dados['urlrewrite'].') status ('.$antigo['status'].') PARA nome ('.$dados['nome'].') status ('.$dados['status'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemalerta='.urlencode('Solucoes salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemerro='.urlencode('ERRO ao salvar Solucoes!'));
		}

	break;

	case DELETA_SOLUCOES:
		include_once 'solucoes_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('solucoes_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaSolucoes(array('idsolucoes'=>$dados['idu']));

            apagarImagemSolucoes($antigo[0]['thumbs']);
            apagarImagemSolucoes($antigo[0]['banner_topo']);

            $antigoRecursos = buscaRecursos(array('idsolucoes'=>$dados['idu']));
            $antigoTestes = buscaTestes(array('idsolucoes'=>$dados['idu']));
            $antigoDiferenciais = buscaDiferenciais(array('idsolucoes'=>$dados['idu']));

            foreach ($antigoRecursos as $key => $oldRec) {
                apagarImagemRecursos($oldRec['imagem']);
            }

            foreach ($antigoTestes as $key => $oldRec) {
                apagarImagemTestes($oldRec['imagem']);
            }

            foreach ($antigoDiferenciais as $key => $oldRec) {
                apagarImagemDiferenciais($oldRec['imagem']);
            }

			if (deletaSolucoes($dados['idu']) == 1) {
                deletaRecursos($dados['idu']);
                deletaTestes($dados['idu']);
                deletaDiferenciais($dados['idu']);
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'solucoes';
				$log['descricao'] = 'Deletou solucoes ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemalerta='.urlencode('Solucoes deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=solucoes&acao=listarSolucoes&mensagemerro='.urlencode('ERRO ao deletar Solucoes!'));
			}
		}

	break;

    case SALVA_IMAGEM:
        include_once('solucoes_class.php');
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

        $dados = $_POST;
        $imagem = $_FILES;

        if(!empty($dados['idsolucoes'])){
            $solucoesOld = buscaSolucoes(array('idsolucoes'=>$dados['idsolucoes']));
            $solucoesOld = $solucoesOld[0];
        }

        if (isset($imagem['imagemCadastrar']) && $imagem['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $dados['coordenadas']);
            $nomeimagem = fileImage("solucoes", "", '', $imagem['imagemCadastrar'], $dados['dimensaoWidth'], $dados['dimensaoHeight'], 'cropped', $coordenadas);
            // fileImage("solucoes", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/solucoes/'.$nomeimagem;
            compressImage($caminho);

            if(!empty($dados['idsolucoes'])){
                if(!empty($solucoesOld[$dados['tipo']])){
                    $apgImage = deleteFiles('files/solucoes/', $solucoesOld[$dados['tipo']], array('', 'thumb_', 'original_'));
                    $solucoesOld[$dados['tipo']] = $nomeimagem;
                    $edit = editSolucoes($solucoesOld);
                }else{
                    $solucoesOld[$dados['tipo']] = $nomeimagem;
                    $edit = editSolucoes($solucoesOld);
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
        include_once 'solucoes_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $solucoes = buscaSolucoes(array('solucoes'=>$id));
        $solucoes = $solucoes[0];

        $imgAntigo = $solucoes[$tipo];
        deleteFiles('files/solucoes/', $imgAntigo, array('','thumb_','original_'));
        $solucoes[$tipo] = '';
        editSolucoes($solucoes);

        echo json_encode(array('status'=>true));

    break;

   case LISTA_SOLUCOES:
      include_once 'solucoes_class.php';

      $dados = $_REQUEST;
      $listasolucoes = buscaSolucoes($dados);

      echo json_encode($listasolucoes);

   break;

   case LISTA_SUBCATEGORIAS:
      include_once 'solucoes_class.php';
      include_once 'subcategoria_solucoes_class.php';

      $dados = $_REQUEST;
      $listasubcategoria_solucoes = buscaSubcategoria_solucoes($dados);

      echo json_encode($listasubcategoria_solucoes);

   break;

   //SALVA IMAGENS DA GALERIA 
   case SALVA_GALERIA:
      include_once ('solucoes_class.php');
      include_once 'includes/fileImage.php';
      include_once 'includes/functions.php';

      $dados = $_POST;
      $idsolucoes = $dados['idsolucoes'];
      $posicao = $dados['posicao']; 

      $imagem = $_FILES;

      $caminhopasta = "files/solucoes/galeria";
      if(!file_exists($caminhopasta)){
         mkdir($caminhopasta, 0777);
      }  

      //galeria grande
      $nomeimagem = fileImage("solucoes/galeria", "", "", $imagem['imagem'], 294, 343, 'resize');
      // $thumb = fileImage("solucoes/galeria", $nomeimagem, "thumb", $imagem['imagem'], 100, 100, 'crop');
      // fileImage("solucoes/galeria", $nomeimagem, "small", $imagem['imagem'], 64, 79, 'crop'); 

      $caminho = $caminhopasta.'/'.$nomeimagem;

      compressImage($caminho);

      //vai cadastrar se já tiver o id do solucoes, senao so fica na pasta.
      $idsolucoes_imagem = $nomeimagem; 

      if(is_numeric($idsolucoes)){
         //CADASTRAR IMAGEM NO BANCO E TRAZER O ID - EDITANDO GALERIA
         $imagem['idsolucoes'] = $idsolucoes;
         $imagem['descricao_imagem'] = "";
         $imagem['posicao_imagem'] = $posicao;
         $imagem['nome_imagem'] = $nomeimagem; 
         $idsolucoes_imagem = salvaImagemSolucoes($imagem); 
      } 

      print '{"status":true, "caminho":"'.$caminho.'", "idsolucoes":"'.$idsolucoes.'", "idsolucoes_imagem":"'.$idsolucoes_imagem.'", "nome_arquivo":"'.$nomeimagem.'"}'; 
   break; 

   case SALVAR_DESCRICAO_IMAGEM:
      include_once('solucoes_class.php');
      $dados = $_POST;

      $imagem = buscaSolucoes_imagem(array("idsolucoes_imagem"=>$dados['idImagem']));
      $imagem = $imagem[0];
      if($imagem){
         $imagem['descricao_imagem'] = $dados['descricao'];
         if(editSolucoes_imagem($imagem)){
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

      include_once('solucoes_class.php');

      $dados = $_POST;  
      $idsolucoes = $dados['idsolucoes'];
      $idsolucoes_imagem = $dados['idsolucoes_imagem'];
      $imagem = $dados['imagem']; 

      if(is_numeric($idsolucoes) && $idsolucoes > 0){ 
         //esta editando, apagar a imagem da pasta e do banco
         deletarImagemBlogGaleriaSolucoes($idsolucoes_imagem, $idsolucoes);
         $retorno['status'] = apagarImagemBlogSolucoes($imagem);
      }else{
         //apagar somente a imagem da pastar
         $retorno['status'] = apagarImagemBlogSolucoes($imagem);
      }  
      print json_encode($retorno);   

   break;

   //ALTERAR POSICAO DA IMAGEM
   case ALTERAR_POSICAO_IMAGEM:

      include_once('solucoes_class.php');
      $dados = $_POST;  
      alterarPosicaoImagemSolucoes($dados);
      print '{"status":true}';

   break; 


   //EXCLUI TODAS AS IMAGENS FEITO NA CADASTRO CANCELADAS
   case EXCLUIR_IMAGENS_TEMPORARIAS: 
      include_once('solucoes_class.php');
      $dados = $_POST;  

      if(isset($dados['imagem_solucoes'])){
         $imgs = $dados['imagem_solucoes'];
         foreach ($imgs as $key => $value) { 
            apagarImagemBlogSolucoes($value);
         }
      } 
      print '{"status":true}'; 
   break; 

   case EXCLUIR_ARQUIVO: 
        include_once('solucoes_class.php');
        $dados = $_POST;

        // print_r($dados);die;
        $return = excluirArquivoSolucoes($dados);

        if($return == true){
           echo json_encode(array('status'=>true));
        }else{
           echo json_encode(array('status'=>false));
        }
    break; 

    case VERIFICAR_URLREWRITE:

        include_once('solucoes_class.php');
        include_once('includes/functions.php');

        $dados = $_POST;

        $urlrewrite = converteUrl(utf8_encode(str_replace(" - ", " ", $dados['urlrewrite'])));
        $urlrewrite = converteUrl(utf8_encode(str_replace("-", " ", $urlrewrite)));
        // echo $urlrewrite;
        // exit;
        $url = buscaSolucoes(array("urlrewrite" => $urlrewrite, "not_idsolucoes" => $dados['idsolucoes']));

        if (empty($url)) {
            print '{"status":true,"url":"' . $urlrewrite . '"}';
        } else {
            print '{"status":false,"msg":"Url já cadastrada para outro tratamento"}';
        }

    break;


    case INVERTE_STATUS:
        include_once("solucoes_class.php");
        $dados = $_REQUEST;
        // inverteStatus($dados);
        $resultado['status'] = 'sucesso';
        include_once("includes/functions.php");
        $tabela = 'solucoes';
        $id = 'idsolucoes';

        try {
            $solucoes = buscaSolucoes(array('idsolucoes' => $dados['idsolucoes']));
            $solucoes = $solucoes[0];

            // print_r($solucoes);
            if($solucoes['status'] == 1){
                $status = 0;
            }
            else{
                $status = 1;
            }

            $dadosUpdate = array();
            $dadosUpdate['idsolucoes'] = $dados['idsolucoes'];
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
