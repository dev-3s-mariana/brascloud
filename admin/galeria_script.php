<?php  
	 // Versao do modulo: 3.00.010416

if(!isset($_REQUEST['ajax'])){
	require_once 'includes/verifica.php'; // checa user logado
}

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("CADASTRO_GALERIA") || define("CADASTRO_GALERIA","cadastroGaleria");
defined("EDIT_GALERIA") || define("EDIT_GALERIA","editGaleria");
defined("DELETA_GALERIA") || define("DELETA_GALERIA","deletaGaleria");
defined("LISTAR_GALERIA_IMAGENS") || define("LISTAR_GALERIA_IMAGENS","listarGaleriaImagens");
defined("ABRIR_GALERIA") || define("ABRIR_GALERIA","abrirGaleria");

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");

//GALERIA
defined("SALVA_GALERIA") || define("SALVA_GALERIA","salvarGaleria");
defined("SALVAR_DESCRICAO_IMAGEM") || define("SALVAR_DESCRICAO_IMAGEM","salvarDescricao");
defined("EXCLUIR_IMAGEM_GALERIA") || define("EXCLUIR_IMAGEM_GALERIA","excluirImagemGaleria");
defined("ALTERAR_POSICAO_IMAGEM") || define("ALTERAR_POSICAO_IMAGEM","alterarPosicaoImagem");
defined("EXCLUIR_IMAGENS_TEMPORARIAS") || define("EXCLUIR_IMAGENS_TEMPORARIAS","excluiImagensTemporarias");

