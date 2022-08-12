<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/lgpd/banner.png">
            <img class="bn-m" src="imagens/lgpd/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1>Políticas de privacidade e LGPD</h1>
		</div>
	</section>

    <?php if(!empty($politicas)):?>
    	<section class="conteudo1 d-flex">
    		<div class="faq d-flex">
    			<div class="content1 d-flex">
                    <?php foreach($politicas as $key => $p):?>
        				<div class="duvidas d-flex">
        		            <div class="faq-direction d-flex">
        	                    <div class="faq-perguntas d-flex">
        	                        <div class="faq-duvidas d-flex">
        	                            <h3><?=$p['pergunta']?></h3>
        	                            <img src="imagens/lgpd/seta.png">
        	                        </div>
        	                        <div class="duvidas-descricao d-flex">
        	                            <p>
        	                               <?=$p['resposta']?>
        	                            </p>
        	                        </div>
        	                    </div>
        		            </div>
        		        </div>
                    <?php endforeach;?>
    			</div>
    	    </div>
    	</section>
    <?php endif;?>

    <?php if(!empty($links)):?>
    	<section class="conteudo2 d-flex">
    		<div class="content1 d-flex">
    			<div class="conteudo2-cards d-flex">
                    <?php foreach($links as $key => $l):?>
        				<div class="box d-flex" style="position: relative;">
                            <a href="<?=$l['link']?>" target="_blank" class="box-link" style="left: 0; top: 0"></a>
        					<div class="box-img d-flex">
        						<img src="imagens/lgpd/link.png">
        					</div>
        					<div class="box-text d-flex">
        						<h2><?=$l['nome']?></h2>
        					</div>
        				</div>
                    <?php endforeach;?>
    			</div>
    		</div>
    	</section>
    <?php endif;?>

    <?php if(!empty($faq)):?>
    	<section class="conteudo3 d-flex">
    		<div class="content1 d-flex">
    			<div class="faq d-flex">
    				<div class="faq-title">
    					<h1>Dúvidas frequentes</h1>
    				</div>
                    <?php foreach($faq as $key => $f):?>
        				<div class="duvidas d-flex">
        		            <div class="faq-direction d-flex">
        	                    <div class="faq-perguntas d-flex">
        	                        <div class="faq-duvidas d-flex">
        	                            <h3><?=$f['pergunta']?></h3>
        	                            <img class="change-color" src="imagens/lgpd/faq.png">
        	                        </div>
        	                        <div class="duvidas-descricao d-flex">
        	                            <p>
        	                                <?=$f['resposta']?>
        	                            </p>
        	                        </div>
        	                    </div>
        		            </div>
        		        </div>
                    <?php endforeach;?>
    		    </div>
    		    <div class="conteudo3-img d-flex">
    		    	<img src="imagens/lgpd/img1.png">
    		    </div>
    	   	</div>
    	</section>
    <?php endif;?>

	<section class="conteudo4">
		<div class="conteudo4-content">
			<div class="content1-title">
				<h1>Ainda com dúvidas?</h1>
			</div>
			<form class="d-flex" id="form-duvida">
				<div class="div-input d-flex">
					<span>Nome</span>
					<input type="text" name="nome" class="required">
				</div>
				<div class="div-input d-flex">
					<span>E-mail</span>
					<input type="text" name="email" class="required">
				</div>
				<div class="div-input d-flex">
					<span>Telefone</span>
					<input type="text" name="telefone" class="required phone_br">
				</div>
				<div class="div-input d-flex">
					<span>Assunto</span>
					<input type="text" name="assunto" class="required">
				</div>
				<div class="div-text d-flex">
					<span>Mensagem</span>
					<textarea name="mensagem" class="required"></textarea>
				</div>
    			<div class="form-botao">
    				<a href="" id="enviar-duvida">ENVIAR DÚVIDA</a>
    			</div>
			</form>
		</div>
	</section>
</main>