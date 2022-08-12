<?php
	 // Versao do modulo: 2.20.130114

	include_once "banner_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";

    $width = 1044;
    $height = 829;

    $width2 = 300;
    $height2 = 600;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="banner_css.css" />
<script type="text/javascript" src="banner_js.js"></script>

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


<?php if($_REQUEST['acao'] == "formBanner"){
	if($_REQUEST['met'] == "cadastroBanner"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "banner_script.php?opx=cadastroBanner";
		$metodo_titulo = "Cadastro Banner";
		$idBanner = 0 ;

        // dados para os campos
        $banner['nome'] = "";
        $banner['link'] = "";
        $banner['ordem'] = 0;
        $banner['status'] = "";
        $banner['banner_full'] = "";
        $banner['banner_mobile'] = "";
        $banner['idbanner'] = "";
        $banner['dinamico'] = 0;
        $banner['subtitulo'] = "";
        $banner['titulo_botao'] = "";
}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "banner_script.php?opx=editBanner";
		$metodo_titulo = "Editar Banner";
		$idBanner = $_GET['idu'];
		$banner = buscaBanner(array('idbanner'=>$idBanner));
		$banner = $banner[0];
	}
?>

	<div id="titulo">
		<i class="fas fa-image" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=banner&acao=listarBanner">Listagem</a></li>
		</ul>
	</div>


    <div id="principal">
    	<form class="form" name="formBanner" id="formBanner" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="return verificarCampos(new Array('nome'));">
            <div id="informacaoBanner" class="content">
                <div class="content_tit">Dados Banner:</div>
                <div class="box_ip box_txt">
                    <label for="nome">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?php echo $banner['nome']; ?>" class=""/>
                </div>

                <div class="box_ip link_video box_txt">
                    <label for="link">Link</label>
                    <input type="text" name="link" id="link" value="<?php echo $banner['link']; ?>" class=""/>
                </div>

                <div class="box_ip">
                    <div class="box_sel box_txt">
                        <label for="dinamico">Tipo</label>
                        <div class="box_sel_d">
                            <select name="dinamico" id="dinamico" class=''>
                                <!-- <option></option> -->
                                <option value="0" <?= ((!$banner['dinamico']) ? ' selected="selected" ' : ''); ?> > Imagem </option>
                                <option value="1" <?= (($banner['dinamico']) ? ' selected="selected" ' : ''); ?> > Dinâmico </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="box_ip">
                    <div class="box_sel box_txt">
                        <label for="status">Status</label>
                        <div class="box_sel_d">
                            <select name="status" id="status" class="">
                                <!-- <option></option> -->
                                <option value="1" <?php print ($banner['status'] == "1" ? ' selected="selected" ' : ''); ?> > Ativo </option>
                                <option value="0" <?php print ($banner['status'] == "0" ? ' selected="selected" ' : ''); ?> > Inativo </option>
                            </select>
                        </div>
                   </div>
                </div>

                <div class='bannerDinamico' <?= ((!$banner['dinamico']) ? 'style="display:none;"' : ''); ?>>
                    <div class="box_ip box_txt">
                      <label for="subtitulo">Subtítulo</label>
                      <textarea type="text" name="subtitulo" id="subtitulo"><?= $banner['subtitulo']; ?></textarea>
                    </div>
                    <div class="box_ip">
                      <label for="titulo_botao">Título Botão</label>
                      <input type="text" name="titulo_botao" id="titulo_botao" value="<?= $banner['titulo_botao']; ?>"/>
                    </div>
                </div>

                <!-- CROPPER IMG -->
                <?php $caminho = 'files/banner/'; ?>
                <div id="select-image-1" class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Banner</div>
                                <div class="box_ip imagem-atual" style="<?=empty($banner['banner_full'])?'display: none;':''?>">
                                    <a data-tipo="banner_full" data-img="<?=$banner['banner_full']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="300" src="<?=empty($banner['banner_full'])?'images/cliente/logo.png':$caminho.$banner['banner_full']?>" class="img-banner-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <!--input FILE -->
                                    <input id="inputImage" class="cropped-image" name="imagemCadastrar2" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que:
                                        <?=$tamanho?>
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
                                <div class="content_tit">Banner Mobile</div>
                                <div class="box_ip imagem-atual" style="<?=empty($banner['banner_mobile'])?'display: none;':''?>">
                                    <a data-tipo="banner_mobile" data-img="<?=$banner['banner_mobile']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                    <img width="120" src="<?=empty($banner['banner_mobile'])?'images/cliente/logo.png':$caminho.$banner['banner_mobile']?>" class="img-banner-form" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <!--input FILE -->
                                    <input id="inputImage2" class="cropped-image" name="imagemCadastrar" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width2?>x<?=$height2?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que:
                                        <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize2" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div id="cropper-modal">
                    <!-- /.docs-buttons -->
                    <div class="img-container" id="img-container">
                        <img alt="">
                    </div>
                    <!-- Show the cropped image in modal -->
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
                    </div><!-- /.modal -->
                    <div class="div-save-cropped-image">
                        <input data-image-type='' type="button" value="Salvar" class="save-cropped-image">
                    </div>
                </div>

            </div>


            <input type="hidden" name="ordem" id="ordem" value="<?php echo $banner['ordem'] ?>" />
            <input type="hidden" id="idbanner" name="idbanner" value="<?= $banner['idbanner'] ?>" />
            <input type="hidden" id="mod" name="mod" value="<?= ($banner['idbanner'] == 0)? "cadastro":"editar"; ?>" />
            <input type="submit" value="Salvar" class="bt_save salvar" />
            <input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
            <input type='hidden' name='banner_full' id='imagem-value' value='<?=$banner['banner_full']?>'>
            <input type='hidden' name='banner_mobile' id='imagem_2-value' value='<?=$banner['banner_mobile']?>'>
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


<?php if($_REQUEST['acao'] == "listarBanner"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	 <div id="titulo">
		<i class="fas fa-image" aria-hidden="true"></i>
		<span>Listagem de Banner</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=banner&acao=formBanner&met=cadastroBanner">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv1">Nome</label><input type="text" name="nome" id="adv1"></div>
            <div class="box_ip link_video">
                <label  for="adv_link">Link</label>
                <input type="text" name="link" id="adv_link" size="30" maxlength="255" value="" />
            </div>
            <div class="box_ip">
                <label for="adv_status">Status</label>
                <div class="box_sel box_txt">
                    <label for="">Status</label>
                    <div class="box_sel_d box_txt">
                        <select name="status" id="adv_status">
                            <option></option>
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
                <li class="abas_list_li action"><a href="javascript:void(0)">Banners</a></li>
             </ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=banner&acao=formBanner&met=cadastroBanner"><img src="images/novo.png" alt="Cadastro Banner" title="Cadastrar Banner" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=banner&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=banner&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=banner&p="+preventCache+"&";
			ordem = "ordem";
			dir = "asc";
			$(document).ready(function(){
				preTableBanner();
			});
			dataTableBanner('<?php print $buscar; ?>');
			columnBanner();
		</script>
	</div>

<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>