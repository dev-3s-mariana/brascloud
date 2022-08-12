<?php 
	 // Versao do modulo: 3.00.010416

require_once 'includes/verifica.php'; // checa user logado

if(!isset($_REQUEST["opx"]) || empty($_REQUEST["opx"])) exit;

$opx = $_REQUEST["opx"];

defined("INVERTE_STATUS") || define("INVERTE_STATUS", "inverteStatus");
defined("CADASTRO_TIMELINE") || define("CADASTRO_TIMELINE","cadastroTimeline");
defined("EDIT_TIMELINE") || define("EDIT_TIMELINE","editTimeline");
defined("DELETA_TIMELINE") || define("DELETA_TIMELINE","deletaTimeline");
defined("SALVA_IMAGEM") || define("SALVA_IMAGEM","salvaImagem");
defined("EXCLUIR_IMAGEM") || define("EXCLUIR_IMAGEM","excluirImagem");
defined("CANCELAR_IMAGEM") || define("CANCELAR_IMAGEM","cancelarImagem");

switch ($opx) {

	case CADASTRO_TIMELINE:
		include_once 'timeline_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;

		$idTimeline = cadastroTimeline($dados);

		if (is_int($idTimeline)) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'timeline';
			$log['avaliacao'] = 'Cadastrou timeline ID('.$idTimeline.') titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].') data ('.$dados['data'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('Timeline criado com sucesso!'));
		} else {
			header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('ERRO ao criar novo Timeline!'));
		}

		break;

	case EDIT_TIMELINE:
		include_once 'timeline_class.php';
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

		$dados = $_REQUEST;
		$antigo = buscaTimeline(array('idtimeline'=>$dados['idtimeline']));
		$antigo = $antigo[0];

		$idTimeline = editTimeline($dados);

		if ($idTimeline != FALSE) {
			//salva log
			include_once 'log_class.php';
			$log['idusuario'] = $_SESSION['sgc_idusuario'];
			$log['modulo'] = 'timeline';
			$log['avaliacao'] = 'Editou timeline ID('.$idTimeline.') DE  titulo ('.$antigo['titulo'].')imagem ('.$antigo['imagem'].') status ('.$antigo['status'].') data ('.$dados['data'].') PARA  titulo ('.$dados['titulo'].') imagem ('.$dados['imagem'].') status ('.$dados['status'].') data ('.$dados['data'].')';
			$log['request'] = $_REQUEST;
			novoLog($log);
			header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('Timeline salvo com sucesso!'));
		} else {
			header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('ERRO ao salvar Timeline!'));
		}

		break;

	case DELETA_TIMELINE:
		include_once 'timeline_class.php';
		include_once 'usuario_class.php';
        include_once 'includes/functions.php';

		if (!verificaPermissaoAcesso('timeline_deletar', $_SESSION['sgc_idusuario'])){
			header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('Voce nao tem privilegios para executar esta ação!'));
			exit;
		} else {
			$dados = $_REQUEST;
			$antigo = buscaTimeline(array('idtimeline'=>$dados['idu']));
            $imgAntigo = $antigo[0]['imagem'];
            $imgAntigo2 = $antigo[0]['imagem_2'];

			if (deletaTimeline($dados['idu']) == 1) {
                deleteFiles('files/timeline/', $imgAntigo, array('','thumb_','original_'));
                deleteFiles('files/timeline/', $imgAntigo2, array('','thumb_','original_'));

				//salva log
				include_once 'log_class.php';
				$log['idusuario'] = $_SESSION['sgc_idusuario'];
				$log['modulo'] = 'timeline';
				$log['avaliacao'] = 'Deletou timeline ID('.$dados['idu'].') ';
				$log['request'] = $_REQUEST;
				novoLog($log);
				header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('Timeline deletado com sucesso!'));
			} else {
				header('Location: index.php?mod=timeline&acao=listarTimeline&mensagemalerta='.urlencode('ERRO ao deletar Timeline!'));
			}
		}

	break;

	case INVERTE_STATUS:
		include_once("timeline_class.php");
		include_once("includes/functions.php");
		$dados = $_REQUEST;
		// inverteStatus($dados);
		$resultado['status'] = 'sucesso';

		$tabela = 'timeline';
		$id = 'idtimeline';

		try {
			$timeline = buscaTimeline(array('idtimeline' => $dados['idtimeline']));
			$timeline = $timeline[0];

			// print_r($depoimento);
			if($timeline['status'] == 1){
				$status = 0;
			}
			else{
				$status = 1;
			}

			$dadosUpdate = array();
			$dadosUpdate['idtimeline'] = $dados['idtimeline'];
			$dadosUpdate['status'] = $status;
			inverteStatus($dadosUpdate,$tabela,$id);

			print json_encode($resultado);
		} catch (Exception $e) {
			$resultado['status'] = 'falha';
			print json_encode($resultado);
		}
	break;

    case EXCLUIR_IMAGEM:
        include_once 'timeline_class.php';
        include_once 'includes/functions.php';

        $dados = $_REQUEST;
        $id = $dados['id'];
        $tipo = $dados['tipo'];
        $img = $dados['img'];
        $timeline = buscaTimeline(array('idtimeline'=>$id));
        $timeline = $timeline[0];

        $imgAntigo = $timeline[$tipo];
        deleteFiles('files/timeline/', $imgAntigo, array('','thumb_','original_'));
        $timeline[$tipo] = '';
        editTimeline($timeline);

        echo json_encode(array('status'=>true));

    break;

    case SALVA_IMAGEM:
        include_once('timeline_class.php');
        include_once 'includes/fileImage.php';
        include_once 'includes/functions.php';

        $dados = $_POST;
        $imagem = $_FILES;

        if(!empty($dados['idtimeline'])){
            $timelineOld = buscaTimeline(array('idtimeline'=>$dados['idtimeline']));
            $timelineOld = $timelineOld[0];
        }

        if (isset($imagem['imagemCadastrar']) && $imagem['imagemCadastrar']['error'] == 0) {
            $coordenadas = getDataImageCrop($imagem, $dados['coordenadas']);
            $nomeimagem = fileImage("timeline", "", '', $imagem['imagemCadastrar'], $dados['dimensaoWidth'], $dados['dimensaoHeight'], 'cropped', $coordenadas);
            // fileImage("timeline", $nomeimagem, 'original', $imagem['imagemCadastrar'], '', '', 'original');

            $caminho = 'files/timeline/'.$nomeimagem;
            compressImage($caminho);

            if(!empty($dados['idtimeline'])){
                if(!empty($timelineOld[$dados['tipo']])){
                    $apgImage = deleteFiles('files/timeline/', $timelineOld[$dados['tipo']], array('', 'thumb_', 'original_'));
                    $timelineOld[$dados['tipo']] = $nomeimagem;
                    $edit = editTimeline($timelineOld);
                }else{
                    $timelineOld[$dados['tipo']] = $nomeimagem;
                    $edit = editTimeline($timelineOld);
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

	default:
		if (!headers_sent() && (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) {
			header('Location: index.php?mod=home&mensagemalerta='.urlencode('Nenhuma acao definida...'));
		} else {
			trigger_error('Erro...', E_USER_ERROR);
			exit;
		}

}
?>