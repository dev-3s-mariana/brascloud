<?php 
	 // Versao do modulo: 3.00.010416
	include_once "newsletter_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="newsletter_css.css" />
<script type="text/javascript" src="newsletter_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->

<?php if($_REQUEST['acao'] == "formNewsletter"){
	if($_REQUEST['met'] == "cadastroNewsletter"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "newsletter_script.php?opx=cadastroNewsletter";
		$metodo_titulo = "Cadastro Newsletter";
		$idNewsletter = 0 ;

		// dados para os campos
		$newsletter['titulo'] = "";
		$newsletter['email'] = "";
		
		
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "newsletter_script.php?opx=editNewsletter";
		$metodo_titulo = "Editar Newsletter";
		$idNewsletter = (int) $_GET['idu'];
		$newsletter = buscaNewsletter(array('idnewsletter'=>$idNewsletter));
		if (count($newsletter) != 1) exit;
		$newsletter = $newsletter[0];
	}
?>

	<div id="titulo">
		<i class="fas fa-newspaper" aria-hidden="true"></i>
		<!-- <img src="images/modulos/newsletter_cinza.png" height="24" width="24" alt="ico" /> -->
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=newsletter&acao=listarNewsletter">Listagem</a></li>
			<li class="others_abs_br"></li>

		</ul>
	</div>

	<div id="principal">
		<form class="form" name="formNewsletter" id="formNewsletter" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" >

			<div id="informacaoNewsletter" class="content">
				<div class="content_tit">Dados Newsletter:</div>
					<div class="box_ip">
						<label for="nome">Nome</label>
						<input type="text" class="" name="nome" id="nome" size="30" maxlength="255" value="<?php echo $newsletter['nome']; ?>" />
					</div>

					<div class="box_ip">
						<label for="email">E-mail</label>
						<input type="text" class="" name="email" id="email" size="30" maxlength="255" value="<?php echo $newsletter['email']; ?>" />
					</div>

			<input type="hidden" name="idnewsletter" value="<?php echo $idNewsletter; ?>" id="idnewsletter" />
			<input type="button" value="Salvar" class="bt_save salvar" id="enviar_newsletter"  />
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


<?php if($_REQUEST['acao'] == "listarNewsletter"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<i class="fas fa-newspaper" aria-hidden="true"></i>
		<!-- <img src="images/modulos/newsletter_cinza.png" height="22" width="24" alt="ico" /> -->
		<span>Listagem de Newsletter</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=newsletter&acao=listarNewsletter">Listagem</a></li>
			<li class="others_abs_br"></li>
		</ul>
	</div>
	<div class="search">
		<form name="formbusca" method="post" action="#" onsubmit="return false">
			<input type="text" name="buscarapida" value="Buscar" onblur="campoBuscaEscreve(this);" onfocus="campoBuscaLimpa(this);" id="buscarapida" />
		</form>
		<!-- <a href="" class="search_bt">Busca Avançada</a>-->
	</div>
	<!-- <div class="advanced">
		<form name="formAvancado" id="formAvancado" method="post" action="#" onsubmit="return false" >
			<p class="advanced_tit">Busca Avançada</p>
			<img class="advanced_close" src="images/ico_close.png" height="10" width="11" alt="ico" />
			<div class="box_ip"><label for="adv_email">email</label><input type="text" name="email" id="adv_email"></div>
			
		
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div> -->

	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Newsletter</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=newsletter&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=newsletter&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=newsletter&p="+preventCache+"&";
			ordem = "idnewsletter";
			dir = "desc";
			$(document).ready(function(){
				preTableNewsletter();
			});
			dataTableNewsletter('<?php print $buscar; ?>');
			columnNewsletter();
		</script>

	</div>

<?php } ?>

