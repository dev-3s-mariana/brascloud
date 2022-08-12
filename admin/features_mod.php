<?php 
	 // Versao do modulo: 3.00.010416

	include_once "features_class.php";
	include_once "includes/functions.php";
    // $icone = buscaFW3(array('ordem' => 'nome', 'dir' => 'asc'));
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = ""; 
     
    $width = 150;
    $height = 150;

    $width2 = 60;
    $height2 = 60;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="features_css.css" />
<script type="text/javascript" src="features_js.js"></script>

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


<?php if($_REQUEST['acao'] == "formFeatures"){
	if($_REQUEST['met'] == "cadastroFeatures"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "features_script.php?opx=cadastroFeatures";
		$metodo_titulo = "Cadastro de Features";
		$idFeatures = 0 ;
      $FontAwesome = false;

		// dados para os campos
		$features['titulo'] = "";
        $features['numero'] = "";
        $features['sufixo'] = "";
        $features['prefixo'] = "";
		$features['icone'] = "";
        $features['icone_name'] = "";
        $features['home'] = "";
        $features['imagem'] = "";
	}

	if($_REQUEST['met'] == "editFeatures"){
		
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "features_script.php?opx=editFeatures";
		$metodo_titulo = "Editar Features";
		$idFeatures = (int) $_GET['idu'];
		$features = buscaFeatures(array('idfeatures'=>$idFeatures));

		if (count($features) != 1) exit;
		$features = $features[0];

        $StringIcone = strlen($features['icone']);
        if ($StringIcone > 3) {
            $FontAwesome = false;
            $features['icone_name'] = '';
        } else {
            $FontAwesome = true;
            // $icones_Edit = buscaFW3(array('idfw' => $features['icone']));
            // $icones_Edit = $icones_Edit[0];
        }
	}
?>

	<div id="titulo">
		<i class="fas fa-asterisk" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=features&acao=listarFeatures">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=features&acao=formFeatures&met=cadastroFeatures">Cadastro</a></li>
		</ul>
	</div>
  
	<div id="principal">
		<form class="form" name="formFeatures" id="formFeatures" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="return verificarCampos(new Array('nome'));">

			<div id="informacaoFeatures" class="content">
				<div class="content_tit">Dados Features:</div>

                    <!-- ========== Upload Icone ========== -->
                    <div class="box_ip box_txt">
                        <ul class="tabs">
                            <li class="tab-link <?= @$FontAwesome ? 'current' : ''; ?>" data-tab="tab-1">Escolher um Ícone</li>
                            <li class="tab-link <?= !@$FontAwesome ? 'current' : ''; ?>" data-tab="tab-2">Anexar um Ícone</li>
                        </ul>
                        <div id="tab-1" class="tab-content <?= $FontAwesome ? 'current' : ''; ?>">
                            <span id="icone-titulo" class='labeltxt' for="pesquisar_icone">
                                <strong>Ícone</strong>
                            </span>
                            <?php if ($_GET['met'] == 'editFeatures') : ?>
                                <div id="mostrar_icone">
                                    <i id="current_icon" class='fas fa-<?=$features['icone_name'];?> fa-2x'></i>
                                    <input type="hidden" name="icone" value="<?= $features['icone']; ?>" id="imagem_icone">
                                    <input type="hidden" name="icone_name" value="<?= $features['icone_name']; ?>" id="icone_name">
                                </div>
                            <?php else : ?>
                                <div id="mostrar_icone">
                                    <i id="current_icon" class=''></i>
                                    <input type="hidden" name="icone" id="imagem_icone">
                                    <input type="hidden" name="icone_name" id="icone_name">
                                </div>
                            <?php endif; ?>
                            <input type="text" name="pesquisar_icone" id="pesquisar_icone" placeholder="Pesquise um icone">
                            <div id="icone_pai">
                            </div>
                            <div id="div-page-icon">
                           </div>
                        </div>
                        <div id="tab-2" class="tab-content <?= !$FontAwesome ? 'current' : ''; ?>">
                            <?php $caminho = "files/features/"; ?>
                            <div class="content_tit">Ícone</div>
                            <span class="botaoArquivo" id="inputArquivoBotao">Anexar Ícone <i class="fas fa-paperclip" aria-hidden="true"></i></span>
                            <input type="file" name="icone" id="icone_upload" class="all_imagens" data-tipo='1'><br>
                            <input type="hidden" id="imagem_value">
                            <img class="pump" src="<?= $caminho . $features['icone']; ?>" width='53' id="icone" style="display: <?= $_GET['met'] == 'editFeatures' ? (!empty($features['icone'] && !$FontAwesome) ? 'initial' : 'none') : 'none'; ?>"><br>
                            <p class="pre">Tamanho recomendado: <?=$width2?>x<?=$height2?>px (ou maior proporcional) - Extensão recomendada: jpg, png</p>
                            <span>O arquivo não pode ser maior que: <?=$tamanho?>
                            </span>
                            <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                        </div>
                    </div>
                    <script>
                        var div = document.getElementsByClassName("botaoArquivo")[0];
                        var input = document.getElementById("icone_upload");
                        var imagem_value = document.getElementById("imagem_value");

                        div.addEventListener("click", function() {
                            input.click();
                        });
                        input.addEventListener("change", function() {
                            var nome = "sem arquivos...";
                            if (input.files.length > 0) nome = input.files[0].name;
                            div.innerHTML = nome;
                            imagem_value.value = nome;
                        });
                    </script>
                    <!-- ========== Fim Upload Icone ========== -->
					<div class="box_ip">
						<label for="nome">Título</label>
						<input type="text" name="titulo" id="nome" class="" size="30" maxlength="255" value="<?php echo $features['titulo']; ?>"/>
					</div>
               <div class="box_ip">
                  <label for="prefixo">Prefixo</label>
                  <input type="text" name="prefixo" id="prefixo" class="" size="30" maxlength="255" value="<?php echo $features['prefixo']; ?>"/>
               </div>
               <div class="box_ip">
                  <label for="numero">Número</label>
                  <input type="text" name="numero" onkeydown="return somenteNumero(event);" id="numero" class="" size="30" maxlength="255" value="<?php echo $features['numero']; ?>"/>
               </div>
               <div class="box_ip">
                  <label for="sufixo">Sufixo</label>
                  <input type="text" name="sufixo" id="sufixo" class="" size="30" maxlength="255" value="<?php echo $features['sufixo']; ?>"/>
               </div>

               <div class="box_ip">
                  <div class="box_sel box_txt">
                     <label for="home">Página</label>
                     <div class="box_sel_d">
                         <select name="home" id="home" class=''>
                             <option value="0">Todas</option>
                             <option value="1" <?php print ($features['home'] == "1" ? ' selected="selected" ' : ''); ?> > Quero Trabalhar </option>
                             <option value="2" <?php print ($features['home'] == "2" ? ' selected="selected" ' : ''); ?> > Quero Contratar </option>
                             <option value="3" <?php print ($features['home'] == "3" ? ' selected="selected" ' : ''); ?> > Nossos Cases </option>
                         </select>
                     </div>
                  </div>
               </div>

                <!-- CROPPER IMG -->
                <?php $caminho = 'files/features/'; ?>
                <div class="box_ip box_txt pd-left-important">
                    <div class="box_ip box_txt">
                        <div class="img_pricipal">
                            <div>
                                <div class="content_tit">Imagem</div>
                                <?php if ($features['imagem'] != '') { ?>
                                    <div class="box_ip imagem-atual">
                                        <a data-tipo="imagem" data-img="<?=$features['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                        <img src="<?=$caminho.$features['imagem'];?>" class="img-features-form" alt=""/>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="box-img-crop">
                            <input type="hidden" value="" name="coordenadas" id="coordenadas" />
                            <div class="docs-buttons">
                                <div class="btn-group box_txt">
                                    <!--input FILE -->
                                    <input id="" class="cropped-image" name="imagemCadastrar" type="file"/>
                                    <br />
                                    <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                    <span>O arquivo não pode ser maior que: <?=$tamanho?>
                                    </span>
                                    <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                                </div>
                            </div><!-- /.docs-buttons -->
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
                        </div>
                    </div>
                </div>
			</div> 
            
			<input type="hidden" name="idfeatures" id="idfeatures" value="<?= $idFeatures; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type="hidden" name="imagem" value="<?php echo $features['imagem']; ?>" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
            <input type='hidden' name='aspectRatioW2' id='aspectRatioW2' value='<?=$width2?>'>
            <input type='hidden' name='aspectRatioH2' id='aspectRatioH2' value='<?=$height2?>'>
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


<?php if($_REQUEST['acao'] == "listarFeatures"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
 
	<div id="titulo">
		<i class="fas fa-asterisk" aria-hidden="true"></i>
		<span>Listagem de Features</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=features&acao=listarFeatures">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=features&acao=formFeatures&met=cadastroFeatures">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_nome">Título</label><input type="text" name="titulo" id="adv_nome"></div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>
 
	<div id="principal" >
		<div id="abas"> 
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Features</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=features&acao=formFeatures&met=cadastroFeatures"><img src="images/novo.png" alt="Cadastro de Features" title="Cadastrar Features" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=features&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=features&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
				if(!empty($v)){
					$buscar .= $k.'='.$v.'&';
				}
			}
		?>


		<script type="text/javascript">
			queryDataTable = '<?php print $buscar; ?>';
			requestInicio = "tipoMod=features&p="+preventCache+"&";
			ordem = "ordem";
			dir = "asc";
			$(document).ready(function(){
				preTableFeatures();
			});
			dataTableFeatures('<?php print $buscar; ?>');
			columnFeatures();
		</script> 

	</div>

<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>