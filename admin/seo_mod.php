<?php 
	 // Versao do modulo: 3.00.010416

	include_once "seo_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="seo_css.css" />
<script type="text/javascript" src="seo_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formSeo"){
	if($_REQUEST['met'] == "cadastroSeo"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
			exit;
		}
		$action = "seo_script.php?opx=cadastroSeo";
		$metodo_titulo = "Cadastro Seo";
		$idSeo = 0 ;

		// dados para os campos
		$seo['title'] = "";
		$seo['description'] = "";
        $seo['keywords'] = "";
        $seo['urlrewrite'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
			exit;
		}
		$action = "seo_script.php?opx=editSeo";
		$metodo_titulo = "Editar Seo";
		$idSeo = (int) $_GET['idu'];
		$seo = buscaSeo(array('idseo'=>$idSeo));
		if (count($seo) != 1) exit;
		$seo = $seo[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=seo&acao=listarSeo">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=seo&acao=formSeo&met=cadastroSeo">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formSeo" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('title'));">
			<div id="informacaoSeo" class="content">
				<div class="content_tit">Dados Seo:</div>
                <div class="box_ip separar" style='width:100%'>
                    <label for="title">Title</label>
                    <input name="title" id="title" class='required' value="<?php echo $seo['title']; ?>">
                </div>
                <div class="box_ip separar" style='width:100%'>
                   <label for="urlrewrite">Url</label>
                   <input name="urlrewrite" id="urlrewrite" class='required' value="<?php echo $seo['urlrewrite']; ?>">
                </div>
                <div class="box_ip separar" style='width:100%'>
                    <label for="description">Description</label for="">
                    <textarea name="description" id="description" class=''><?php echo $seo['description']; ?></textarea>
                </div>
                <div class="box_ip separar" style='width:100%'>
                   <label for="keywords">Keywords</label>
                   <textarea name="keywords" id="keywords" class=''><?php echo $seo['keywords']; ?></textarea>
                </div>
			</div>
			<input type="hidden" name="idseo" value="<?php echo $idSeo; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
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


<?php if($_REQUEST['acao'] == "listarSeo"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span>Listagem de Seo</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=seo&acao=listarSeo">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=seo&acao=formSeo&met=cadastroSeo">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_title">Title</label><input type="text" name="title" id="adv_title"></div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>




	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Seo</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=seo&acao=formSeo&met=cadastroSeo"><img src="images/novo.png" alt="Cadastro Seo" title="Cadastrar Seo" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=seo&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=seo&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=seo&p="+preventCache+"&";
			ordem = "idseo";
			dir = "asc";
			$(document).ready(function(){
				preTableSeo();
			});
			dataTableSeo('<?php print $buscar; ?>');
			columnSeo();
		</script>




	</div>

<?php } ?>