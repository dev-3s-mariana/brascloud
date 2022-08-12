<?php 
	 // Versao do modulo: 3.00.010416

	include_once "idiomas_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = ""; 
	 
?>
<link rel="stylesheet" type="text/css" href="idiomas_css.css" />
<script type="text/javascript" src="idiomas_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formIdiomas"){
	if($_REQUEST['met'] == "cadastroIdiomas"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "idiomas_script.php?opx=cadastroIdiomas";
		$metodo_titulo = "Cadastro Idiomas";
		$idIdiomas = 0 ;

		// dados para os campos
		$idiomas['idioma'] = "";
		$idiomas['bandeira'] = "";
		$idiomas['urlamigavel'] = ""; 
		$idiomas['status'] = 1;
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "idiomas_script.php?opx=editIdiomas";
		$metodo_titulo = "Editar Idiomas";
		$idIdiomas = (int) $_GET['idu'];
		$idiomas = buscaIdiomas(array('ididiomas'=>$idIdiomas));
		if (count($idiomas) != 1) exit;
		$idiomas = $idiomas[0];
	}
?>

	<div id="titulo">
		<i class="fas fa-globe"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=idiomas&acao=listarIdiomas">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=idiomas&acao=formIdiomas&met=cadastroIdiomas">Cadastro</a></li>
		</ul>
	</div> 

	<div id="principal">
		<form class="form" name="formIdiomas" id="formIdiomas" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('idioma'));">

			<div id="informacaoIdiomas" class="content">
				<div class="content_tit">Dados Idiomas:</div>
					<div class="box_ip">
						<label for="idioma">Idioma</label>
						<input type="text" name="idioma" id="idioma" size="30" maxlength="255" value="<?php echo $idiomas['idioma']; ?>" class='required'/>
					</div> 
					<div class="box_ip">
						<label for="urlamigavel">Urlamigável</label>
						<input type="text" name="urlamigavel" id="urlamigavel" size="30" maxlength="255" value="<?php echo $idiomas['urlamigavel']; ?>"  class='required'/>
						<input type="hidden" name="urlamigavelantigo" id="urlamigavelantigo" value="<?= $idiomas['urlamigavel']; ?>" />
					</div>

					<div class="box_ip">
	                    <label  for="status">Status</label> 
	                    <div class="box_sel" style='width:90%;margin-left:0px;'>
	                      <label for="">Status</label>
	                      <div class="box_sel_d">
	                         	<select name="status" id="status" class='required'>
	                          		<option></option>
	                              	<option value="1" <?= ($idiomas['status'] == 1 ? ' selected="selected" ' : ''); ?> > Ativo </option>
	                              	<option value="0" <?= ($idiomas['status'] == 0 ? ' selected="selected" ' : ''); ?> > Inativo </option>
	                          	</select>
	                      </div>
	                   </div>
	                </div>

	                <div style='clear:both;'></div>

					<!--################################## ICONE NEGATIVO #################################################
                    ###############################################################################################-->
                    <div class="box_ip bandeira " style="width:50%">
                        <div class="content_tit" style="margin-left:0px;">Bandeira</div>                              
                        <input type="file" id="iconebandeira" class="foto" name="iconebandeira" tipo="bandeira" value="<?php echo $idiomas['bandeira'] ?>"/>
                        <p class="pre">Tamanho mínimo recomendado: 24 x 24px (ou maior proporcional)  -  Extensão recomendada: *.png</p>
                        <span class='maoir'><strong>O arquivo não pode ser maior que:</strong> 
                            <?php 
                                //echo ini_get('upload_max_filesize').'B<br>';
                                $tamanho = explode('M', ini_get('upload_max_filesize'));
                                $tamanho = $tamanho[0];
                                echo $tamanho.'MB';
                            ?>  
                       </span>
                        <?php
                            $caminho = '';
                            if($idiomas['bandeira'] !='' ){
                                $caminho = 'files/idiomas/'.$idiomas['bandeira'];
                            }
                        ?> 
                        <div class='icone'>     
                            <img src="<?php echo $caminho;?>" class="bandeira" <?= (empty($idiomas['bandeira'])) ? 'style="display:none;"' : '' ;?> />
                        </div> 
                    </div>   
                    <!--################################## FIM IMAGEM GRANDE #################################################
                    ###############################################################################################-->
	                 
			</div>

			<input type="hidden" name="bandeira" id='bandeira' value="<?= $idiomas['bandeira']; ?>" class='required'/>
			<input type="hidden" name="ididiomas" id="ididiomas" value="<?php echo $idIdiomas; ?>" />
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


<?php if($_REQUEST['acao'] == "listarIdiomas"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<i class="fas fa-globe"></i>
		<span>Listagem de Idiomas</span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=idiomas&acao=listarIdiomas">Listagem</a></li>
			<li class="others_abs_br"></li>
			<li class="other_abs_li"><a href="index.php?mod=idiomas&acao=formIdiomas&met=cadastroIdiomas">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_idioma">Idioma</label><input type="text" name="idioma" id="adv_idioma"></div>
			<div class="box_ip"><label for="adv_urlamigavel">Url Amigável</label><input type="text" name="urlamigavel" id="adv_urlamigavel"></div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div> 

	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)" class='listaFlag' aba_status="">Todos</a></li>
				<li class="abas_list_li"><a href="javascript:void(0)" class='listaFlag' aba_status="1">Ativos</a></li>
				<li class="abas_list_li"><a href="javascript:void(0)" class='listaFlag' aba_status="0">Inativos</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=idiomas&acao=formIdiomas&met=cadastroIdiomas"><img src="images/novo.png" alt="Cadastro Idiomas" title="Cadastrar Idiomas" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=idiomas&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=idiomas&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
			</ul>
		</div>
		<table class="table" cellspacing="0" cellpadding="0" id="listagem">
			<thead> </thead>
			<tbody> </tbody>
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
			requestInicio = "tipoMod=idiomas&p="+preventCache+"&";
			ordem = "ididiomas";
			dir = "asc";
			$(document).ready(function(){
				preTableIdiomas();
			});
			dataTableIdiomas('<?php print $buscar; ?>');
			columnIdiomas();
		</script>  

	</div>

<?php } ?>

