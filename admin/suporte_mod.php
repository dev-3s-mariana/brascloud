<?php 
	 // Versao do modulo: 3.00.010416

	include_once "suporte_class.php";
    include_once "categoria_suporte_class.php";

    $categoria_suporte = buscaCategoria_suporte(array());
	
	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="suporte_css.css" />
<script type="text/javascript" src="suporte_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formSuporte"){
	if($_REQUEST['met'] == "cadastroSuporte"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "suporte_script.php?opx=cadastroSuporte";
		$metodo_titulo = "Cadastro Suporte";
		$idSuporte = 0 ;

		// dados para os campos
		$suporte['nome'] = "";
		$suporte['descricao'] = "";
        $suporte['idcategoria_suporte'] = "";
        $suporte['titulo'] = "";
        $suporte['texto'] = "";
        $suporte['titulo_faq'] = "";
        $suporte['texto_faq'] = "";
        $suporte['titulo_caixa'] = "";
        $suporte['texto_caixa'] = "";

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "suporte_script.php?opx=editSuporte";
		$metodo_titulo = "Editar Suporte";
		$idSuporte = (int) $_GET['idu'];
		$suporte = buscaSuporte(array('idsuporte'=>$idSuporte));
		if (count($suporte) != 1) exit;
		$suporte = $suporte[0];	
	}
?>

	<div id="titulo">
		<i class="fas fa-list" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=suporte&acao=listarSuporte">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=suporte&acao=formSuporte&met=cadastroSuporte">Cadastro</a></li>
		</ul>
	</div>
	<div id="principal">
		<form class="form" name="formSuporte" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome'));">
			<div id="informacaoSuporte" class="content">
				<div class="content_tit">Dados Suporte:</div>
                <div class="box_ip separar box_txt">
                    <label for="titulo">Título</label>
                    <input name="titulo" id="titulo_" class='' value="<?php echo $suporte['titulo']; ?>"/>
                </div>

				<!-- <div class="box_ip separar box_txt">
					<label for="nome">Nome</label>
					<input name="nome" id="nome" class='' value="<?php echo $suporte['nome']; ?>"/>
				</div> -->

				<div class="box_ip separar box_txt">
					<span style="font-family: 'RobotoM'">Descrição</span>
					<textarea name="descricao" id="descricao" class='mceAdvanced'><?php echo $suporte['descricao']; ?></textarea>
				</div>


                <!-- <div class="box_ip separar box_txt">
                    <label for="texto">Texto</label>
                    <textarea name="texto" id="texto" class=''><?php echo $suporte['texto']; ?></textarea>
                </div> -->

                <div class="box_ip separar box_txt">
                    <label for="titulo_faq">Título Faq</label>
                    <input name="titulo_faq" id="titulo_faq" class='' value="<?php echo $suporte['titulo_faq']; ?>"/>
                </div>

                <div class="box_ip separar box_txt">
                    <label for="texto_faq">Texto Faq</label>
                    <textarea name="texto_faq" id="texto_faq" class=''><?php echo $suporte['texto_faq']; ?></textarea>
                </div>

                <div class="box_ip separar box_txt">
                    <label for="titulo_caixa">Título Caixa</label>
                    <input name="titulo_caixa" id="titulo_caixa" class='' value="<?php echo $suporte['titulo_caixa']; ?>"/>
                </div>

                <div class="box_ip separar box_txt">
                    <label for="texto_caixa">Texto Caixa</label>
                    <textarea name="texto_caixa" id="texto_caixa" class=''><?php echo $suporte['texto_caixa']; ?></textarea>
                </div>

                <div class="box_ip">
                    <div class="box_sel box_txt">
                        <label for="idcategoria_suporte">Categoria</label>
                        <div class="box_sel_d">
                            <select name="idcategoria_suporte" id="idcategoria_suporte" class="">
                                <?php foreach($categoria_suporte as $key => $c):?>
                                    <option value="<?=$c['idcategoria_suporte']?>" <?php print ($suporte['idcategoria_suporte'] == $c['idcategoria_suporte'] ? ' selected="selected" ' : ''); ?> > <?=$c['titulo']?> </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                   </div>
                </div>
			</div>
			<input type="hidden" name="idsuporte" value="<?php echo $idSuporte; ?>" />
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


<?php if($_REQUEST['acao'] == "listarSuporte"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
	<i class="fas fa-list" aria-hidden="true"></i>
		<span>Listagem de Suporte</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=suporte&acao=listarSuporte">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=suporte&acao=formSuporte&met=cadastroSuporte">Cadastro</a></li>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Suporte</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=suporte&acao=formSuporte&met=cadastroSuporte"><img src="images/novo.png" alt="Cadastro Suporte" title="Cadastrar Suporte" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=suporte&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=suporte&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=suporte&p="+preventCache+"&";
			ordem = "idsuporte";
			dir = "asc";
			$(document).ready(function(){
				preTableSuporte();
			});
			dataTableSuporte('<?php print $buscar; ?>');
			columnSuporte();
		</script>




	</div>

<?php } ?>