switch ($opx) {

	case CADASTRO_GALERIA:
		include_once 'galeria_class.php';

		$dados = $_REQUEST;
		 
		$idtemporario = $dados['idgaleria']; 
		$idGaleria = cadastroGaleria($dados); 

		if (is_int($idGaleria)) {

			if(!is_numeric($idtemporario) && file_exists('files/galeria/'.$idtemporario.'/')){
				rename('files/galeria/'.$idtemporario, 'files/galeria/'.$idGaleria);
			}  

			$imagens = $dados['imagem_galeria'];

			if(!empty($imagens)){
				$descricao = $dados['descricao_imagem'];
				$posicao = $dados['posicao_imagem']; 
				foreach($imagens as $k=>$v){
					$imagem['idgaleria'] = $idGaleria;
					$imagem['descricao_imagem'] = $descricao[$k];
					$imagem['posicao_imagem'] = $posicao[$k];
					$imagem['nome_imagem'] = $v; 
					salvaImagemGaleria($imagem);					
				} 
			}

			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'galeria';
			$log['descricao'] = 'Cadastrou galeria ID('.$idGaleria.') nome ('.$dados['nome'].') status ('.$dados['status'].') descricao ('.$dados['descricao'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemalerta='.urlencode('Galeria criado com sucesso!'));
		} else {
			header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemerro='.urlencode('ERRO ao criar novo Galeria!'));
		}

	break;

	case EDIT_GALERIA:
		include_once 'galeria_class.php';

		$dados = $_REQUEST;
		$antigo = buscaGaleria(array('idgaleria'=>$dados['idgaleria']));
		$antigo = $antigo[0];

		$idGaleria = editGaleria($dados);

		if ($idGaleria != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'galeria';
			$log['descricao'] = 'Editou galeria ID('.$idGaleria.') DE  nome ('.$antigo['nome'].') status ('.$antigo['status'].') descricao ('.$antigo['descricao'].') imagem ('.$antigo['imagem'].') PARA  nome ('.$dados['nome'].') status ('.$dados['status'].') descricao ('.$dados['descricao'].') imagem ('.$dados['imagem'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemalerta='.urlencode('Galeria salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemerro='.urlencode('ERRO ao salvar Galeria!'));
		}

	break;

	case DELETA_GALERIA:
		include_once 'galeria_class.php';
		include_once 'usuario_class.php';

		if (!verificaPermissaoAcesso('galeria_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaGaleria(array('idgaleria'=>$dados['idu']));

			if (deletaGaleria($dados['idu']) == 1) {
				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'galeria';
				$log['descricao'] = 'Deletou galeria ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemalerta='.urlencode('Galeria deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=galeria&acao=listarGaleria&mensagemerro='.urlencode('ERRO ao deletar Galeria!'));
			}
		}

	break; 
	 

    //SALVA IMAGENS DA GALERIA 
    case SALVA_GALERIA:
            
        include_once('galeria_class.php'); 
		include_once 'includes/fileImage.php'; 
        include_once 'includes/functions.php';
          
		$dados = $_POST;  
        $idgaleria = $dados['idgaleria'];
        $tipo = $dados['tipogaleria'];
        $posicao = $dados['posicao'];
        $files = $_FILES;
        $width2 = 200;
        $height2 = 200;
        $imagem = $_FILES;
        if(empty($idgaleria)){
            $idgaleria = md5(uniqid(rand(), true));
        }  

      
      	$caminhopasta = "files/galeria/";

        //maior
        $caminhoimagem = fileImage("galeria", '', '', $imagem['imagem'], "607", "607", 'crop');

        //thumb
        $thumb_imagem = fileImage("galeria", $caminhoimagem, 'thumb', $imagem['imagem'], "100", "100", 'crop');

       //  //big
       //  $big_imagem = fileImage("galeria", $caminhoimagem, 'big', $imagem['imagem'], "576", "700", 'crop');

       //  //thumb
       //  $thumb_imagem = fileImage("galeria", $caminhoimagem, 'thumb', $imagem['imagem'], "100", "100", 'crop');

       //  //large
       //  $large_imagem = fileImage("galeria", $caminhoimagem, 'large', $imagem['imagem'], "799", "350", 'crop');

       //  //medium
       //  $medium_imagem = fileImage("galeria", $caminhoimagem, 'medium', $imagem['imagem'], "533", "350", 'crop');

       //  //small
       //  $small_imagem = fileImage("galeria", $caminhoimagem, 'small', $imagem['imagem'], "400", "350", 'crop');

      	// //thumb  
      	// $imagemName = fileImage("galeria", $caminhoimagem, 'thumb', $imagem['imagem'], $width2, $height2, 'crop'); 

      	// //original
       //  $imagemName = fileImage("galeria", $caminhoimagem, 'original', $imagem['imagem'], '2000', '2000', 'original');
   	  
        
        $caminho = $caminhopasta.$caminhoimagem; 

        compressImage($caminho);
        compressImage($caminhopasta.$thumb_imagem);

        //vai cadastrar se já tiver o id do galeria, senao so fica na pasta.
        $idgaleria_imagem = $caminhoimagem; 

        if(is_numeric($idgaleria)){ 
        	//CADASTRAR IMAGEM NO BANCO E TRAZER O ID - EDITANDO GALERIA
			$imagem['idgaleria'] = $idgaleria;
			$imagem['descricao_imagem'] = "";
			$imagem['posicao_imagem'] = $posicao;
			$imagem['nome_imagem'] = $caminhoimagem; 
			$idgaleria_imagem = salvaImagemGaleria($imagem);	
        } 
       
        print '{"status":true, "caminho":"'.$caminho.'", "idgaleria":"'.$idgaleria.'", "idgaleria_imagem":"'.$idgaleria_imagem.'", "nome_arquivo":"'.$caminhoimagem.'"}'; 
          
    break; 
 
    case SALVAR_DESCRICAO_IMAGEM:
		include_once('galeria_class.php');
		$dados = $_POST;

		$imagem = buscaGaleria_imagem(array("idgaleria_imagem"=>$dados['idImagem']));
		$imagem = $imagem[0];
		if($imagem){
			$imagem['descricao_imagem'] = $dados['descricao'];
			if(editGaleria_imagem($imagem)){
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
				
		include_once('galeria_class.php');
		$dados = $_POST;		
		$idgaleria = $dados['idgaleria'];
		$idgaleria_imagem = $dados['idgaleria_imagem'];
		$imagem = $dados['imagem'];

		$retorno['status'] = true;

		if(is_numeric($idgaleria)){ 
			//ESTA EDITANDO OS DADOS, APAGAR IMAGEM DA PASTA E DO BANCO REORDENANDO A POSICAO
			$retorno['status'] = apagarImagemGaleria($idgaleria, $imagem, $idgaleria_imagem);			
		}else{ 
			//ESTA CADASTRANDO - APAGAR IMAGEM SO DA PASTA
			$retorno['status'] = apagarImagemGaleria($idgaleria, $imagem,''); 
		} 

		print json_encode($retorno);

	break;

	//ALTERAR POSICAO DA IMAGEM
	case ALTERAR_POSICAO_IMAGEM:
				
		include_once('galeria_class.php');
		$dados = $_POST;  
		alterarPosicaoImagemGaleria($dados);
		print '{"status":true}';

	break;

	//EXCLUI TODAS AS IMAGENS FEITO NA CADASTRO CANCELADAS
	case EXCLUIR_IMAGENS_TEMPORARIAS:
				
		include_once('galeria_class.php');
		$dados = $_POST;	 

	 	apagarPastaImagemGaleria($dados['idgaleria']);
		print '{"status":true}';

	break;


	case LISTAR_GALERIA_IMAGENS:
        
		include_once 'galeria_class.php'; 
         
        $dados = $_REQUEST;  
       	$dados['ordem'] = "posicao_imagem";
       	$dados['dir'] = "asc";
        $retorno = array();

        $imagens = buscaGaleria_imagem($dados); 

        $retorno['dados'] = $imagens;

        unset($dados['ordem']);
        $galeria = buscaGaleria($dados); 
        $retorno['galeria'] = $galeria; 
         
        $dados['totalRecords'] = true;                
        $total = buscaGaleria_imagem($dados);  
        $retorno['total'] = $total[0]['totalRecords'];  
                  
        if(count($total) > 0 && isset($dados['limit']) && $dados['limit'] > 0){
            $paginas = ceil($total[0]['totalRecords'] / $dados['limit']);
            $retorno['totalPaginas'] = $paginas;
        }  
        
        
        print json_encode($retorno);       

	break;

	case ABRIR_GALERIA:
        
		include_once 'galeria_class.php'; 
         
        $dados = $_REQUEST;  
       
        $retorno = array();
        $galeria = buscaGaleria($dados); 
        if(!empty($galeria)){
        	$retorno['dados'] = $galeria[0];
        	$retorno['galeria'] = buscaGaleria_imagem(array("idgaleria"=>$dados['idgaleria']));
        }  
 
        print json_encode($retorno);       

	break;	

	case INVERTE_STATUS:
		include_once("galeria_class.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		include_once("includes/functions.php");
		$tabela = 'galeria';
		$id = 'idgaleria';

		try {
			$galeria = buscaGaleria(array('idgaleria' => $dados['idgaleria']));
			$galeria = $galeria[0];

			// print_r($galeria);
			if($galeria['status'] == 'A'){
				$status = 'I';
			}
			else{
				$status = 'A';
			}

			$dadosUpdate = array();
			$dadosUpdate['idgaleria'] = $dados['idgaleria'];
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