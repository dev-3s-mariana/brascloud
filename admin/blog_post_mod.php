<?php 
	 // Versao do modulo: 3.00.010416

	include_once "blog_post_class.php";
	include_once "blog_categoria_class.php";
    include_once "blog_tags_class.php";

    $blog_tags = buscaBlog_tags(array('status'=>1));

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";

	$categorias = buscaBlog_categoria(array('ordem'=>"nome asc"));

	$idg = "";
	$idgcategoria = "";
	$nomeCategoria = "";
	if(isset($_REQUEST['idg']) && !empty($_REQUEST['idg'])){
		$idg = $_REQUEST['idg'];
		$idgcategoria = "&idg=".$_REQUEST['idg'];
		$nomeCategoria = buscaBlog_categoria(array("idblog_categoria"=>$idg));
		$nomeCategoria = ": ".$nomeCategoria[0]['nome'];
	}

    $width = 968;
    $height = 445;

    $tamanho = explode('M', ini_get('upload_max_filesize'));
    $tamanho = $tamanho[0].'MB';

?>
<link rel="stylesheet" type="text/css" href="blog_post_css.css" />
<script type="text/javascript" src="blog_post_js.js"></script>

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


<?php if($_REQUEST['acao'] == "formBlog_post"){
	if($_REQUEST['met'] == "cadastroBlog_post"){
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_criar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_post_script.php?opx=cadastroBlog_post";
		$metodo_titulo = "Cadastro Post".$nomeCategoria;
		$idBlog_post = 0;

		// dados para os campos
		$blog_post['nome'] = ""; 
		$blog_post['idblog_categoria'] = ""; 
		$blog_post['resumo'] = "";
		$blog_post['descricao'] = "";
		$blog_post['status'] = "1";
		$blog_post['imagem'] = ""; 
		$blog_post['destaque'] = ""; 
		$blog_post['contador'] = 0;  
		$blog_post['data_hora_formatado'] = date('d/m/Y H:m'); 
		$blog_post['status'] = "A";
		$blog_post['urlrewrite'] = "";
		$blog_post['idblog_post'] = ""; 
		$blog_post['description'] = "";
		$blog_post['keywords'] = "";
		$blog_post['title'] = "";
		$blog_post['autor'] = ""; 
		$blog_post['link_video'] = ""; 
        $blog_post['tags'] = ''; 
		$blog_post_imagens = array();
	}else{
		if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_editar', $MODULOACESSO['usuario'])) {
			header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
			exit;
		}
		$action = "blog_post_script.php?opx=editBlog_post";
		$metodo_titulo = "Editar Post".$nomeCategoria;
		$idBlog_post = (int) $_GET['idu'];
		$blog_post = buscaBlog_post(array('idblog_post'=>$idBlog_post));
		if (count($blog_post) != 1) exit;
		$blog_post = $blog_post[0];
		$blog_post_imagens = buscaBlog_post_imagem(array("idblog_post"=>$blog_post['idblog_post'],"ordem"=>'posicao_imagem',"dir"=>'ASC'));
 
	}

	$pathLogo = null;
	if(file_exists("files/blog_post/{$idBlog_post}/thumb/{$blog_post['imagem']}")){
		$pathLogo = "files/blog_post/{$idBlog_post}/thumb/{$blog_post['imagem']}";
	}

	$upLoadMaxSize = explode('M', ini_get('upload_max_filesize'));
