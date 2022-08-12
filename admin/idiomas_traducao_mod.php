<?php 
	 // Versao do modulo: 3.00.010416

	include_once "idiomas_traducao_class.php";
	include_once "idiomas_class.php";

	if(!isset($_REQUEST['acao']))
		$_REQUEST['acao'] = "";	

	if(!isset($_REQUEST['idg'])){
		header("Location:index.php?mod=idiomas&acao=listarIdiomas&mensagemerro=".urlencode('Selecione um idioma para gerenciar a tradução!'));
	}

	$ididioma = $_REQUEST['idg'];
	$idioma = buscaIdiomas(array("ididiomas"=>$ididioma));
	if(empty($idioma)){
		header("Location:index.php?mod=idiomas&acao=listarIdiomas&mensagemerro=".urlencode('Idioma não encontrado!'));
	}

	$idioma = $idioma[0];


	$limitText = array();
	//se algum campo possuir limit colocar o nome da tag com o limit como seu valor
	$limitText['pag_assistencia_texto'] = 140;
?>
<link rel="stylesheet" type="text/css" href="idiomas_traducao_css.css" />
<script type="text/javascript" src="idiomas_traducao_js.js"></script>

<!--************************************
                                         _ _ _
                                        | (_) |
 _ __   _____   _____     ___    ___  __| |_| |_ __ _ _ __
| '_ \ / _ \ \ / / _ \   / _ \  / _ \/ _` | | __/ _` | '__|
| | | | (_) \ V / (_) | |  __/ |  __/ (_| | | || (_| | |
|_| |_|\___/ \_/ \___/   \___|  \___|\__,_|_|\__\__,_|_|
								*******************************-->


<?php if($_REQUEST['acao'] == "formIdiomas_traducao"){
		$metodo_titulo = "Gerenciar Tradução: ".$idioma['idioma'];
		//tags em portugues
		$tagsPt = buscaIdiomas_traducao(array("ididiomas"=>1,"ordem"=>"tag","dir"=>"asc"));	
		$tagsIdioma = array();		 
		if($ididioma != 1){ 
			$tagsIdioma = buscaIdiomas_traducao(array("ididiomas"=>$idioma['ididiomas'],"ordem"=>"tag","dir"=>"asc"));
		} 
?>

	<div id="titulo">
		<i class="fas fa-globe"></i>
		<span><?php print $metodo_titulo; ?></span>
		<ul class="other_abs">
			<li class="other_abs_li"><a href="index.php?mod=idiomas&acao=listarIdiomas">Idiomas</a></li>
 		</ul>
	</div> 

	<div id="principal">

		<input type="hidden" name="ididiomas" id='ididiomas' value="<?= $ididioma; ?>" />
		<input type="hidden" name="idioma" id='idioma' value="<?= $idioma['idioma']; ?>" />
		
      <div class="box_ip" style="width: 75%">
         <input type="text" id="buscar" style="width: 67%">
         <button id="enviarBusca" class="btn-pesquisar">Pesquisar</button>
         <button id="limparBusca" class="btn-limpar">Limpar</button>
      </div>

		<div class='listagemtags'>
			<?php foreach($tagsPt as $k => $v){ 
					$limit = "";
					if(array_key_exists($v['tag'], $limitText)){
						$limit = $limitText[$v['tag']];
					}
			?> 
					<div class="tags"> 
						<label style='width: 350px !important' class='nameTag'><?= $v['tag'] ?></label>
						<div class="box_ip">
							<label for="traducao_tag">Português</label>
							<textarea name="traducao_tag" maxlength='<?= $limit ?>'><?= $v['texto'] ?></textarea> 
							<input type='hidden' name='idioma_tag' value='<?= $v['ididiomas'] ?>'>
							<?php if(!empty($limit)){ ?>
									<span style='margin-top:10px;float:left;'>Limite de caracteres: <?= $limit ?></span>
							<?php } ?>
						</div> 

						<?php if(!empty($tagsIdioma)){ ?>
							<div class="box_ip">
								<label for="ididiomas"><?= $idioma['idioma'] ?></label>
								<textarea name="traducao_tag" maxlength='<?= $limit ?>'><?= $tagsIdioma[$k]['texto'] ?></textarea>
								<input type='hidden' name='idioma_tag' value='<?= $tagsIdioma[0]['ididiomas'] ?>'>
								<?php if(!empty($limit)){ ?>
									<span style='margin-top:10px;float:left;'>Limite de caracteres: <?= $limit ?></span>
								<?php } ?>
							</div>
						<?php } ?>	
						<img src='images/delete.png' class='deletetag'>
						<input type='hidden' name='tag' value='<?= $v['tag'] ?>'>
					</div> 
			<?php } ?> 
		</div>	
		
		<div class='novaTag'>
			<div class="box_ip">
				<label for="novatag">Criar nova Tag</label>
				<input type="text" name="novatag" id="novatag" value="" />
			</div>
			<div style='clear:both;'></div>
			<div class="box_ip">
				<label for="ididiomas">Português</label>
				<textarea name="traducao_tag" id='novo_texto' style='height:60px;'></textarea>				 
			</div>
			 
			<input type="submit" value="Salvar" class="bt_save salvar" />
		</div> 
		
	</div>

<?php } ?> 