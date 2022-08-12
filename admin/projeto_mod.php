<?php 
	 // Versao do modulo: 3.00.010416

	include_once "projeto_class.php";
    include_once "cliente_class.php";

    $cliente = buscaCliente(array('status'=>'A'));

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";

    $width = 774;
    $height = 492;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';
?>
<link rel="stylesheet" type="text/css" href="projeto_css.css" />
<script type="text/javascript" src="projeto_js.js"></script>

<!-- CROPPER-->
<link href="css/cropper-padrao.css" rel="stylesheet">
<link href="css/main.css" rel="stylesheet">

<script src="js/bootstrap.min.js"></script>
<script src="js/cropper.js"></script>
<script src="js/main.js"></script>
<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formProjeto"){
	if($_REQUEST['met'] == "cadastroProjeto"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "projeto_script.php?opx=cadastroProjeto";
		$metodo_titulo = "Cadastro Projeto";
		$idProjeto = 0 ;

		// dados para os campos
		$projeto['titulo'] = "";
		$projeto['imagem'] = "";
        $projeto['idcliente'] = "";
		$projeto['status'] = "";
        $projeto['texto'] = "";
        $projeto['descricao'] = "";
        $projeto['titulo_caixa'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "projeto_script.php?opx=editProjeto";
		$metodo_titulo = "Editar Projeto";
		$idProjeto = (int) $_GET['idu'];
		$projeto = buscaProjeto(array('idprojeto'=>$idProjeto));
		if (count($projeto) != 1) exit;
		$projeto = $projeto[0];
	}
?>

	<div id="titulo">
		<!-- <img src="images/modulos/projeto_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-list fa-2x"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=projeto&acao=listarProjeto">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=projeto&acao=formProjeto&met=cadastroProjeto">Cadastro</a></li> 
		</ul>
	</div>

	<div id="principal">
		<form class="form" name="formProjeto" method="post" enctype="multipart/form-data" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('titulo_aba')); " >

			<div id="informacaoProjeto" class="content">
				<div class="content_tit">Dados Projeto:</div>
                    <div class="box_ip box_txt">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo_aba" class='' size="30" maxlength="50" value="<?php echo $projeto['titulo']; ?>" />
                    </div>

                    <div class="box_ip box_txt">
                        <label for="descricao">Descrição</label>
                        <textarea type="text" name="descricao" id="descricao_aba" class='' size="30"><?php echo $projeto['descricao']; ?></textarea>
                    </div>

                    <div class="box_ip box_txt">
                        <label for="titulo_caixa">Título Caixa</label>
                        <input type="text" name="titulo_caixa" id="titulo_caixa_aba" class='' size="30" maxlength="50" value="<?php echo $projeto['titulo_caixa']; ?>" />
                    </div>

                    <div class="box_ip box_txt">
                        <label for="texto">Texto Caixa</label>
                        <textarea type="text" name="texto" id="texto_aba" class='' size="30"><?php echo $projeto['texto']; ?></textarea>
                    </div>

					<div class="box_ip">
	                    <div class="box_sel box_txt">
	                      <label for="idcliente">Cliente</label>
	                      <div class="box_sel_d">
                         	<select name="idcliente" id="idcliente" class=''>
                                <?php foreach($cliente as $key => $s):?>
                              	    <option value="<?=$s['idcliente']?>" <?=$projeto['idcliente'] == $s['idcliente'] ? ' selected="selected" ' : '';?>> <?=$s['titulo']?> </option>
                                <?php endforeach;?>
                          	</select>
	                      </div>
	                   </div>
	                </div>

                    <div class="box_ip">
                        <div class="box_sel box_txt">
                          <label for="status">Status</label>
                          <div class="box_sel_d">
                            <select name="status" id="status" class=''>
                                <option value="A" <?=$projeto['status'] == "A" ? ' selected="selected" ' : '';?>> Ativo </option>
                                <option value="I" <?=$projeto['status'] == "I" ? ' selected="selected" ' : '';?>> Inativo </option>
                            </select>
                          </div>
                       </div>
                    </div>

                    <!-- CROPPER IMG -->
                    <?php $caminho = 'files/projeto/'; ?>
                    <div class="box_ip box_txt pd-left-important">
                        <div class="box_ip box_txt">
                            <div class="img_pricipal">
                                <div>
                                    <div class="content_tit">Imagem</div>
                                    <?php if ($projeto['imagem'] != '') { ?>
                                        <div class="box_ip imagem-atual">
                                            <a data-tipo="imagem" data-img="<?=$projeto['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                            <img src="<?=$caminho.$projeto['imagem'];?>" class="img-projeto-form" alt=""/>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="box-img-crop">
                                <input type="hidden" value="" name="coordenadas" id="coordenadas" />
                                <div class="docs-buttons">
                                    <div class="btn-group box_txt">
                                        <!--input FILE -->
                                        <input id="" class="cropped-image" name="imagemCadastrar" type="file"/>
                                        <br />
                                        <p class="pre">Tamanho recomendado: <?=$width?>x<?=$height?>px (ou maior proporcional) - Extensão recomendada: png, jpg</p>
                                        <span>O arquivo não pode ser maior que: <?=$tamanho?>
                                        </span>
                                        <input type="hidden" name="maxFileSize" id="maxFileSize" value="<?php echo $tamanho; ?>" />
                                    </div>
                                </div><!-- /.docs-buttons -->
                                <div class="img-container" id="img-container" style="display:none;">
                                    <img alt="">
                                </div>
                                <!-- Show the cropped image in modal -->
                                <div class="modal fade docs-cropped" id="getCroppedCanvasModal" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button class="close" data-dismiss="modal" type="button" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="getCroppedCanvasTitle">Cropped</h4>
                                            </div>
                                            <div class="modal-body"></div>
                                        </div>
                                    </div>
                                </div><!-- /.modal -->
                            </div>
                        </div>
                    </div>
    			</div>

			<input type="hidden" name="idprojeto" value="<?php echo $idProjeto; ?>" />
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
            <input type="hidden" name="imagem" value="<?php echo $projeto['imagem']; ?>" />
            <input type='hidden' name='aspectRatioW' id='aspectRatioW' value='<?=$width?>'>
            <input type='hidden' name='aspectRatioH' id='aspectRatioH' value='<?=$height?>'>
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


<?php if($_REQUEST['acao'] == "listarProjeto"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<!-- <img src="images/modulos/projeto_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-list fa-2x"></i>
		<span>Listagem de Projeto</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=projeto&acao=listarProjeto">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=projeto&acao=formProjeto&met=cadastroProjeto">Cadastro</a></li>
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
         <div class="box_ip">
             <div class="box_sel box_txt">
                 <label for="adv_status">Status</label>
                 <div class="box_sel_d">
                     <select name="status" id="adv_status">
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Projeto</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=projeto&acao=formProjeto&met=cadastroProjeto"><img src="images/novo.png" alt="Cadastro Projeto" title="Cadastrar Projeto" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=projeto&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=projeto&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=projeto&p="+preventCache+"&";
			ordem = "idprojeto";
			dir = "desc";
			$(document).ready(function(){
				preTableProjeto();
			});
			dataTableProjeto('<?php print $buscar; ?>');
			columnProjeto();
		</script>




	</div>

<?php } ?>

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>