<?php 
	 // Versao do modulo: 3.00.010416

	include_once "servicos_adicionais_class.php";
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="servicos_adicionais_css.css" />
<script type="text/javascript" src="servicos_adicionais_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formServicos_adicionais"){
	if($_REQUEST['met'] == "cadastroServicos_adicionais"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "servicos_adicionais_script.php?opx=cadastroServicos_adicionais";
		$metodo_titulo = "Cadastro Serviços adicionais";
		$idServicos_adicionais = 0 ;

		// dados para os campos
		$servicos_adicionais['nome'] = "";
        $servicos_adicionais['preco'] = "";
		$servicos_adicionais['descricao'] = "";
        $servicos_adicionais['texto'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "servicos_adicionais_script.php?opx=editServicos_adicionais";
		$metodo_titulo = "Editar Serviços adicionais";
		$idServicos_adicionais = (int) $_GET['idu'];
		$servicos_adicionais = buscaServicos_adicionais(array('idservicos_adicionais'=>$idServicos_adicionais));
		if (count($servicos_adicionais) != 1) exit;
		$servicos_adicionais = $servicos_adicionais[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-cart-plus" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=servicos_adicionais&acao=listarServicos_adicionais">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=servicos_adicionais&acao=formServicos_adicionais&met=cadastroServicos_adicionais">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formServicos_adicionais" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoServicos_adicionais" class="content">
				<div class="content_tit">Dados Serviços adicionais:</div>
				<div class="box_ip separar box_txt">
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $servicos_adicionais['nome']; ?>"/>
				</div>

                <div class="box_ip separar box_txt">
                    <label for="preco">Preço</label>
                    <input name="preco" id="preco" class='money' value="<?php echo $servicos_adicionais['preco']; ?>"/>
                </div>
				<!-- <div class="box_ip separar box_txt">
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class=''><?php echo $servicos_adicionais['descricao']; ?></textarea>
				</div>
                <div class="box_ip separar box_txt">
                    <label for="texto">Texto</label>
                    <textarea name="texto" id="texto" class=''><?php echo $servicos_adicionais['texto']; ?></textarea>
                </div> -->
			</div>
			<input type="hidden" name="idservicos_adicionais" value="<?php echo $idServicos_adicionais; ?>" />
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


<?php if($_REQUEST['acao'] == "listarServicos_adicionais"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-cart-plus" aria-hidden="true"></i>
		<span>Listagem de Serviços adicionais</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=servicos_adicionais&acao=listarServicos_adicionais">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=servicos_adicionais&acao=formServicos_adicionais&met=cadastroServicos_adicionais">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Serviços adicionais</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=servicos_adicionais&acao=formServicos_adicionais&met=cadastroServicos_adicionais"><img src="images/novo.png" alt="Cadastro Serviços adicionais" title="Cadastrar Serviços adicionais" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=servicos_adicionais&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=servicos_adicionais&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=servicos_adicionais&p="+preventCache+"&";
			ordem = "idservicos_adicionais";
			dir = "asc";
			$(document).ready(function(){
				preTableServicos_adicionais();
			});
			dataTableServicos_adicionais('<?php print $buscar; ?>');
			columnServicos_adicionais();
		</script>




	</div>

<?php } ?>

