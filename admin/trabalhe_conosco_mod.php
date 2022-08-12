<?php 
	 // Versao do modulo: 3.00.010416

	include_once "trabalhe_conosco_class.php";
	include_once "area_pretendida_class.php";
	
	$areas = buscaArea_pretendida(array("status"=>"A","ordem"=>"idarea_pretendida", "dir"=>"asc"));

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";

?>
<link rel="stylesheet" type="text/css" href="trabalhe_conosco_css.css" />
<script type="text/javascript" src="trabalhe_conosco_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formTrabalhe_conosco"){
	if($_REQUEST['met'] == "cadastroTrabalhe_conosco"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		
		$action = "trabalhe_conosco_script.php?opx=cadastroTrabalhe_conosco";
		$metodo_titulo = "Cadastro Trabalhe Conosco";
		$idTrabalhe_conosco = 0 ;

		// dados para os campos
		$trabalhe_conosco['nome'] = "";
		$trabalhe_conosco['email'] = "";
		$trabalhe_conosco['telefone'] = "";
		$trabalhe_conosco['idarea_pretendida'] = "";
		$trabalhe_conosco['arquivo'] = ""; 
		$trabalhe_conosco['ip'] = "";
		$trabalhe_conosco['conheceu'] = "";
      $trabalhe_conosco['mensagem'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "trabalhe_conosco_script.php?opx=editTrabalhe_conosco";
		$metodo_titulo = "Editar Trabalhe Conosco";
		$idTrabalhe_conosco = (int) $_GET['idu'];
		$trabalhe_conosco = buscaTrabalhe_conosco(array('idtrabalhe_conosco'=>$idTrabalhe_conosco));
		if (count($trabalhe_conosco) != 1) exit;
		$trabalhe_conosco = $trabalhe_conosco[0];
		$areas = buscaArea_pretendida(array("status"=>"A", "ordem"=>"idarea_pretendida", "dir"=>"asc"));
	}
?>
	<div id="titulo">
		<i class="fas fa-file"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco">Listagem</a></li>			
		</ul>
	</div>  

	<div id="principal">
		<form class="form" name="formTrabalhe_conosco" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome', 'email', 'telefone', 'idarea_pretendida')); return false; " enctype="multipart/form-data">

			<div id="informacaoTrabalhe_conosco" class="content">
			<div class="content_tit">Dados Candidato:</div>
				<div class="box_ip">
					<label for="nome">Nome</label>
					<input type="text" name="nome" id="nome" size="30" maxlength="255" value="<?php echo $trabalhe_conosco['nome']; ?>" class='required'/>
				</div>
				<div class="box_ip">
					<label  for="email">Email</label>
					<input type="text" name="email" id="email" size="30" maxlength="255" value="<?php echo $trabalhe_conosco['email']; ?>"  class='required'/>
				</div>
				<div class="box_ip">
					<label  for="telefone">Telefone</label>
					<input type="text" name="telefone" id="telefone" size="30" maxlength="20" class='phone_br required' value="<?php echo $trabalhe_conosco['telefone']; ?>" />
				</div>
			 
				<div class="box_ip">
					<label  for="idarea_pretendida">Área Pretendida</label> 
					<div class="box_sel">
						<label for="">Área Pretendida</label>
						<div class="box_sel_d">
							<select name="idarea_pretendida" id="idarea_pretendida" class='required'>
							<option></option>
							<?php foreach($areas as $k => $v){ ?>	
								<option value="<?= $v['idarea_pretendida'] ?>" <?= (($v['idarea_pretendida'] == $trabalhe_conosco['idarea_pretendida'])? "SELECTED":""); ?>> <?= $v['nome'] ?></option>
							<?php } ?>
							</select>
						</div>
					</div>
				</div>

            <div class="box_ip" style="width: 100%;">
               <span>Mensagem</span>
               <textarea name="mensagem" id="mensagem" size="30" maxlength="255" value="<?php echo $trabalhe_conosco['mensagem']; ?>" class='required'><?php echo $trabalhe_conosco['mensagem']; ?></textarea>
            </div>
	            
				<div class='box_ip' style='width:100%;'>
					<label for="sexo"><b>Arquivo</b></label><br/>					
				</div>

				<div class="box_ip">
					<input type="file" name="arquivo" id="arquivo" value="<?php echo $trabalhe_conosco['arquivo']; ?>" />
				</div>

				<?php if(!empty($trabalhe_conosco['arquivo'])) 
					print "<a href='".$trabalhe_conosco['arquivo']."' target='_blank' style='float:left;width:100%;margin-left:20px;color:#000;font-weight:bold;'>Ver Currículo</a>";
				?>

			</div> 

			<input type="hidden" name="idtrabalhe_conosco" value="<?php echo $idTrabalhe_conosco; ?>" />
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


<?php if($_REQUEST['acao'] == "listarTrabalhe_conosco"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<i class="fas fa-file"></i>
		<span>Listagem de Trabalhe Conosco</span>
		<ul class="other_abs">
         <li class="other_abs_li"><a href="index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco">Listagem</a></li>
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
			<div class="box_ip"><label for="adv_nome">Nome</label><input type="text"  name="nome" id="adv_nome"></div>
			<div class="box_ip"><label for="adv_email">E-mail</label><input type="text"  name="email" id="adv_email"></div>
			<div class="box_ip" style='width:30%'>
                <label for="idarea_pretendida">Pretensão</label> 
                <div class="box_sel">
                    <label for="">Pretensão</label>
                    <div class="box_sel_d">
	                    <select name="idarea_pretendida" id="idarea_pretendida"> 
                      		<option></option>
                      		<? foreach($areas as $k => $v){ ?>
                                <option value="<?= $v['idarea_pretendida'] ?>"><?= $v['nome'] ?></option>
                      		<? } ?> 
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Currículos</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=trabalhe_conosco&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=trabalhe_conosco&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=trabalhe_conosco&p="+preventCache+"&";
			ordem = "idtrabalhe_conosco";
			dir = "desc";
			$(document).ready(function(){
				preTableTrabalhe_conosco();
			});
			dataTableTrabalhe_conosco('<?php print $buscar; ?>');
			columnTrabalhe_conosco();
		</script>
 
	</div>

<?php } ?>

