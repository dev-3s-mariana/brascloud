<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/suporte/banner.png">
            <img class="bn-m" src="imagens/suporte/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$suporte_brascloud?></h1>
		</div>
	</section>

    <?php if(!empty($categoria_suporte)):?>
    	<section id="categoria" class="categorias">
    		<div class="categoria-slider">
                <?php foreach($categoria_suporte as $key => $cs):?>
        			<div class="categoria-suporte" data-idcs="<?=$cs['idcategoria_suporte']?>">
        				<div class="categoria-slide d-flex">
        					<a class="box-link"></a>
        					<div class="categoria-img d-flex">
                                <?php if(strlen($cs['icone']) > 4):?>
        						    <img src="admin/files/categoria_suporte/<?=$cs['icone']?>">
                                <?php else:?>
                                    <i style="color: #c4372b" class="fas fa-<?=$cs['icone_name']?> fa-3x"></i>
                                <?php endif;?>
        					</div>
        					<div class="categoria-text d-flex">
        						<h2><?=$cs['titulo']?></h2>
        					</div>
        				</div>
        			</div>
                <?php endforeach;?>
    		</div>
    	</section>
    <?php endif;?>

    <?php if(!empty($suporte)):?>
    	<section id="suporte" class="suporte d-flex">
    		<div class="content1 d-flex">
    			<div class="suporte-menu">
    				<div class="categoria2-slider">
                        <?php foreach($suporte as $key => $s):?>
        					<div class="div-suporte" data-ids="<?=$s['idsuporte']?>" data-idcs="<?=$s['idcategoria_suporte']?>" style="<?=$s['idcategoria_suporte'] == $idcategoria_suporte?'':'display: none'?>">

        						<div class="categoria2-slide d-flex">
        							<img class="cate2-img" src="imagens/suporte/barra.png">
        							<div class="categoria2-link <?=$key==0?'categoria2-active':''?> d-flex">
        								<a><?=$s['titulo']?></a>
        							</div>
        						</div>
        					</div>
                        <?php endforeach;?>
    				</div>
    				<div class="suporte-botao d-flex">
    					<a href=""><?=$veja_a_wiki?></a>
    				</div>
    			</div>
    			
                <?php foreach($suporte as $key => $s):?>
                    <?php $faq = buscaFaq(array('idsuporte'=>$s['idsuporte']));?>
        			<div class="conteudo-slider conteudo-suporte" data-ids="<?=$s['idsuporte']?>" style="<?=$key==0?'':'display: none'?>">
        				<div>
        					<div class="conteudo-slide d-flex">
        						<div class="conteudo-1 d-flex">
        							<div class="conteudo-1-text1 d-flex">
        								<h1><?=$s['titulo']?></h1>
        								<p>
        									<?=$s['descricao']?>
        								</p>
        							</div>
        							<!-- <div class="conteudo-1-text2 d-flex">
        								<h2><?=$s['titulo']?></h2>
        								<p>
        									<?=$s['texto']?>
        								</p>
        							</div> -->
        						</div>
        						<div class="faq conteudo-2">
        					        <div class="duvidas">
                                        <?php if(!empty($s['titulo_faq']) || !empty($s['texto_faq'])):?>
            					            <div class="conteudo-2-text d-flex">
            									<h2><?=$s['titulo_faq']?></h2>
            									<span><img src="imagens/suporte/seta.png"></span>
            									<p>
            										<?=$s['texto_faq']?>
            									</p>
            								</div>
                                        <?php endif;?>
        					            <div class="faq-direction">
                                            <?php foreach($faq as $key => $f):?>
            				                    <div class="faq-perguntas">
            				                        <div class="faq-duvidas">
            				                            <h3><?=$f['pergunta']?></h3>
            				                            <img class="change-color" src="imagens/suporte/faq.png">
            				                        </div>

            				                        <div class="duvidas-descricao">
            				                            <p>
            				                               <?=nl2br($f['resposta'])?>
            				                            </p>
            				                        </div>
            				                    </div>
                                            <?php endforeach;?>
        					            </div>
        					        </div>
        					    </div>
                                <?php if(!empty($s['titulo_caixa']) || !empty($s['texto_caixa'])):?>
            					    <div class="conteudo-3 d-flex">
            					    	<div class="conteudo-3-text d-flex">
            					    		<h2><?=$s['titulo_caixa']?></h2>
            					    		<p>
            	                               <?=$s['texto_caixa']?>
            	                            </p>
            					    	</div>
            					    </div>
                                <?php endif;?>
        					</div>
        				</div>
        			</div>
                <?php endforeach;?>
    		</div>
    	</section>
    <?php endif;?>

	<section class="conteudo1">
		<div class="conteudo1-content">
			<div class="content1-title">
				<h1><?=$duvidas_titulo?></h1>
			</div>
			<form class="d-flex" id="form-duvida">
				<div class="div-input d-flex">
					<span><?=$duvidas_nome?></span>
					<input type="text" name="nome" class="required">
				</div>
				<div class="div-input d-flex">
					<span><?=$duvidas_email?></span>
					<input type="text" name="email" class="required">
				</div>
				<div class="div-input d-flex">
					<span><?=$duvidas_telefone?></span>
					<input type="text" name="telefone" class="required phone_br">
				</div>
				<div class="div-input d-flex">
					<span><?=$duvidas_assunto?></span>
					<input type="text" name="assunto" class="required">
				</div>
				<div class="div-text d-flex">
					<span><?=$duvidas_mensagem?></span>
					<textarea name="mensagem" class="required"></textarea>
				</div>
    			<div class="form-botao">
    				<a href="" id="enviar-duvida"><?=$enviar_duvida?></a>
    			</div>
			</form>
		</div>
	</section>
</main>