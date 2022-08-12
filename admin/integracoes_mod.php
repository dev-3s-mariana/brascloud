<?php 
	 // Versao do modulo: 3.00.010416

	include_once "integracoes_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="integracoes_css.css" />
<script type="text/javascript" src="integracoes_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formIntegracoes"){
	if($_REQUEST['met'] == "cadastroIntegracoes"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
			exit;
		}
		$action = "integracoes_script.php?opx=cadastroIntegracoes";
		$metodo_titulo = "Cadastro Integracoes";
		$idIntegracoes = 0 ;

		// dados para os campos
		$integracoes['integracao'] = "";
		$integracoes['token'] = "";
        $integracoes['usuario'] = "";
        $integracoes['senha'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
			exit;
		}
		$action = "integracoes_script.php?opx=editIntegracoes";
		$metodo_titulo = "Editar Integracoes";
		$idIntegracoes = (int) $_GET['idu'];
		$integracoes = buscaIntegracoes(array('idintegracoes'=>$idIntegracoes));
		if (count($integracoes) != 1) exit;
		$integracoes = $integracoes[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=integracoes&acao=listarIntegracoes">Listagem</a></li>
			<!-- <li class="others_abs_br"></li> -->
			<!-- <li class="other_abs_li"><a href="index.php?mod=integracoes&acao=formIntegracoes&met=cadastroIntegracoes">Cadastro</a></li> -->
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formIntegracoes" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('title'));">
			<div id="informacaoIntegracoes" class="content">
				<div class="content_tit">Dados Integracoes:</div>
                <div class="box_ip separar" style='width:100%'>
                   <label for="usuario">Usuário</label>
                   <input name="usuario" id="usuario" class='' value="<?php echo $integracoes['usuario']; ?>">
                </div>
                <div class="box_ip separar" style='width:100%'>
                   <label for="senha">Senha</label>
                   <input type="password" name="senha" id="senha" class='' value="<?php echo $integracoes['senha']; ?>">
                </div>
                <div class="box_ip separar" style='width:100%'>
                   <label for="token">Token</label>
                   <input name="token" id="token" class='required' value="<?php echo $integracoes['token']; ?>">
                </div>
			</div>
			<input type="hidden" name="idintegracoes" value="<?php echo $idIntegracoes; ?>" />
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


<?php if($_REQUEST['acao'] == "listarIntegracoes"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Você não tem privilégios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span>Listagem de Integracoes</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=integracoes&acao=listarIntegracoes">Listagem</a></li>
			<!-- <li class="others_abs_br"></li> -->
			<!-- <li class="other_abs_li"><a href="index.php?mod=integracoes&acao=formIntegracoes&met=cadastroIntegracoes">Cadastro</a></li> -->
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Integracoes</a></li>
			</ul>
			<ul class="abas_bts">
				<!-- <li class="abas_bts_li"><a href="index.php?mod=integracoes&acao=formIntegracoes&met=cadastroIntegracoes"><img src="images/novo.png" alt="Cadastro Integracoes" title="Cadastrar Integracoes" /></a></li> -->
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=integracoes&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=integracoes&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=integracoes&p="+preventCache+"&";
			ordem = "idintegracoes";
			dir = "asc";
			$(document).ready(function(){
				preTableIntegracoes();
			});
			dataTableIntegracoes('<?php print $buscar; ?>');
			columnIntegracoes();
		</script>




	</div>

<?php } ?>