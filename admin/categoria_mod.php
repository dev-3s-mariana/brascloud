<?php 
	 // Versao do modulo: 3.00.010416

	include_once "categoria_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="categoria_css.css" />
<script type="text/javascript" src="categoria_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formCategoria"){
	if($_REQUEST['met'] == "cadastroCategoria"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "categoria_script.php?opx=cadastroCategoria";
		$metodo_titulo = "Cadastro Categoria";
		$idCategoria = 0 ;

		// dados para os campos
		$categoria['nome'] = "";
		$categoria['descricao'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "categoria_script.php?opx=editCategoria";
		$metodo_titulo = "Editar Categoria";
		$idCategoria = (int) $_GET['idu'];
		$categoria = buscaCategoria(array('idcategoria'=>$idCategoria));
		if (count($categoria) != 1) exit;
		$categoria = $categoria[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-list" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=categoria&acao=listarCategoria">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=categoria&acao=formCategoria&met=cadastroCategoria">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formCategoria" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoCategoria" class="content">
				<div class="content_tit">Dados Categoria:</div>
				<div class="box_ip separar" style='width:100%'>
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $categoria['nome']; ?>"/>
				</div>
				<!-- <div class="box_ip separar" style='width:100%'>
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class=''><?php echo $categoria['descricao']; ?></textarea>
				</div> -->
			</div>
			<input type="hidden" name="idcategoria" value="<?php echo $idCategoria; ?>" />
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


<?php if($_REQUEST['acao'] == "listarCategoria"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-list" aria-hidden="true"></i>
		<span>Listagem de Categoria</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=categoria&acao=listarCategoria">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=categoria&acao=formCategoria&met=cadastroCategoria">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_pergunta">Pergunta</label><input type="text" name="pergunta" id="adv_pergunta"></div>
			<div class="box_ip"><label for="adv_status">Status</label><input type="text" name="status" id="adv_status"></div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>




	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Categoria</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=categoria&acao=formCategoria&met=cadastroCategoria"><img src="images/novo.png" alt="Cadastro Categoria" title="Cadastrar Categoria" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=categoria&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=categoria&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=categoria&p="+preventCache+"&";
			ordem = "idcategoria";
			dir = "asc";
			$(document).ready(function(){
				preTableCategoria();
			});
			dataTableCategoria('<?php print $buscar; ?>');
			columnCategoria();
		</script>




	</div>

<?php } ?>

