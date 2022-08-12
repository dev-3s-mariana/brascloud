<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img src="admin/files/landing_page/<?=$lp['banner_topo']?>">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$lp['titulo']?></h1>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="content1 d-flex">
			<div class="conteudo1-1 d-flex">
                <?php if(!empty($lp['descricao'])):?>
    				<div class="conteudo1-text d-flex">
    					<h1>Descrição técnica</h1>
    					<p>
    						<?=nl2br($lp['descricao'])?>
    					</p>
    				</div>
                <?php endif;?>
                <?php if(!empty($itens)):?>
    				<div class="conteudo1-cards d-flex">
                        <?php foreach($itens as $key => $i):?>
        					<div class="conteudo1-box d-flex">
        						<div class="box1-img d-flex">
        							<div class="d-flex">
                                        <?php if(!empty($i['imagem'])):?>
        								    <img src="admin/files/itens/<?=$i['imagem']?>">
                                        <?php else:?>
                                            <i style="color: #fe8001;" class="fas fa-<?=$i['nome_icone']?> fa-4x"></i>
                                        <?php endif;?>
        							</div>
        						</div>
        						<div class="box1-text d-flex ">
        							<h2 class="text-orange"><?=$i['nome']?></h2>
        							<p class="text-white">
        								<?=$i['descricao']?>
        							</p>
        						</div>
        					</div>
                        <?php endforeach;?>
    				</div>
                <?php endif;?>
			</div>
		</div>
	</section>

	<section class="conteudo2 d-flex">
    	<div class="conteudo2-text1 d-flex">
            <?php if(!empty($lp['diferenciais_texto'])):?>
        		<div class="text-1 d-flex">
        			<h1>Diferenciais</h1>
        			<p>
        				<?=$lp['diferenciais_texto']?>
        			</p>
        		</div>
            <?php endif;?>
            <?php if(!empty($difs)):?>
    			<div class="list-card d-flex">
                    <?php foreach($difs as $key => $d):?>
        				<div class="list-box d-flex">
        					<img src="imagens/publica/check.png">
        					<span><?=$d['nome']?></span>
        				</div>
                    <?php endforeach;?>
    			</div>	
            <?php endif;?>
    	</div>

        <?php if(!empty($indicacoes)):?>
    		<div class="conteudo2-text2 d-flex">
    			<div class="conteudo2-titulo d-flex">
    				<h1>Indicações</h1>
    			</div>
                <?php foreach($indicacoes as $key => $ind):?>
        			<div class="list-box2 d-flex">
        				<h2 class="text-orange"><?=$ind['nome']?></h2>
        				<p class="text-white">
        					<?=$ind['descricao']?>
        				</p>
        			</div>
                <?php endforeach;?>
    		</div>
        <?php endif;?>
	</section>
	

	<section class="conteudo4 d-flex">
    	<div class="content1 d-flex">
            <?php if(!empty($abrangencia)):?>
        		<div class="conteudo4-text text-center d-flex">
        			<h1>Abrangência</h1>
        		</div>
        		<div class="conteudo4-cards d-flex">
                    <?php foreach($abrangencia as $key => $abr):?>
            			<div class="conteudo4-box d-flexx">
            				<div class="conteudo4-img d-flex">
                                <?php if(!empty($abr['imagem'])):?>
            					    <img src="admin/files/abrangencia/<?=$abr['imagem']?>">
                                <?php else:?>
                                    <i style="color: #fe8001" class="fas fa-<?=$abr['nome_icone']?> fa-2x"></i>
                                <?php endif;?>
            				</div>
            				<div class="conteudo4-box-text d-flex">
            					<h2 class="text-orange"><?=$abr['nome']?></h2>
            					<p>
        		    				<?=$abr['descricao']?>
        		    			</p>
            				</div>
            			</div>
                    <?php endforeach;?>
        		</div>
            <?php endif;?>
    		<div class="botao-padrao d-flex">
	    		<a class="botao-red abrir-publica" style="cursor: pointer;">CADASTRE-SE AGORA</a>
	    	</div>
	    	<div class="text-bottom d-flex">
	            <div class="text-tags d-flex">
	                <h3>Tags:</h3>
	                <a href="">cloud</a>
	                <span>,</span>
	                <a href="">vpn brasil</a>
	                <span>,</span>
	                <a href="">cpu</a>
	                <span>,</span>
	                <a href="">SPVPN</a>
	                <span>,</span>
	                <a href="">aws</a>
	            </div>
	            <div class="text-compartilhe d-flex">
	                <h3>Compartilhe:</h3>
	                <a href="https://www.facebook.com/sharer.php?u=<?=ENDERECO;?>publica" target="_blank"><img src="imagens/publica/face.png"></a>
	                <a href="https://twitter.com/intent/tweet?text=<?=ENDERECO;?>publica" target="_blank"><img src="imagens/publica/twitter.png"></a>
	                <a href=""><img src="imagens/publica/share.png"></a>
	            </div>
	        </div>
    	</div>	
	</section>

    <?php if(!empty($depoimento)):?>
    	<section id="comentarios" class="comentarios d-flex">
    		<div class="comentarios-titulo d-flex text-center">
    			<h1>O que estão falando de nós</h1>
    		</div>
    		<div class="comentarios-slider">
                <?php foreach($depoimento as $key => $d):?>
        			<div>
        				<div class="coment-slide d-flex">
        					<div class="coment-text d-flex">
        						<p>
        							<?=$d['depoimento']?>
        						</p>
        					</div>
        					<div class="coment-img d-flex">
        						<img src="admin/files/depoimento/<?=$d['imagem']?>">
        						<div class="coment-user d-flex text-white">
        							<h2><?=$d['nome']?></h2>
        							<span><?=$d['subtitulo']?></span>
        						</div>
        					</div>
        				</div>
        			</div>
                <?php endforeach;?>
    		</div>
    		<div class="botao-padrao d-flex">
    	    	<a class="botao-red abrir-publica" style="cursor: pointer;">SEJA O PRÓXIMO CASE</a>
    	    </div>
    	</section>
    <?php endif;?>
</main>