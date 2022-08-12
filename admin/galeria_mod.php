<?php 
	 // Versao do modulo: 3.00.010416

	include_once "galeria_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="galeria_css.css" />
<script type="text/javascript" src="galeria_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formGaleria"){
	if($_REQUEST['met'] == "cadastroGaleria"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "galeria_script.php?opx=cadastroGaleria";
		$metodo_titulo = "Cadastro Galeria";
		$idGaleria = 0 ;

		// dados para os campos
		$galeria['nome'] = "";
		$galeria['status'] = "A"; 
		$galeria['imagem'] = "";

		$galeria_imagens = array();
		$width = 607;
		$height = 607;

	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "galeria_script.php?opx=editGaleria";
		$metodo_titulo = "Editar Galeria";
		$idGaleria = (int) $_GET['idu'];
		$galeria = buscaGaleria(array('idgaleria'=>$idGaleria));
		if (count($galeria) != 1) exit;
		$galeria = $galeria[0];

		$galeria_imagens = buscaGaleria_imagem(array("idgaleria"=>$galeria['idgaleria'],"ordem"=>'posicao_imagem',"dir"=>'ASC'));

			$width = 607;
			$height = 607;
		
	} 

?>
 
	 <div id="titulo">
		<!-- <img src="images/modulos/galeria_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-images" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=galeria&acao=listarGaleria">Listagem</a></li>			
		</ul>
	</div> 

	<div id="principal">
		<form class="form" name="formGaleria" id="formGaleria" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome', 'status')); " >

			<div id="informacaoGaleria" class="content">
				<div class="content_tit">Dados Galeria:</div>
					<div class="box_ip">
						<label  for="nome">Nome</label>
						<input type="text" name="nome" id="nome" size="30" maxlength="255" class='' value="<?php echo $galeria['nome']; ?>" />
					</div>

					<div class="box_ip">
	                    <label  for="status">Status</label> 
	                    <div class="box_sel" style='width:90%;'>
	                      <label for="">Status</label>
	                      <div class="box_sel_d">
	                         	<select name="status" id="status" class=''>
	                          		<option></option>
	                              	<option value="A" <?= ($galeria['status'] == "A" ? ' selected="selected" ' : ''); ?> > Ativo </option>
	                              	<option value="I" <?= ($galeria['status'] == "I" ? ' selected="selected" ' : ''); ?> > Inativo </option>
	                          	</select>
	                      </div>
	                   </div>
	                </div>  
					 

                    <!--################################## GALERIA #################################################
                    ###############################################################################################-->
 				
              
 					<div class="box_ip" style="width:100%;" id="galeria_imagem">
						<div class="content_tit" style="margin-left:0; padding-left:5px;">Fotos Galeria</div>
					    <!--input FILE -->
						<input style="width:50%; margin-left:5px;" id="image" name="image"  type="file" multiple />
						<div class='tamanhoImagem'>
	                        <p class="pre">Tamanho mínimo recomendado: <?= $width ?> x <?= $height ?>px (ou maior proporcional)  -  Extensão recomendada: jpg, png</p>
	                        <span class='maoir'><strong>O arquivo não pode ser maior que:</strong> 
                                <?php  
                                    $tamanho = explode('M', ini_get('upload_max_filesize'));
                                    $tamanho = $tamanho[0];
                                    echo $tamanho.'MB'; 
                                ?>	
                                <input type="hidden" id="fileMax" value="<?= $tamanho ?>" />
	                       </span>
	                    </div> 

	                    <!-- listagem das imagens -->
	                    <div class="box_ip content-image" id="content-image" style="width:100%; margin-left:5px;">

                    		<!-- INÍCIO DRAG N' DROP-->  
							<div class="box_ip content-image" id="content-image" >
							<div style="overflow:hidden"></div>
								<ul id="sortable">  
									<?php
										if(!empty($galeria_imagens)){
											//LEMBRE-SE QUE A BUSCA DA TABELA galeria_imagem ORDENA PELO CAMPO posicao_imagem
											//DESTE MODO ESSE FOREACH JÁ ALOCARÁ CADA IMAGEM EM SUA RESPECTIVA POSIÇÃO								
											$posicao = 1;
											foreach($galeria_imagens as $imagem){
												$caminho = 'files/galeria/thumb_'.$imagem['nome_imagem'];
												echo '<li class="ui-state-default'.$posicao.' move box-img" id="'.$posicao.'" idimagem="'.$imagem['idgaleria_imagem'].'">';
												echo '<img src="'.$caminho.'" id="img'.$imagem['posicao_imagem'].'" class="imagem-gallery" style="opacity:1;" />';
										  		echo '<a href="#" class="editImagemDescricao" idImagem="'.$imagem['idgaleria_imagem'].'">';
												echo '<button class="edit"></button>';	
												echo '</a>';
												echo '<a href="#" class="excluirImagem" idImagemDelete="'.$imagem['idgaleria_imagem'].'">';
												echo '<button class="delete"></button>';	
												echo '</a>';
												echo '<input type="hidden" name="idgaleria_imagem[]" value="'.$imagem['idgaleria_imagem'].'">';
												echo '<input type="hidden" name="descricao_imagem[]" value="'.$imagem['descricao_imagem'].'">';
												echo '<input type="hidden" name="imagem_galeria[]" value="'.$imagem['nome_imagem'].'">';
												echo '<input type="hidden" name="posicao_imagem[]" value="'.$imagem['posicao_imagem'].'">';
												echo '</li>'; 
												$posicao++;	 
											}								
										} 
									?> 
								</ul>
							</div> 
	                    </div>  
				    </div> 

                    <!--################################## FIM GALERIA #################################################
                    ###############################################################################################-->    
                     
			</div> 

			<input type="hidden" name="idgaleria" id="idgaleria" value="<?php echo $idGaleria; ?>" tipogaleria="<?= (($idGaleria == 1)? "quem_somos":"estrutura")?>"/>
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="hidden" id="mod" name="mod" value="<?= ($idGaleria == 0)? "cadastro":"editar"; ?>" />  
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


<?php if($_REQUEST['acao'] == "listarGaleria"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>
	<div id="titulo">
		<!-- <img src="images/modulos/galeria_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-images" aria-hidden="true"></i>
		<span>Listagem de Galeria</span>
		 
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
			  <div class="box_ip">
                    <label  for="status">Status</label> 
                    <div class="box_sel">
                      <label for="">Status</label>
                      <div class="box_sel_d">
                         <select name="status" id="status">
                          <option></option>
                              <option value="A" > Ativo </option>
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
				<li class="abas_list_li action"><a href="javascript:void(0)">Galeria</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=galeria&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=galeria&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=galeria&p="+preventCache+"&";
			ordem = "idgaleria";
			dir = "desc";
			$(document).ready(function(){
				preTableGaleria();
			});
			dataTableGaleria('<?php print $buscar; ?>');
			columnGaleria();
		</script> 


	</div>

<?php } ?>


<!--/////////////////////////////////////////////////////////-->
<!--////////////// FORMULARIOS PARA A GALERIA ////////////////-->
<!--////////////////////////////////////////////////////////-->

<!--Inicio dialog descrição-->
<div id="boxDescricao" style="display:none;">													
	<div id="principal">
		<form class="form" name="formDescricaoImagem" id="formDescricaoImagem" method="post" action="">
			<div id="informacaoGaleria" class="content">								
				<div class="content_tit"></div>		
			   	<div class="box_ip" >
					<label  for="descricao_imagem">Descrição</label>
					<input type="text" name="descricao_imagem" id="descricao_imagem"   />
					<input type="hidden" id="idImagem" value="" /> 
					<input type="hidden" id="posImagem" value="" />
				</div>
				<input type="submit" value="Salvar" class="btSaveDescricao button" />
			</div>
		</form>
	</div>
</div>	
<!--Fim dialog descrição-->	

<!--Inicio dialog exclusão de imagem-->
<div id="excluirImagem" style="display:none;">													
	<div id="principal">
		<form class="form" name="formDeleteImagem" id="formDeleteImagem" method="post" action="">
			<div id="informacaoGaleria" class="content">								
				<div class="content_tit"></div>	 
				<input type="hidden" id="idPosicao" value="" />  
				<input type="button" value="NÃO" id="cancelar" class="btCancelarExclusao button cancel" />							   	
				<input type="submit" value="SIM" class="btExcluirImagem button"/>
			</div>
		</form>
	</div>
</div>	
<!--Fim dialog exclusão de imagem-->