<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/inovacao/banner.png">
            <img class="bn-m" src="imagens/inovacao/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$inovacao_e_tecnologias?></h1>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="conteudo1-content d-flex">
			<div class="conteudo1-text d-flex">
				<h1><?=$inovacao_titulo?></h1>
				<p><?=$inovacao_texto?></p>
			</div>
			<div class="conteudo1-cards d-flex">
				<div class="conteudo1-box d-flex">
					<div class="box-img d-flex">
						<img src="imagens/inovacao/icon.png">
					</div>
					<div class="box-text d-flex">
						<h2 class="text-orange"><?=$inovacao_card_titulo_1?></h2>
						<p><?=$inovacao_card_texto_1?></p>
					</div>
				</div>
				<div class="conteudo1-box d-flex">
					<div class="box-img d-flex">
						<img src="imagens/inovacao/icon.png">
					</div>
					<div class="box-text d-flex">
						<h2 class="text-orange"><?=$inovacao_card_titulo_2?></h2>
						<p><?=$inovacao_card_texto_2?></p>
					</div>
				</div>
				<div class="conteudo1-box d-flex">
					<div class="box-img d-flex">
						<img src="imagens/inovacao/icon.png">
					</div>
					<div class="box-text d-flex">
						<h2 class="text-orange"><?=$inovacao_card_titulo_3?></h2>
						<p><?=$inovacao_card_texto_3?></p>
					</div>
				</div>
				<div class="conteudo1-box d-flex">
					<div class="box-img d-flex">
						<img src="imagens/inovacao/icon.png">
					</div>
					<div class="box-text d-flex">
						<h2 class="text-orange"><?=$inovacao_card_titulo_4?>IMPOSTOS INCLUSOS</h2>
						<p><?=$inovacao_card_texto_4?></p>
					</div>
				</div>
			</div>
		</div>
	</section>	

    <?php if(!empty($equipe)):?>
    	<section class="conteudo2 d-flex">
    		<div class="content1 d-flex">
    			<div class="conteudo2-title d-flex">
    				<h1><?=$arquitetos?>Arquitetos</h1>
    			</div>
    			<div class="arquitetos-slider">
                    <?php foreach($equipe as $key => $e):?>
        				<div>
        					<div class="arquitetos-slide d-flex">
        						<div class="slide1-img d-flex">
        							<img src="admin/files/equipe/<?=$e['imagem']?>">
        						</div>
        						<div class="slide1-text d-flex">
        							<h2 class="text-orange"><?=$e['nome']?></h2>
        						</div>
        					</div>
        				</div>
                    <?php endforeach;?>
    			</div>
    			<div class="botao-padrao d-flex">
    		    	<a class="botao-red abrir-publica"><?=$converse_com_nossos_arquitetos?></a>
    		    </div>
    		</div>
    	</section>
    <?php endif;?>

	<section class="conteudo3">
		<div class="content1">
			<div class="vantagens-slider">
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_1?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_2?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_3?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_4?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_5?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
				<div>
					<div class="vantanges-slide d-flex">
						<div class="slide3-text d-flex">
							<h2><?=$vantagens_titulo_6?></h2>
						</div>
						<div class="slide3-img d-flex">
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="conteudo4 d-flex">
		<div class="conteudo4-content d-flex">
			<div class="conteudo4-text d-flex">
				<div class="conteudo4-title d-flex">
					<h1><?=$nossos_pilares?></h1>
				</div>
				<div class="conteudo4-cards d-flex">
					<div class="conteudo4-box d-flex">
		    			<h2><?=$pilares_card_titulo_1?> <span>01</span></h2>
		    			<p><?=$pilares_card_texto_1?></p>
					</div>
					<div class="conteudo4-box d-flex">
		    			<h2><?=$pilares_card_titulo_2?> <span>02</span></h2>
		    			<p><?=$pilares_card_texto_2?></p>
					</div>
					<div class="conteudo4-box d-flex">
		    			<h2><?=$pilares_card_titulo_3?>Escalável <span>03</span></h2>
		    			<p><?=$pilares_card_texto_3?></p>
					</div>
					<div class="conteudo4-box d-flex">
		    			<h2><?=$pilares_card_titulo_4?> <span>04</span></h2>
		    			<p><?=$pilares_card_texto_4?></p>
					</div>
				</div>
			</div>
			<div class="conteudo4-img d-flex">
				<img src="imagens/inovacao/img1.png">
			</div>
		</div>
	</section>

    <?php if(!empty($solucoes)):?>
    	<section id="solucoes-home" class="solucoes d-flex">
        	<div class="solucoes-content d-flex">
        		<div class="solucoes-text d-flex text-center">
    	    		<div class="solucoes-h d-flex">
    	    			<h1><?=$outros_servicos?></h1>
    	    		</div>
    	    	</div>
    	    	<div class="solucoes-slider">
                    <?php foreach($solucoes as $key => $s):?>
        	    		<div>
        	    			<div class="so-slide d-flex">
        	    				<div class="so-img d-flex">
                                    <?php if(strlen($s['icone']) > 4):?>
        	    					    <img src="admin/files/solucoes/<?=$s['icone']?>">
                                    <?php else:?>
                                        <i style="color: #c7c7c7" class="fas fa-<?=$s['icone_name']?> fa-4x"></i>
                                    <?php endif;?>
        	    				</div>
        	    				<div class="so-text d-flex text-center">
        	    					<h2><?=$s['nome']?></h2>
        	    				</div>
        	    			</div>
        	    		</div>
                    <?php endforeach;?>
    	    	</div>
    	    	<div class="botao-padrao d-flex">
    		    	<a class="botao-red" href="servicos"><?=$ver_todas_as_solucoes?></a>
    		    </div>
        	</div>
        </section>
    <?php endif;?>
</main>