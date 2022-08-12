<?php 
	 // Versao do modulo: 2.20.130114

	include_once "blog_categoria_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";
?>
<link rel="stylesheet" type="text/css" href="blog_categoria_css.css" />
<script type="text/javascript" src="blog_categoria_js.js"></script>

<!--************************************
                                         _ _ _             
                                        | (_) |            
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __ 
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |   
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|			
								*******************************-->


<?php if($_REQUEST['acao'] == "formBlog_categoria"){
	if($_REQUEST['met'] == "cadastroBlog_categoria"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_categoria_script.php?opx=cadastroBlog_categoria";
		$metodo_titulo = "Cadastro de Blog categoria";
		$idBlog_categoria = 0 ;

		// dados para os campos
		$blog_categoria['nome'] = "";
		$blog_categoria['urlrewrite'] = "";
		$blog_categoria['status'] = "1";
		$blog_categoria['keywords'] = "";
		$blog_categoria['description'] = "";
		$blog_categoria['title'] = "";
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_categoria_script.php?opx=editBlog_categoria";
		$metodo_titulo = "Editar Blog categoria";
		$idBlog_categoria = (int) $_GET['idu'];

		$blog_categoria = buscaBlog_categoria(array('idblog_categoria'=>$idBlog_categoria));
		if (count($blog_categoria) != 1) exit;
		$blog_categoria = $blog_categoria[0];
	}
?>

	<div id="titulo">
		<!-- <img src="images/modulos/blog_categoria_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=blog_categoria&acao=listarBlog_categoria">Listagem</a></li>			
		</ul>
	</div> 


	<div id="principal">
		<form class="form" name="formBlog_categoria" method="post" action="<?php echo $action; ?>" onsubmit="return verificarCampos(new Array('nome', 'status')); " >

			<div id="informacaoBlog_categoria" class="content">
				<div class="content_tit">Dados Categoria:</div>
						<div class="box_ip">
							<label  for="nome">Nome</label>
						    <input type="text" name="nome" id="nome" size="30" maxlength="60" value="<?php echo $blog_categoria['nome']; ?>" class=''/>
						</div>
                        <div class="box_ip">
                            <label  for="urlrewrite">Url</label>
                            <input type="text" name="urlrewrite" id="urlrewrite" size="30" maxlength="60" value="<?php echo $blog_categoria['urlrewrite']; ?>" class=''/>
                        </div>
						<div class="box_sel focus" style="width: 36%">
                            <div class="box_sel_d">
								<label  for="status">Status</label>
								<select name="status" id="status" class=''> 
									<option value="1" <?php $blog_categoria['status'] == 1 ? print "selected='selected'": '' ;?> >Ativo</option>
									<option value="0" <?php $blog_categoria['status'] == 0 ? print "selected='selected'": '' ;?>>Inativo</option>
									
								</select>
							</div>
						</div>
<!-- 
						<div class="content_seo"> 
		                      <label class='label' style='margin-left:10px;'>SEO:</label>
		                      <div class="box_ip" style='width:100%'>
		                        <label for="title">Title</label>
		                        <input type="text" name="title" id="title" size="30" maxlength="255" value="<?php echo $blog_categoria['title']; ?>"  class=''/>
		                      </div> 
		                      <div class="box_ip" style='width:100%'>
		                        <label for="description">Description</label>
		                        <textarea name="description" id="description" size="30" maxlength="255"  class=''><?php echo $blog_categoria['description']; ?></textarea>
		                      </div>  
		                      <div class="box_ip" style='width:100%'>
		                        <label  for="keywords">Keywords</label>
		                        <textarea name="keywords" id="keywords" size="30" maxlength="400"><?php echo $blog_categoria['keywords']; ?></textarea>
		                      </div>  
	                    </div>  -->
			</div>


			<input type="hidden" name="idblog_categoria" value="<?php echo $idBlog_categoria; ?>" />
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


<?php if($_REQUEST['acao'] == "listarBlog_categoria"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));

	
?>
	<div id="titulo">
		<!-- <img src="images/modulos/blog_categoria_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span>Listagem de Categorias de Blog</span>
		<ul class="other_abs"> 
			<li class="other_abs_li"><a href="index.php?mod=blog_categoria&acao=formBlog_categoria&met=cadastroBlog_categoria">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv1">Nome</label><input type="text"  name="nome" id="nome"></div>
			<div class="box_sel focus" style="width: 48.6%">
				<div class="box_sel_d">
					<label for="status">Status</label>
					<select name="status" id="status"> 
						<option></option>
						<option value="1" >Ativo</option>
						<option value="0" >Inativo</option>						
					</select>
				</div>
			</div>
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div> 

	<div id="principal" >
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Categorias</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=blog_categoria&acao=formBlog_categoria&met=cadastroBlog_categoria"><img src="images/novo.png" alt="Cadastro Blog_categoria" title="Cadastrar Blog_categoria" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_categoria&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_categoria&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=blog_categoria&p="+preventCache+"&";
			ordem = "nome";
			dir = "asc";
			$(document).ready(function(){
				preTableBlog_categoria();
			});
			dataTableBlog_categoria('<?php print $buscar; ?>');
			columnBlog_categoria();
		</script>




	</div>

<?php } ?>

