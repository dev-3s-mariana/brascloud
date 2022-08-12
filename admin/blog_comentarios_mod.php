<?php 
	 // Versao do modulo: 2.20.130114

	include_once "blog_comentarios_class.php";
	include_once "blog_post_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
  
	$idg = "";
	$idgblog = "";
	$nomeBlog = "";
	if(isset($_REQUEST['idg']) && !empty($_REQUEST['idg'])){
		$idg = $_REQUEST['idg'];
		$idgblog = "&idg=".$_REQUEST['idg'];
		$nomeBlog = buscaBlog_post(array("idblog_post"=>$idg));
		$nomeBlog = ": ".$nomeBlog[0]['nome'];
	}

?>
<link rel="stylesheet" type="text/css" href="blog_comentarios_css.css" />
<script type="text/javascript" src="blog_comentarios_js.js"></script>

<!--************************************
                                         _ _ _             
                                        | (_) |            
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __ 
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |   
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|			
								*******************************-->


<?php if($_REQUEST['acao'] == "formBlog_comentarios"){
	if($_REQUEST['met'] == "cadastroBlog_comentarios"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:../admin/?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_comentarios_script.php?opx=cadastroBlog_comentarios";
		$metodo_titulo = "Cadastro Comentários";
		$idBlog_comentarios = 0 ;

		// dados para os campos
		$blog_comentarios['nome'] = "";
		$blog_comentarios['email'] = "";
		$blog_comentarios['comentario'] = ""; 
		$blog_comentarios['idblog_post'] = 0;
		$blog_comentarios['status'] = "";
      $blog_comentarios['imagem'] = "";
		$blog_comentarios['data'] = date('d/m/Y');
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:../admin/?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_comentarios_script.php?opx=editBlog_comentarios";
		$metodo_titulo = "Editar Comentários";
		$idBlog_comentarios = (int) $_GET['idu'];
		$blog_comentarios = buscaBlog_comentarios(array('idblog_comentarios'=>$idBlog_comentarios));
		if (count($blog_comentarios) != 1) exit;
		$blog_comentarios = $blog_comentarios[0];
	}
?>

	<div id="titulo">
		<!-- <img src="images/modulos/blog_comentarios_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<span><?= substr($nomeBlog, 1, 50) ?> <?= (strlen($nomeBlog) > 50)?"...":"" ?></span> 
		<ul class="other_abs">
			<li class="other_abs_li"><a href="../admin/?mod=blog_comentarios&acao=listarBlog_comentarios<?= $idgblog ?>">Listagem</a></li>
			<li class="others_abs_br"></li>
		</ul>
	</div>




	<div id="principal">
		<form class="form" name="formBlog_comentarios" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome', 'email', 'comentario', 'status', 'data')); " >

			<div id="informacaoBlog_comentarios" class="content">
				<div class="content_tit">Comentários</div>
					<div class="box_ip">
						<label  for="nome">Nome</label>
						<input type="text" name="nome" id="nome" size="30" maxlength="60" value="<?php echo $blog_comentarios['nome']; ?>" class=''/>
					</div>
					<div class="box_ip">
						<label  for="email">Email</label>
						<input type="text" name="email" id="email" size="30" maxlength="120" value="<?php echo $blog_comentarios['email']; ?>"  class=''/>
					</div>
					<div class="box_ip box_txt">
						<label  for="comentario">Comentário</label>
						<textarea name="comentario" id="comentario" cols="34" rows="4" class=''><?php echo $blog_comentarios['comentario']; ?></textarea>
					</div>
					
					<div class="box_sel focus" style="width: 48.6%">
                        <div class="box_sel_d">
							<label  for="status">Status</label>
							<select name="status" id="status" class=''> 
								<option value="1" <?php $blog_comentarios['status'] == 1 ? print "selected='selected'": '' ;?>>Pendente</option>
								<option value="2" <?php $blog_comentarios['status'] == 2 ? print "selected='selected'": '' ;?>>Aprovado</option>
								<option value="3" <?php $blog_comentarios['status'] == 3 ? print "selected='selected'": '' ;?>>Reprovado</option> 
							</select>
						</div>
					</div>
					<? if($idBlog_comentarios > 0){ ?>
							<div class="box_ip">
								<label  for="data">Data</label>
								<input type="text" name="data" id="data"  class="wDate" size="30" value="<?php echo $blog_comentarios['data_formatado']; ?>" />
							</div> 
					<? } ?>

               <div class="box_ip">
                  <div class="content_tit">Imagem:</div>
                  <?php if(!empty($blog_comentarios['imagem'])):?>
                     <img src="files/blog/comentarios/<?=$blog_comentarios['imagem']?>">
                  <?php else:?>
                     <img src="https://via.placeholder.com/84x84?text=Sem+Imagem">
                  <?php endif;?>
               </div> 
			</div>

			 <? if(!empty($idg)){ ?>
					<input type='hidden' name='idg' id="idg" value="<?= $idg ?>">
			<? } ?>
			<input type="hidden" name="idblog_post" id="idblog_post"  size="30" value="<?= ((empty($idg))?$blog_comentarios['idblog_post']:$idg)?>" />
			<input type="hidden" name="idblog_comentarios" value="<?php echo $idBlog_comentarios; ?>" />
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


<?php if($_REQUEST['acao'] == "listarBlog_comentarios"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:../admin/?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
	 

	 if(!empty($idg)){ 
			print "<input type='hidden' name='idg' id='idg' value='".$idg."'>";
	 } 	 

?>	 

	<div id="titulo">
		<!-- <img src="images/modulos/blog_comentarios_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span>Listagem de Comentários</span> 
		<span><?= substr($nomeBlog, 1, 50) ?> <?= (strlen($nomeBlog) > 50)?"...":"" ?></span> 
		<? if(!empty($idg)){ ?>	
			<ul class="other_abs"> 
				<li class="other_abs_li"><a href="../admin/?mod=blog_comentarios&acao=formBlog_comentarios&met=cadastroBlog_comentarios<?= $idgblog ?>">Cadastro</a></li>
			</ul> 
		<? } ?>
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
			<div class="box_ip"><label for="adv1">Nome</label><input type="text"  name="nome" id="nome"></div>
			<div class="box_ip"><label for="adv3">Comentário</label><input type="text"  name="comentario" id="comentario"></div>
      		<div class="box_ip"><label for="adv6">Data de</label><input type="text"  name="dataDe"  class="wDate comparar" id="dataDe"></div>
			<div class="box_ip"><label for="adv6">Data Até</label><input type="text"  name="dataAte"  class="wDate comparar" id="dataAte"></div>
		 	<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div>




	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">  
				<li class="abas_list_li action"><a href="javascript:void(0)" class="listaFlag" codigo="0">Todos</a></li>
				<li class="abas_list_li"><a href="javascript:void(0)" class="listaFlag" codigo="1">Pendentes</a></li>
				<li class="abas_list_li"><a href="javascript:void(0)" class="listaFlag" codigo="2">Aprovados</a></li>
				<li class="abas_list_li"><a href="javascript:void(0)" class="listaFlag" codigo="3">Reprovados</a></li>
		  		
		  	</ul>
			<ul class="abas_bts">
			 	<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_comentarios&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_comentarios&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=blog_comentarios&p="+preventCache+"&"; 
			ordem = "idblog_comentarios";
			dir = "desc";
			$(document).ready(function(){
				preTableBlog_comentarios('<?php print $buscar; ?>');
			});
			dataTableBlog_comentarios('<?php print $buscar; ?>');
			columnBlog_comentarios();
		</script>




	</div>

<?php } ?>

