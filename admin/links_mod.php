<?php 
	 // Versao do modulo: 3.00.010416

	include_once "links_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="links_css.css" />
<script type="text/javascript" src="links_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formLinks"){
	if($_REQUEST['met'] == "cadastroLinks"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "links_script.php?opx=cadastroLinks";
		$metodo_titulo = "Cadastro Links";
		$idLinks = 0 ;

		// dados para os campos
		$links['nome'] = "";
        $links['link'] = "";
		$links['descricao'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "links_script.php?opx=editLinks";
		$metodo_titulo = "Editar Links";
		$idLinks = (int) $_GET['idu'];
		$links = buscaLinks(array('idlinks'=>$idLinks));
		if (count($links) != 1) exit;
		$links = $links[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-paperclip" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=links&acao=listarLinks">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=links&acao=formLinks&met=cadastroLinks">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formLinks" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoLinks" class="content">
				<div class="content_tit">Dados Links:</div>
				<div class="box_ip separar box_txt">
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $links['nome']; ?>"/>
				</div>

                <div class="box_ip separar box_txt">
                    <label for="link">Link</label>
                    <input name="link" id="link" class='' value="<?php echo $links['link']; ?>"/>
                </div>
				<!-- <div class="box_ip separar" style='width:100%'>
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class=''><?php echo $links['descricao']; ?></textarea>
				</div> -->
			</div>
			<input type="hidden" name="idlinks" value="<?php echo $idLinks; ?>" />
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


<?php if($_REQUEST['acao'] == "listarLinks"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-paperclip" aria-hidden="true"></i>
		<span>Listagem de Links</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=links&acao=listarLinks">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=links&acao=formLinks&met=cadastroLinks">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Links</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=links&acao=formLinks&met=cadastroLinks"><img src="images/novo.png" alt="Cadastro Links" title="Cadastrar Links" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=links&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=links&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=links&p="+preventCache+"&";
			ordem = "idlinks";
			dir = "asc";
			$(document).ready(function(){
				preTableLinks();
			});
			dataTableLinks('<?php print $buscar; ?>');
			columnLinks();
		</script>




	</div>

<?php } ?>

