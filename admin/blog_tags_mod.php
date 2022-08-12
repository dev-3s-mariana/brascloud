<?php 
	 // Versao do modulo: 3.00.010416

	include_once "blog_tags_class.php";
	include_once "includes/functions.php";
    // $icone = buscaFW3(array('ordem' => 'nome', 'dir' => 'asc'));
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = ""; 
     
    $width = 150;
    $height = 150;

    $width2 = 42;
    $height2 = 42;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="blog_tags_css.css" />
<script type="text/javascript" src="blog_tags_js.js"></script>

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


<?php if($_REQUEST['acao'] == "formBlog_tags"){
	if($_REQUEST['met'] == "cadastroBlog_tags"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_tags_script.php?opx=cadastroBlog_tags";
		$metodo_titulo = "Cadastro de Tags";
		$idBlog_tags = 0 ;
      $FontAwesome = false;

		// dados para os campos
		$blog_tags['titulo'] = "";
        $blog_tags['numero'] = "";
        $blog_tags['sufixo'] = "";
        $blog_tags['prefixo'] = "";
		$blog_tags['icone'] = "";
        $blog_tags['icone_name'] = "";
        $blog_tags['home'] = "";
        $blog_tags['imagem'] = "";
        $blog_tags['urlrewrite'] = "";
        $blog_tags['status'] = "";
	}

	if($_REQUEST['met'] == "editBlog_tags"){
		
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_tags_script.php?opx=editBlog_tags";
		$metodo_titulo = "Editar Tags";
		$idBlog_tags = (int) $_GET['idu'];
		$blog_tags = buscaBlog_tags(array('idblog_tags'=>$idBlog_tags));

		if (count($blog_tags) != 1) exit;
		$blog_tags = $blog_tags[0];

        $StringIcone = strlen($blog_tags['icone']);
        if ($StringIcone > 3) {
            $FontAwesome = false;
            $blog_tags['icone_name'] = '';
        } else {
            $FontAwesome = true;
            // $icones_Edit = buscaFW3(array('idfw' => $blog_tags['icone']));
            // $icones_Edit = $icones_Edit[0];
        }
	}
?>

	<div id="titulo">
		<i class="fas fa-cubes" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=blog_tags&acao=listarBlog_tags">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=blog_tags&acao=formBlog_tags&met=cadastroBlog_tags">Cadastro</a></li>
		</ul>
	</div>
  
	<div id="principal">
		<form class="form" name="formBlog_tags" id="formBlog_tags" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="return verificarCampos(new Array('nome'));">

			<div id="informacaoBlog_tags" class="content">
				<div class="content_tit">Dados Tags:</div>
					<div class="box_ip ">
						<label for="nome">Título</label>
						<input type="text" name="titulo" id="nome" class="" size="30" maxlength="255" value="<?php echo $blog_tags['titulo']; ?>"/>
					</div>

                    <div class="box_ip ">
                        <label for="urlrewrite">Url</label>
                        <input type="text" name="urlrewrite" id="urlrewrite" class="" size="30" maxlength="255" value="<?php echo $blog_tags['urlrewrite']; ?>"/>
                    </div>

                    <div class="box_ip">
                        <div class="box_sel box_txt">
                            <label for="status">Status</label>
                            <div class="box_sel_d">
                                <select name="status" id="status" class="">
                                    <!-- <option></option> -->
                                    <option value="1" <?php print ($blog_tags['status'] == "1" ? ' selected="selected" ' : ''); ?> > Ativo </option>
                                    <option value="2" <?php print ($blog_tags['status'] == "2" ? ' selected="selected" ' : ''); ?> > Inativo </option>
                                </select>
                            </div>
                       </div>
                    </div>

			</div> 
            
			<input type="hidden" name="idblog_tags" id="idblog_tags" value="<?= $idBlog_tags; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type="hidden" name="imagem" value="<?php echo $blog_tags['imagem']; ?>" />
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


<?php if($_REQUEST['acao'] == "listarBlog_tags"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
 
	<div id="titulo">
		<i class="fas fa-cubes" aria-hidden="true"></i>
		<span>Listagem de Tags</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=blog_tags&acao=listarBlog_tags">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=blog_tags&acao=formBlog_tags&met=cadastroBlog_tags">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Tags</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=blog_tags&acao=formBlog_tags&met=cadastroBlog_tags"><img src="images/novo.png" alt="Cadastro de Tags" title="Cadastrar Tags" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_tags&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_tags&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=blog_tags&p="+preventCache+"&";
			ordem = "ordem";
			dir = "asc";
			$(document).ready(function(){
				preTableBlog_tags();
			});
			dataTableBlog_tags('<?php print $buscar; ?>');
			columnBlog_tags();
		</script> 

	</div>

<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>