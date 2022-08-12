<?php 
	 // Versao do modulo: 3.00.010416

	include_once "timeline_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";

    $width = 150;
    $height = 150;

    $width2 = 150;
    $height2 = 300;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="timeline_css.css" />
<script type="text/javascript" src="timeline_js.js"></script>

<!-- CROPPER-->
<link href="css/cropper-padrao.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">

<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formTimeline"){
	if($_REQUEST['met'] == "cadastroTimeline"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "timeline_script.php?opx=cadastroTimeline";
		$metodo_titulo = "Cadastro Timeline";
		$idTimeline = 0 ;

		// dados para os campos
		$timeline['titulo'] = "";
		$timeline['imagem'] = "";
        $timeline['imagem_2'] = "";
		$timeline['status'] = "";
        $timeline['texto'] = "";
		$timeline['ano'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "timeline_script.php?opx=editTimeline";
		$metodo_titulo = "Editar Timeline";
		$idTimeline = (int) $_GET['idu'];
		$timeline = buscaTimeline(array('idtimeline'=>$idTimeline));
		if (count($timeline) != 1) exit;
		$timeline = $timeline[0];
	}
?>

	<div id="titulo">
		<i class="fas fa-bars fa-2x"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=timeline&acao=listarTimeline">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=timeline&acao=formTimeline&met=cadastroTimeline">Cadastro</a></li> 
		</ul>
	</div>

	<div id="principal">
		<form class="form" name="formTimeline" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="return verificarCampos(new Array('titulo_aba')); ">

			<div id="informacaoTimeline" class="content">
				<div class="content_tit">Dados Timeline:</div>
				<div class="box_ip">
					<label for="titulo">Título</label>
					<input type="text" name="titulo" id="titulo_aba" class='' size="30" value="<?php echo $timeline['titulo']; ?>" />
				</div>
				<div class="box_ip">
					<label for="ano">Ano</label>
					<input type="text" name="ano" id="ano" class='' size="30" maxlength="4" value="<?=$timeline['ano']?>"/>
				</div>
                <div class="box_ip box_txt">
                   <label for="texto">Texto</label>
                   <textarea name="texto" id="texto" class="" size="30"><?php echo $timeline['texto']; ?></textarea>
                </div>
                <div class="box_ip">
                	<div class="box_sel box_txt">
    					<label for="status">Status</label>
    					<div class="box_sel_d">
    						<select name="status" id="status" class=''>
    							<option value="1" <?php print($timeline['status'] == "1" ? ' selected="selected" ' : ''); ?>> Ativo </option>
    							<option value="0" <?php print($timeline['status'] == "0" ? ' selected="selected" ' : ''); ?>> Inativo </option>
    						</select>
    					</div>
    				</div>
                </div>

                <!-- CROPPER IMG -->
                <!-- <?php $caminho = 'files/timeline/'; ?>
                <div id="select-image-1" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <div class="box_ip imagem-atual" style="<?=empty($timeline['imagem'])?'display: none;':''?>">
                                    <a data-tipo="imagem" data-img="<?=$timeline['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img src="<?=empty($timeline['imagem'])?'images/cliente/logo.png':$caminho.$timeline['imagem']?>" class="img-timeline-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <input id="inputImage" class="cropped-image" name="imagemCadastrar2" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que: <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="select-image-2" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <div class="box_ip imagem-atual" style="<?=empty($timeline['imagem_2'])?'display: none;':''?>">
                                    <a data-tipo="imagem_2" data-img="<?=$timeline['imagem_2']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img src="<?=empty($timeline['imagem_2'])?'images/cliente/logo.png':$caminho.$timeline['imagem_2']?>" class="img-timeline-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <input id="inputImage2" class="cropped-image" name="imagemCadastrar" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width2?>x<?=$height2?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que: <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize2" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div> -->

               <!--  <div id="cropper-modal">
                    <div class="img-container" id="img-container">
                        <img alt="">
                    </div>
                    <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button class="close" data-dismiss="modal" type="button" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                </div>
                                <div class="modal-body"></div>
                            </div>
                        </div>
                    </div>
                    <div class="div-save-cropped-image">
                        <input data-image-type='' type="button" value="Salvar" class="save-cropped-image">
                    </div>
                </div>
 -->
			</div>
            
			<input type="hidden" name="idtimeline" value="<?php echo $idTimeline; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
            <!-- <input type="hidden" value="" name="coordenadas" id="coordenadas" /> -->
            <input type='hidden' name='imagem' id='imagem-value' value='<?=$timeline['imagem']?>'>
            <input type='hidden' name='imagem_2' id='imagem_2-value' value='<?=$timeline['imagem_2']?>'>
		</form>
	</div>

<?php } ?>



<!--************************************
     _       _        _        _     _
    | |     | |      | |      | |   | |
  __| | __ _| |_ __ _| |_ __ _| |__ | | ___
 / _` |/ _` | __/ _` | __/ _` | '_ \| |/ _ \
| (_| | (_| | || (_| | || (_| | |_) | |  __/
 \__,_|\__,_|\__\__,_|\__\__,_|_.__/|_|\___|
					*******************************-->


<?php if($_REQUEST['acao'] == "listarTimeline"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<i class="fas fa-bars fa-2x"></i>
		<span>Listagem de Timeline</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=timeline&acao=listarTimeline">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=timeline&acao=formTimeline&met=cadastroTimeline">Cadastro</a></li>
		</ul>
	</div>
	<div class="search">
		<form name="formbusca" method="post" action="#" onsubmit="return false">
			<input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida" />
		</form>
		<a href="" class="search_bt">Busca Avançada</a>
	</div>
	<div class="advanced">
		<form name="formAvancado" id="formAvancado" method="post" action="#" onsubmit="return false">
			<p class="advanced_tit">Busca Avançada</p>
			<img class="advanced_close" src="images/ico_close.png" height="10" width="11" alt="ico" />
			<div class="box_ip"><label for="adv_titulo_aba">Título</label><input type="text" name="titulo" id="adv_titulo_aba"></div>
            <div class="box_ip">
    			<div class="box_sel">
    				<label for="adv_status">Status</label>
    				<div class="box_sel_d">
    					<select name="status" id="adv_status" class=''>
    						<option value=""></option>
    						<option value="1"> Ativo </option>
    						<option value="0"> Inativo </option>
    					</select>
    				</div>
    			</div>
            </div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>




	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Timeline</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=timeline&acao=formTimeline&met=cadastroTimeline"><img src="images/novo.png" alt="Cadastro Timeline" title="Cadastrar Timeline" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=timeline&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=timeline&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
			</ul>
		</div>
		<table class="table" cellspacing="0" cellpadding="0" id="listagem">
			<thead>
			</thead>
			<tbody>
			</tbody>
		</table>
		<?php include_once("paginacao/paginacao.php"); ?> 




		<?php
			$dados = isset($_POST) ? $_POST : array();
			$buscar = '';
			foreach($dados as $k=>$v){
				if(!empty($v))
					$buscar .= $k.'='.$v.'&';
			}
		?>


		<script type="text/javascript">
			queryDataTable = '<?php print $buscar; ?>';
			requestInicio = "tipoMod=timeline&p="+preventCache+"&";
			ordem = "idtimeline";
			dir = "desc";
			$(document).ready(function(){
				preTableTimeline();
			});
			dataTableTimeline('<?php print $buscar; ?>');
			columnTimeline();
		</script>




	</div>

<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>