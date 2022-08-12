<?php 
	 // Versao do modulo: 3.00.010416

	include_once "relatorios_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="relatorios_css.css" />
<script type="text/javascript" src="relatorios_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formRelatorios"){
	if($_REQUEST['met'] == "cadastroRelatorios"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "relatorios_script.php?opx=cadastroRelatorios";
		$metodo_titulo = "Cadastro Relatorios";
		$idRelatorios = 0 ;

		// dados para os campos
		$relatorios['titulo'] = "";
		$relatorios['imagem'] = "";
      // $relatorios['imagem_2'] = "";
		$relatorios['status'] = "";
      $relatorios['texto'] = "";
		$relatorios['ano'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "relatorios_script.php?opx=editRelatorios";
		$metodo_titulo = "Editar Relatorios";
		$idRelatorios = (int) $_GET['idu'];
		$relatorios = buscaRelatorios(array('idrelatorios'=>$idRelatorios));
		if (count($relatorios) != 1) exit;
		$relatorios = $relatorios[0];
	}
?>

	<div id="titulo">
		<!-- <img src="images/modulos/relatorios_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-paste fa-2x"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=relatorios&acao=listarRelatorios">Listagem</a></li>
			<!--<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=relatorios&acao=formRelatorios&met=cadastroRelatorios">Cadastro</a></li> -->
		</ul>
	</div>

	<div id="principal">
		<form class="form" name="formRelatorios" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('titulo_aba')); " enctype="multipart/form-data">

			<div id="informacaoRelatorios" class="content">
				<div class="content_tit">Dados Relatorios:</div>
				<div class="box_ip">
					<label for="titulo">Título</label>
					<input type="text" name="titulo" id="titulo_aba" class='required' size="30" value="<?php echo $relatorios['titulo']; ?>" />
				</div>
				<div class="box_ip">
					<label for="ano">Ano</label>
					<input type="text" name="ano" id="ano" class='required' size="30" maxlength="4" value="<?=$relatorios['ano']?>"/>
				</div>
            <div class="box_ip box_txt">
               <label for="texto">Texto</label>
               <textarea name="texto" id="texto" class="required" size="30"><?php echo $relatorios['texto']; ?></textarea>
            </div>

            <div class="box_ip">
                <label for="arquivo" class="focus-static">Arquivo</label>
                <input type="file" name="arquivo" id="arquivo">
                <?php if(!empty($relatorios['arquivo'])):?>
                    <span style="font-family: 'RobotoM'">
                        Arquivo atual: 
                        <a style="display: inline-block;margin-bottom: 5px;" href="files/relatorios/arquivos/<?=$relatorios['arquivo']?>" download><?=$relatorios['arquivo']?></a>
                        <a data-arquivo="<?=$relatorios['arquivo']?>" data-name="arquivo" class="excluir-arquivo" style="cursor: pointer;">Excluir arquivo</a>
                    </span>
                <?php endif;?>
            </div>

				<div class="box_sel" style="width: 48.5%;">
					<label for="status">Status</label>
					<div class="box_sel_d">
						<select name="status" id="status" class='required'>
							<option value="1" <?php print($relatorios['status'] == "1" ? ' selected="selected" ' : ''); ?>> Ativo </option>
							<option value="0" <?php print($relatorios['status'] == "0" ? ' selected="selected" ' : ''); ?>> Inativo </option>
						</select>
					</div>
				</div>

			</div>

			<input type="hidden" name="imagem" id="imagem_old" size="30" maxlength="" value="<?php echo $relatorios['imagem']; ?>" />
            <input type="hidden" name="imagem_2" id="imagem_2_old" size="30" maxlength="" value="<?php echo $relatorios['imagem_2']; ?>" />
            <input type="hidden" name="arquivo_old" id="arquivo_old" size="30" value="<?php echo $relatorios['arquivo']; ?>" />
			<input type="hidden" id="idrelatorios" name="idrelatorios" value="<?php echo $idRelatorios; ?>" />
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


<?php if($_REQUEST['acao'] == "listarRelatorios"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<!-- <img src="images/modulos/relatorios_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-paste fa-2x"></i>
		<span>Listagem de Relatorios</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=relatorios&acao=listarRelatorios">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=relatorios&acao=formRelatorios&met=cadastroRelatorios">Cadastro</a></li>
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
			<div class="box_sel">
				<label for="adv_status">Status</label>
				<div class="box_sel_d">
					<select name="status" id="adv_status" class='required'>
						<option value=""></option>
						<option value="1"> Ativo </option>
						<option value="0"> Inativo </option>
					</select>
				</div>
			</div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>




	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Relatorios</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=relatorios&acao=formRelatorios&met=cadastroRelatorios"><img src="images/novo.png" alt="Cadastro Relatorios" title="Cadastrar Relatorios" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=relatorios&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=relatorios&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=relatorios&p="+preventCache+"&";
			ordem = "idrelatorios";
			dir = "desc";
			$(document).ready(function(){
				preTableRelatorios();
			});
			dataTableRelatorios('<?php print $buscar; ?>');
			columnRelatorios();
		</script>




	</div>

<?php } ?>

