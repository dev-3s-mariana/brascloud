<main>
    <section class="banner">
    	<div class="banner-slider">
            <?php foreach($banner as $key => $b):?>
        		<div onclick="<?=$b['dinamico'] != 1 && !empty($b['link'])?"window.open('".$b['link']."', '_blank')":''?>">
        			<div class="bn-slide">
            			<div class="bn-text text-white">
                            <?php if($b['dinamico'] == 1):?>
                					<h1><?=$b['nome']?></h1>
                					<p>
                						<?=nl2br($b['subtitulo'])?>
                					</p>
                                <?php if(!empty($b['titulo_botao'])):?>
                					<a class="botao-red" href="<?=$b['link']?>"><?=$b['titulo_botao']?></a>
                                <?php endif;?>
                            <?php endif;?>
            			</div>
        				<picture class="d-flex">
        					<img src="admin/files/banner/<?=$b['banner_full']?>">
        				</picture>
        			</div>
        		</div>
            <?php endforeach;?>
    	</div>
    	<div class="mouse d-flex">
    		<img src="imagens/home/mouse.png">
    	</div>
    </section>

    <section class="conteudo1">
    	<div class="content1">
    		<div class="conteudo1-text d-flex text-center">
	    		<div class="conteudo1-h d-flex">
	    			<h2><?=$home_titulo_pequeno_1?></h2>
	    			<h1 class="text-white"><?=$home_titulo_grande_1?></h1>
	    		</div>
	    		<div class="conteudo1-p d-flex">
	    			<p class="text-white"><?=$home_texto_1?></p>
	    		</div>
	    	</div>
	    	<div class="conteudo1-video">
	    		<iframe src="https://www.youtube.com/embed/PcKZJJLpFzg?autoplay=1&mute=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	    	</div>
    	</div>  	
    </section>

    <?php if(!empty($solucoes)):?>
        <section id="solucoes-home" class="solucoes d-flex">
        	<div class="solucoes-content d-flex">
        		<div class="solucoes-text d-flex text-center">
    	    		<div class="solucoes-h d-flex">
    	    			<h2><?=$home_titulo_pequeno_2?></h2>
    	    			<h1><?=$home_titulo_grande_2?></h1>
    	    		</div>
    	    		<div class="solucoes-p d-flex">
    	    			<p><?=$home_texto_2?></p>
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
                                        <i class="fas fa-<?=$s['icone_name']?> fa-5x" style="color: #c9c9c9"></i>
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
    	    		<a class="botao-red" href="servicos"><?=$saiba_mais?></a>
    	    	</div>
    	    	
        	</div>
        </section>
    <?php endif;?>

    <section class="conteudo3 d-flex">
    	<div class="content1 d-flex">
    		<div class="conteudo3-text d-flex">
	    		<div class="conteudo3-h d-flex">
	    			<h2><?=$home_titulo_pequeno_3?></h2>
	    			<h1><?=$home_titulo_grande_3?></h1>
	    		</div>
	    		<div class="conteudo3-p d-flex">
	    			<p><?=$home_texto_3?></p>
	    		</div>
	    	</div>

	    	<div class="conteudo3-cards d-flex">
	    		<div class="conteudo3-box d-flex">
	    			<h2 class="text-orange"><?=$home_card_titulo_1?> <span>01</span></h2>
	    			
	    			<p><?=$home_card_texto_1?></p>
	    		</div>
	    		<div class="conteudo3-box d-flex">
	    			<h2 class="text-orange"><?=$home_card_titulo_2?> <span>02</span></h2>
	    			
	    			<p><?=$home_card_texto_2?></p>
	    		</div>
	    		<div class="conteudo3-box d-flex">
	    			<h2 class="text-orange"><?=$home_card_titulo_3?> <span>03</span></h2>
	    			
	    			<p><?=$home_card_texto_3?></p>
	    		</div>
	    		<div class="conteudo3-box d-flex">
	    			<h2 class="text-orange"><?=$home_card_titulo_4?> <span>04</span></h2>
	    			
	    			<p><?=$home_card_texto_4?></p>
	    		</div>
	    	</div>
    	</div>
    </section>

    <section class="conteudo4 d-flex">
    	<div class="content1 d-flex">
    		<div class="conteudo4-text text-center d-flex">
    			<h1><?=$home_titulo_grande_4?></h1>
    		</div>
    		<div class="conteudo4-cards d-flex">
    			<div class="conteudo4-box d-flexx">
    				<div class="conteudo4-img d-flex">
    					<img src="imagens/home/icon.png">
    				</div>
    				<div class="conteudo4-box-text d-flex">
    					<h2 class="text-orange"><?=$beneficios_titulo_1?></h2>
    					<p><?=$beneficios_texto_1?></p>
    				</div>
    			</div>
    			<div class="conteudo4-box d-flex">
    				<div class="conteudo4-img d-flex">
    					<img src="imagens/home/icon.png">
    				</div>
    				<div class="conteudo4-box-text d-flex">
    					<h2 class="text-orange"><?=$beneficios_titulo_2?></h2>
    					<p><?=$beneficios_texto_2?></p>
    				</div>
    			</div>
    			<div class="conteudo4-box d-flex">
    				<div class="conteudo4-img d-flex">
    					<img src="imagens/home/icon.png">
    				</div>
    				<div class="conteudo4-box-text d-flex">
    					<h2 class="text-orange"><?=$beneficios_titulo_3?></h2>
    					<p><?=$beneficios_texto_3?></p>
    				</div>
    			</div>
    			<div class="conteudo4-box d-flex">
    				<div class="conteudo4-img d-flex">
    					<img src="imagens/home/icon.png">
    				</div>
    				<div class="conteudo4-box-text d-flex">
    					<h2 class="text-orange"><?=$beneficios_titulo_4?>Baixa latência</h2>
    					<p><?=$beneficios_texto_4?></p>
    				</div>
    			</div>
    		</div>
    		<div class="botao-padrao d-flex">
	    		<a class="botao-red" href=""><?=$saiba_mais?></a>
	    	</div>
    	</div>
    </section>

    <?php if(!empty($planos)):?>
        <section id="planos1-home" class="planos1 d-flex">
        	<div class="planos1-content d-flex">
        		<div class="planos1-text d-flex text-center">
        			<h1 class="text-white"><?=$nossos_planos?></h1>
        		</div>
        		<div class="planos1-slider">
                    <?php foreach($planos as $key => $p):?>
                        <?php $itns = buscaItns(array('idplanos'=>$p['idplanos'], 'ordem'=>'ordem', 'dir'=>'asc'));?>
            			<div>
            				<div class="pla-slide d-flex">
            					<div class="part1 d-flex">
            						<div class="part1-text d-flex">
            							<h3><?=$p['nome']?></h3>
            							<h1>R$<?=$p['preco_hora']?></h1>
            							<span>por hora</span>
            						</div>

            						<div class="part1-img d-flex">
            							<div>
                                            <?php if(strlen($p['icone']) > 4):?>
            								    <img src="admin/files/planos/<?=$p['icone']?>">
                                            <?php else:?>
                                                <i class="fas fa-<?=$p['icone_name']?> fa-3x" style="color: #fe8001"></i>
                                            <?php endif;?>
            							</div>
            						</div>
            					</div>

            					<div class="part2 d-flex">
                                    <?php foreach($itns as $key => $i):?>
                						<div class="part2-box d-flex">
                							<div class="part2-img d-flex">
                                                <?php if(strlen($i['icone']) > 4):?>
                								    <img src="admin/files/itns/<?=$i['icone']?>">
                                                <?php else:?>
                                                    <i class="fas fa-<?=$i['nome_icone']?>" style="color: #C4372B"></i>
                                                <?php endif;?>
                							</div>
                							<div class="part2-text d-flex">
                								<span><?=$i['nome']?></span>
                							</div>
                						</div>
                                    <?php endforeach;?>
            					</div>

            					<div class="part3 d-flex">
            						<a class="text-white text-center" href="https://portal.brascloud.com.br/#/index/signup" target="_blank"><?=$crie_uma_conta?></a>
            					</div>
            				</div>
            			</div>
                    <?php endforeach;?>
        		</div>
        	</div>
        </section>
    <?php endif;?>

    <section class="conteudo6 d-flex">
    	<div class="content1 d-flex">
    		<div class="conteudo6-text d-flex">
    			<h1><?=$home_planos_titulo_1?></h1>
    			<h2><?=$home_planos_titulo_2?></h2>
    			<p><?=$home_planos_texto?></p>
    			<a href="<?=$_SESSION['IDIOMA_URL']?>/planos"><?=$conhecer_planos?></a>
    		</div>
    		<div class="conteudo6-img d-flex">
    			<img src="imagens/home/img1.png">
    		</div>
    	</div>
    </section>

    <?php if(!empty($parceiros)):?>
        <section id="selos" class="selos">
        	<div class="selos-content d-flex">
        		<div class="selos-text text-center d-flex">
        			<h1><?=$selos_e_partners?></h1>
        		</div>
        		<div class="selos-slider">
                    <?php foreach($parceiros as $key => $p):?>
            			<div>
            				<div class="selo-slide">
            					<img class="selo-img1" src="admin/files/parceiros/<?=$p['imagem']?>">
            				</div>
            			</div>
                    <?php endforeach;?>
        		</div>
        	</div>
        </section>
    <?php endif;?>

    <?php if(!empty($maisRecentes)):?>
        <section class="conteudo8 d-flex">
        	<div class="conteudo8-content d-flex">
        		<div class="conteudo8-text d-flex">
        			<h2>Blog</h2>
        			<p>
        				Lorem ipsum is sumply dummy text of the printing and typesetting industry.Lorem ipsum is sumply dummy text of the printing and typesetting industry.Lorem ipsum is sumply dummy text of the printing and typesetting industry.
        			</p>
        			<div class="conteudo8-img d-flex">
        				<div class="blog-arrows d-flex">
        					<div class="borda d-flex blog-left">
        						<img class="" src="imagens/home/left.png">
        					</div>
    	    				<div class="borda d-flex blog-right">
        						<img class="" src="imagens/home/right.png">
        					</div>
    	    			</div>
    	    			<div class="blog-botao d-flex">
    	    				<a href="<?=$_SESSION['IDIOMA_URL']?>/blog">VER TODOS</a>
    	    			</div>
        			</div>
        			
        		</div>
        		<div class="blog-slider">
                    <?php foreach($maisRecentes as $key => $m):?>
            			<div>
            				<div class="blog-slide d-flex">
            					<div class="blog-bg"></div>
            					<div class="bg-slide-img">
            						<img src="admin/files/blog/thumb_<?=$m['imagem']?>">
            					</div>
            					<div class="bg-slide-text">
            						<h2><?=$m['nome']?></h2>
            						<p>
        			    				<?=$m['resumo']?>
        			    			</p>
        			    			<a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$m['urlrewrite']?>">CONTINUE LENDO <img src="imagens/home/Arrow.png"></a>
            					</div>
            				</div>
            			</div>
                    <?php endforeach;?>
        		</div>
        	</div>
        </section>
    <?php endif;?>
</main>