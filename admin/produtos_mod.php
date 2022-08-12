<?php 
	 // Versao do modulo: 3.00.010416

	include_once "produtos_class.php";
    include_once "categoria_class.php";

    $categoria = buscaCategoria(array());
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="produtos_css.css" />
<script type="text/javascript" src="produtos_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formProdutos"){
	if($_REQUEST['met'] == "cadastroProdutos"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "produtos_script.php?opx=cadastroProdutos";
		$metodo_titulo = "Cadastro Produtos";
		$idProdutos = 0 ;

		// dados para os campos
		$produtos['nome'] = "";
		$produtos['descricao'] = "";
        $produtos['idcategoria'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "produtos_script.php?opx=editProdutos";
		$metodo_titulo = "Editar Produtos";
		$idProdutos = (int) $_GET['idu'];
		$produtos = buscaProdutos(array('idprodutos'=>$idProdutos));
		if (count($produtos) != 1) exit;
		$produtos = $produtos[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-list" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=produtos&acao=listarProdutos">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=produtos&acao=formProdutos&met=cadastroProdutos">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formProdutos" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoProdutos" class="content">
				<div class="content_tit">Dados Produtos:</div>
				<div class="box_ip separar" style='width:100%'>
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $produtos['nome']; ?>"/>
				</div>

				<div class="box_ip separar" style='width:100%'>
					<label for="descricao">Descrição</label>
					<textarea name="descricao" id="descricao" class=''><?php echo $produtos['descricao']; ?></textarea>
				</div>

                <div class="box_ip">
                    <div class="box_sel box_txt">
                        <label for="idcategoria">Categoria</label>
                        <div class="box_sel_d">
                            <select name="idcategoria" id="idcategoria" class="">
                                <?php foreach($categoria as $key => $c):?>
                                    <option value="<?=$c['idcategoria']?>" <?php print ($produtos['idcategoria'] == $c['idcategoria'] ? ' selected="selected" ' : ''); ?> > <?=$c['nome']?> </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                   </div>
                </div>
			</div>
			<input type="hidden" name="idprodutos" value="<?php echo $idProdutos; ?>" />
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


<?php if($_REQUEST['acao'] == "listarProdutos"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-list" aria-hidden="true"></i>
		<span>Listagem de Produtos</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=produtos&acao=listarProdutos">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=produtos&acao=formProdutos&met=cadastroProdutos">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Produtos</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=produtos&acao=formProdutos&met=cadastroProdutos"><img src="images/novo.png" alt="Cadastro Produtos" title="Cadastrar Produtos" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=produtos&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=produtos&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=produtos&p="+preventCache+"&";
			ordem = "idprodutos";
			dir = "asc";
			$(document).ready(function(){
				preTableProdutos();
			});
			dataTableProdutos('<?php print $buscar; ?>');
			columnProdutos();
		</script>




	</div>

<?php } ?>