?>

	<div id="titulo">
		<!-- <img src="images/modulos/blog_post_preto.png" height="24" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=blog_post&acao=listarBlog_post">Listagem</a></li>			
		</ul>
	</div>

	<div id="principal">
		<form class="form" name="formBlog_post" id="formBlog_post" method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" onsubmit="return verificarCampos(new Array('nome'));">

			<div id="informacaoBlog_post" class="content">
				<div class="content_tit">Dados Blog Post:</div>
					<div class="box_ip box_txt">
						<label for="nome">Nome</label>
						<input type="text" name="nome" id="nome" size="30" maxlength="255" value="<?php echo $blog_post['nome']; ?>" class=''/>
					</div>

					<div class="box_ip box_txt">
                        <label for="urlrewrite">Urlrewrite</label>
                        <input type="text" name="urlrewrite" id="urlrewrite" size="30" maxlength="255" value="<?php echo $blog_post['urlrewrite']; ?>" class=''/>
                        <input type="hidden" name="urlrewriteantigo" id="urlrewriteantigo" value="<?= $blog_post['urlrewrite']; ?>" />
                    </div>

                    <div class="box_cr box_cr_area_pretendida">
                        <p>Tags</p>
                        <?php
                            $arrTags = explode(',', $blog_post['tags']);
                            $arrTags = empty($arrTags)?array():$arrTags;
                        ?>
                        <?php foreach($blog_tags as $key => $bt):?>
                            <label>
                                <input name="tags[]" value="<?=$bt['idblog_tags']?>" type="checkbox" <?=in_array($bt['idblog_tags'], $arrTags)?'checked':''?>>
                                <span><?=$bt['titulo']?></span>
                            </label>
                        <?php endforeach;?>
                    </div>

					<div class="box_ip">
	                    <label for="categoria">Categoria</label> 
	                    <div class="box_sel">
	                      	<label for="">Categoria</label>
	                      	<div class="box_sel_d">
		                        <select name="idblog_categoria" id="idblog_categoria" class=''>
	                          		<!-- <option value=""></option> -->
	                             	<?php
	                             		foreach($categorias as $k => $v){
	                             			print "<option value='".$v['idblog_categoria']."' ".(($v['idblog_categoria'] == $blog_post['idblog_categoria'] || $v['idblog_categoria'] == $idg )? "SELECTED":"").">".$v['nome']."</option>";
	                             		}
	                             	?> 
		                        </select>
	                      	</div>
	                   </div>
	                </div>
				 
					<div class="box_ip">
	                    <label for="status">Status</label> 
	                    <div class="box_sel">
	                      <label for="">Status</label>
	                      <div class="box_sel_d">
		                        <select name="status" id="status" class=''>
	                          		<!-- <option></option> -->
	                             	<option value="1" <?= ($blog_post['status'] == "1" ? ' selected="selected" ' : ''); ?> > Ativo </option>
	                                <option value="0" <?= ($blog_post['status'] == "0" ? ' selected="selected" ' : ''); ?> > Inativo </option>
		                        </select>
	                      </div>
	                   </div>
	                </div>	 
								 
					<div class="box_ip">
						<label for="data_hora">data</label>
						<input type="text" name="data_hora" id="data_hora" class="wDatetime " size="30" value="<?php echo $blog_post['data_hora_formatado']; ?>" />
					</div>

					<div class="box_ip">
						<label for="autor">Autor</label>
						<input type="text" name="autor" id="autor" size="30" value="<?php echo $blog_post['autor']; ?>" />
					</div> 

					<!-- <div class="box_ip">
						<label for="link_video">Link do Vídeo</label>
						<input type="text" name="link_video" id="link_video" size="30" value="<?php echo $blog_post['link_video']; ?>" />
					</div>  -->
 	
					<div class="box_ip box_txt">
						<label for="resumo">Resumo</label> 
						<textarea name="resumo" id="resumo" class=''><?= $blog_post['resumo']; ?></textarea>
					</div>

	                <div class="box_ip box_txt">
						<span class="ff-roboto-m">Descrição</span> 
						<textarea name="descricao" id="descricao" class='mceAdvanced '><?= $blog_post['descricao']; ?></textarea>
					</div>
	       	
                    <!-- CROPPER IMG -->
                    <?php $caminho = 'files/blog/'; ?>
                    <div class="box_ip box_txt pd-left-important">
                        <div class="box_ip box_txt">
                            <div class="img_pricipal">
                                <div>
                                    <div class="content_tit">Imagem</div>
                                    <?php if ($blog_post['imagem'] != '') { ?>
                                        <div class="box_ip imagem-atual">
                                            <a data-tipo="imagem" data-img="<?=$blog_post['imagem']?>" class="excluir-imagem"><img src="images/delete.png" alt="Excluir Imagem"></a>
                                            <img src="<?=$caminho.$blog_post['imagem'];?>" class="img-blog_post-form" width="100%" alt=""/>
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

 					<div class="box_ip" style="width:100%;" id="blog_post_imagem">
						<div class="content_tit" style="margin-left:0; padding-left:5px;">Galeria</div>
					    <!--input FILE -->
						<input style="width:50%; margin-left:5px;" id="image" name="image"  type="file" multiple />
						<div class='tamanhoImagem'>
	                        <p class="pre">Tamanho mínimo recomendado: 800x600px (ou maior proporcional)  -  Extensão recomendada: jpg, png</p>
	                        <span class='maoir'><strong>O arquivo não pode ser maior que:</strong> 
                                <?php 
                                    //echo ini_get('upload_max_filesize').'B<br>';
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
										if(!empty($blog_post_imagens)){
											//LEMBRE-SE QUE A BUSCA DA TABELA blog_post_imagem ORDENA PELO CAMPO posicao_imagem
											//DESTE MODO ESSE FOREACH JÁ ALOCARÁ CADA IMAGEM EM SUA RESPECTIVA POSIÇÃO								
											$posicao = 1;
											foreach($blog_post_imagens as $imagem){
												$caminho = 'files/blog/thumb_'.$imagem['nome_imagem'];
												echo '<li class="ui-state-default'.$posicao.' move box-img" id="'.$posicao.'" idimagem="'.$imagem['idblog_post_imagem'].'">';
												echo '<img src="'.$caminho.'" id="img'.$imagem['posicao_imagem'].'" class="imagem-gallery" style="opacity:1;" />';
										  		echo '<a href="#" class="editImagemDescricao" idImagem="'.$imagem['idblog_post_imagem'].'">';
												echo '<button class="edit"></button>';	
												echo '</a>';
												echo '<a href="#" class="excluirImagem" idImagemDelete="'.$imagem['idblog_post_imagem'].'">';
												echo '<button class="delete"></button>';	
												echo '</a>'; 

												echo '<a href="#" class="postImagem" idImagemPost="'.$imagem['idblog_post_imagem'].'">';
												echo '<button class="post_imagem"></button>';	
												echo '</a>'; 

												echo '<input type="hidden" name="idblog_post_imagem[]" value="'.$imagem['idblog_post_imagem'].'">';
												echo '<input type="hidden" name="descricao_imagem[]" value="'.$imagem['descricao_imagem'].'">';
												echo '<input type="hidden" name="imagem_blog_post[]" value="'.$imagem['nome_imagem'].'">';
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

	        <?php if(!empty($idg)){ ?>
					<input type='hidden' name='idg' id="idg" value="<?= $idg ?>">
			<?php } ?>

			<input type="hidden" name="idblog_post" id="idblog_post" value="<?= (($idBlog_post > 0)? $idBlog_post: 0); ?>" />
			<input name="imagem" id="imagem" type="hidden" value="<?= $blog_post['imagem'] ?>" class=""/> 	
			<input type="hidden" id="contador" name="contador" value="<?= $blog_post['contador'] ?>" />  
			<input type="hidden" id="mod" name="mod" value="<?= ($idBlog_post == 0)? "cadastro":"editar"; ?>" />  	
			<input type="submit" value="Salvar" class="bt_save salvar" />
			<input type="button" value="Cancelar" class="bt_cancel cancelar" />
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


<?php if($_REQUEST['acao'] == "listarBlog_post"){ ?><?php
	if(!verificaPermissaoAcesso($MODULOACESSO['modulo'].'_visualizar', $MODULOACESSO['usuario']))
		header('Location:index.php?mod=home&mensagemalerta='.urlencode('Voce nao tem privilegios para acessar este modulo!'));
?>

	<? if(!empty($idg)){ ?>
			<input type='hidden' name='idg' id="idg" value="<?= $idg ?>">
	<? } ?>
	<div id="titulo">
		<!-- <img src="images/modulos/blog_post_preto.png" height="22" width="24" alt="ico" /> -->
		<i class="fas fa-rss" aria-hidden="true"></i>
		<span>Listagem de Post<?= $nomeCategoria ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=blog_post&acao=formBlog_post&met=cadastroBlog_post<?= $idgcategoria ?>">Cadastro</a></li>
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
			<div class="box_ip"><label for="adv_data_hora">data Início</label><input type="text"  name="data_inicio" class="wDate" id="adv_data_inicio"></div>
				
			<div class="box_ip">
                <label  for="status">Status</label> 
                <div class="box_sel">
                  <label for="">Status</label>
                  <div class="box_sel_d">
                        <select name="status" id="status" class=''>
                      		<option></option>
                         	<option value="1"> Ativo </option>
                            <option value="0"> Inativo </option>
                        </select>
                  </div>
               </div>
            </div> 
			<a href="" class="advanced_bt" id="filtrar">Filtrar</a>
		</form>
	</div> 


	<div id="principal">
		<div id="abas">
			<ul class="abas_list">
				<li class="abas_list_li action"><a href="javascript:void(0)">Post</a></li>
			</ul>
			<ul class="abas_bts">
				<li class="abas_bts_li"><a href="index.php?mod=blog_post&acao=formBlog_post&met=cadastroBlog_post<?= $idgcategoria ?>"><img src="images/novo.png" alt="Cadastro Blog_post" title="Cadastrar Blog_post" /></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_post&output=print&'+queryDataTable);"><img src="images/imprimir.png" alt="Imprimir listagem" title="Imprimir listagem"></a></li>
				<li class="abas_bts_li"><a href="javascript:void(0)" onclick="popUp('relatorio_class.php?modulo=blog_post&output=xls&'+queryDataTable);"><img src="images/excel.png" alt="Exportar para o Excel" title="Exportar para o Excel"  ></a></li>
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
			requestInicio = "tipoMod=blog_post&p="+preventCache+"&";
			ordem = "idblog_post";
			dir = "desc";
			$(document).ready(function(){
				preTableBlog_post('<?php print $buscar; ?>');
			});
			dataTableBlog_post('<?php print $buscar; ?>');
			columnBlog_post();
		</script>
 

	</div>

<?php } ?> 


<!--/////////////////////////////////////////////////////////-->
<!--////////////// FORMULARIOS PARA A GALERIA ////////////////-->
<!--////////////////////////////////////////////////////////-->

<!--data dialog descrição-->
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

<!--data dialog exclusão de imagem-->
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
<input type="hidden" value="<?=ENDERECO?>" name="_endereco" id="_endereco" />
<!--Fim dialog exclusão de imagem-->

<div id="modal-confirmacao">
    <form class="form" method="post">
        <input type="button" value="NÃO" class="button cancel" />
        <input type="button" value="SIM" class="button confirm"/>
    </form>
</div>