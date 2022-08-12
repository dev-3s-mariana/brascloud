<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/servicos/banner.png">
            <img class="bn-m" src="imagens/servicos/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$nossos_servicos?></h1>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="content1 d-flex">
            <?php foreach($solucoes as $key => $s):?>
    			<div class="conteudo1-box d-flex">
    				<a class="box-link" href="<?=$_SESSION['IDIOMA_URL']?>/servicos/<?=$s['urlrewrite']?>"></a>
    				<div class="box-img d-flex">
                        <?php if(strlen($s['icone']) > 4):?>
    					    <img src="admin/files/solucoes/<?=$s['icone']?>" width="86" style="object-fit: contain;">
                        <?php else:?>
                            <i class="fas fa-<?=$s['icone_name']?> fa-5x"></i>
                        <?php endif;?>
    				</div>
    				<div class="box-text d-flex">
    					<h2><?=$s['nome']?></h2>
    				</div>
    			</div>
            <?php endforeach;?>
		</div>
	</section>
	
</main>