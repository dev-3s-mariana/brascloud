<?php 
	 // Versao do modulo: 3.00.010416

	include_once "area_pretendida_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="area_pretendida_css.css" />
<script type="text/javascript" src="area_pretendida_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formArea_pretendida"){
	if($_REQUEST['met'] == "cadastroArea_pretendida"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "area_pretendida_script.php?opx=cadastroArea_pretendida";
		$metodo_titulo = "Cadastro Área Pretendida";
		$idArea_pretendida = 0 ;

		// dados para os campos
		$area_pretendida['nome'] = "";
		$area_pretendida['email'] = "";
		$area_pretendida['status'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "area_pretendida_script.php?opx=editArea_pretendida";
		$metodo_titulo = "Editar Área Pretendida";
		$idArea_pretendida = (int) $_GET['idu'];
		$area_pretendida = buscaArea_pretendida(array('idarea_pretendida'=>$idArea_pretendida));
		if (count($area_pretendida) != 1) exit;
		$area_pretendida = $area_pretendida[0];
	}
?>
	<div id="titulo">
		<i class="fas fa-folder"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=area_pretendida&acao=listarArea_pretendida">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=area_pretendida&acao=formArea_pretendida&met=cadastroArea_pretendida">Cadastro</a></li>
		</ul>
	</div>
 
	<div id="principal">
		<form class="form" name="formArea_pretendida" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome', 'email', 'status')); " >
			<div id="informacaoArea_pretendida" class="content">
				<div class="content_tit">Dados Área Pretendida:</div>
				<div class="box_ip">
					<label for="nome">Nome</label>
					<input type="text" name="nome" id="nome" value="<?php echo $area_pretendida['nome']; ?>" class=''/>
				</div>
				<div class="box_ip">
					<label for="email">E-mail</label>
					<input type="text" name="email" id="email" value="<?php echo $area_pretendida['email']; ?>"  class=''/>
				</div>
				<div class="box_ip">
					<div class="box_sel box_txt">
						<label for="">Status</label>
						<div class="box_sel_d">
							<select name="status" id="status" class=''>
								<option></option>
								<option value="A" <?php print ($area_pretendida['status'] == "A" ? ' selected="selected" ' : ''); ?> > Ativo </option>
								<option value="I" <?php print ($area_pretendida['status'] == "I" ? ' selected="selected" ' : ''); ?> > Inativo </option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="idarea_pretendida" value="<?php echo $idArea_pretendida; ?>" />
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


<?php if($_REQUEST['acao'] == "listarArea_pretendida"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<i class="fas fa-folder"></i>
		<span>Listagem de Área Pretendida</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=area_pretendida&acao=listarArea_pretendida">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=area_pretendida&acao=formArea_pretendida&met=cadastroArea_pretendida">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_nome">Nome</label><input type="text" name="nome" id="adv_nome"></div>
			<div class="box_ip"><label for="adv_email">E-mail</label><input type="text" name="email" id="adv_email"></div>
            <div class="box_ip">
                <label for="status">Status</label>
                <div class="box_sel box_txt">
                  <label for="">Status</label>
                  <div class="box_sel_d">
                       <select name="status" id="status" class=''>
                      		<option></option>
                        	<option value="A"> Ativo </option>
                           <option value="I"> Inativo </option>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Área Pretendida</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=area_pretendida&acao=formArea_pretendida&met=cadastroArea_pretendida"><img src="images/novo.png" alt="Cadastro Área Pretendida" title="Cadastrar Área Pretendida" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=area_pretendida&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=area_pretendida&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=area_pretendida&p="+preventCache+"&";
			ordem = "idarea_pretendida";
			dir = "desc";
			$(document).ready(function(){
				preTableArea_pretendida();
			});
			dataTableArea_pretendida('<?php print $buscar; ?>');
			columnArea_pretendida();
		</script> 

	</div>

<?php } ?>