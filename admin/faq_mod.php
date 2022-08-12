<?php 
	 // Versao do modulo: 3.00.010416

	include_once "faq_class.php";
    include_once "suporte_class.php";

    $suporte = buscaSuporte();
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="faq_css.css" />
<script type="text/javascript" src="faq_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formFaq"){
	if($_REQUEST['met'] == "cadastroFaq"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "faq_script.php?opx=cadastroFaq";
		$metodo_titulo = "Cadastro Faq";
		$idFaq = 0 ;

		// dados para os campos
		$faq['pergunta'] = "";
		$faq['resposta'] = "";
		$faq['status'] = "";
		$faq['ordem'] = "";
        $faq['idsuporte'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "faq_script.php?opx=editFaq";
		$metodo_titulo = "Editar Faq";
		$idFaq = (int) $_GET['idu'];
		$faq = buscaFaq(array('idfaq'=>$idFaq));
		if (count($faq) != 1) exit;
		$faq = $faq[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=faq&acao=listarFaq">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=faq&acao=formFaq&met=cadastroFaq">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formFaq" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('pergunta'));">
			<div id="informacaoFaq" class="content">
				<div class="content_tit">Dados Faq:</div>
				<div class="box_ip separar box_txt">
					<label for="pergunta">Pergunta</label>
					<textarea name="pergunta" id="pergunta" class=''><?php echo $faq['pergunta']; ?></textarea>
				</div>
				<div class="box_ip separar box_txt">
					<label for="resposta">Resposta</label>
					<textarea name="resposta" id="resposta" class=''><?php echo $faq['resposta']; ?></textarea>
				</div>
                <div class="box_ip">
                    <label for="idsuporte">Suporte</label>
                    <div class="box_sel box_txt">
                        <label for="">Suporte</label>
                        <div class="box_sel_d box_txt">
                            <select name="idsuporte" id="idsuporte" class=''>
                                <option></option>
                                <?php foreach($suporte as $key => $s):?>
                                    <option value="<?=$s['idsuporte']?>" <?php print ($faq['idsuporte'] == $s['idsuporte'] ? ' selected="selected" ' : ''); ?> > <?=$s['titulo']?> </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
				<div class="box_ip">
					<label for="status">Status</label>
					<div class="box_sel box_txt">
						<label for="">Status</label>
						<div class="box_sel_d box_txt">
							<select name="status" id="status" class=''>
								<!-- <option></option> -->
								<option value="A" <?php print ($faq['status'] == "A" ? ' selected="selected" ' : ''); ?> > Ativo </option>
								<option value="I" <?php print ($faq['status'] == "I" ? ' selected="selected" ' : ''); ?> > Inativo </option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="idfaq" value="<?php echo $idFaq; ?>" />
			<input type="hidden" name="ordem" value="<?php echo $faq['ordem']; ?>" />
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


<?php if($_REQUEST['acao'] == "listarFaq"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-question-circle" aria-hidden="true"></i>
		<span>Listagem de Faq</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=faq&acao=listarFaq">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=faq&acao=formFaq&met=cadastroFaq">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Faq</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=faq&acao=formFaq&met=cadastroFaq"><img src="images/novo.png" alt="Cadastro Faq" title="Cadastrar Faq" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=faq&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=faq&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=faq&p="+preventCache+"&";
			ordem = "ordem";
			dir = "asc";
			$(document).ready(function(){
				preTableFaq();
			});
			dataTableFaq('<?php print $buscar; ?>');
			columnFaq();
		</script>




	</div>

<?php } ?>

