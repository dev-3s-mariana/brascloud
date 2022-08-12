<?php 
	 // Versao do modulo: 3.00.010416

	include_once "contratar_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="contratar_css.css" />
<script type="text/javascript" src="contratar_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formContratar"){
	if($_REQUEST['met'] == "cadastroContratar"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "contratar_script.php?opx=cadastroContratar";
		$metodo_titulo = "Cadastro Contratar";
		$idContratar = 0 ;

		// dados para os campos
		$contratar['nome'] = "";
        $contratar['preco'] = "";
		$contratar['descricao'] = "";
        $contratar['texto'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "contratar_script.php?opx=editContratar";
		$metodo_titulo = "Editar Contratar";
		$idContratar = (int) $_GET['idu'];
		$contratar = buscaContratar(array('idcontratar'=>$idContratar));
		if (count($contratar) != 1) exit;
		$contratar = $contratar[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-align-justify" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=contratar&acao=listarContratar">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=contratar&acao=formContratar&met=cadastroContratar">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formContratar" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoContratar" class="content">
				<div class="content_tit">Dados Contratar:</div>
				<div class="box_ip separar box_txt">
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $contratar['nome']; ?>"/>
				</div>

              <!--   <div class="box_ip separar box_txt">
                    <label for="preco">Preço</label>
                    <input name="preco" id="preco" class='' value="<?php echo $contratar['preco']; ?>"/>
                </div> -->
				<div class="box_ip separar box_txt">
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class=''><?php echo $contratar['descricao']; ?></textarea>
				</div>
                <div class="box_ip separar box_txt">
                    <label for="texto">Texto</label>
                    <textarea name="texto" id="texto" class=''><?php echo $contratar['texto']; ?></textarea>
                </div>
			</div>
			<input type="hidden" name="idcontratar" value="<?php echo $idContratar; ?>" />
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


<?php if($_REQUEST['acao'] == "listarContratar"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-align-justify" aria-hidden="true"></i>
		<span>Listagem de Contratar</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=contratar&acao=listarContratar">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=contratar&acao=formContratar&met=cadastroContratar">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Contratar</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=contratar&acao=formContratar&met=cadastroContratar"><img src="images/novo.png" alt="Cadastro Contratar" title="Cadastrar Contratar" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=contratar&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=contratar&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=contratar&p="+preventCache+"&";
			ordem = "idcontratar";
			dir = "asc";
			$(document).ready(function(){
				preTableContratar();
			});
			dataTableContratar('<?php print $buscar; ?>');
			columnContratar();
		</script>




	</div>

<?php } ?>

