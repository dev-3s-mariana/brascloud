<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/brascloud/banner.png">
            <img class="bn-m" src="imagens/brascloud/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$sobre_a_brascloud?>Sobre a Brascloud</h1>
		</div>
	</section>

	<section class="conteudo1">
    	<div class="content1">
    		<div class="conteudo1-text d-flex text-center">
	    		<div class="conteudo1-h d-flex">
	    			<h2><?=$home_titulo_pequeno_1?>MUITO PRAZER, SOMOS A</h2>
	    			<h1 class="text-white">Brascloud</h1>
	    		</div>
	    		<div class="conteudo1-p d-flex">
	    			<p><?=$sobre_texto_1?>
	    				<!-- Na simplicidade. Ela requer eficiência para resolver problemas e empatia para ter assertividade.
                        <br>
                        A simplicidade exalta a objetividade, cremos nela para construir uma nuvem capaz de melhorar a vida das pessoas. Sabemos que infraestrutura é importante, que digitalizar é tendência e que a transformação digital é o caminho. Porém, mais que IaaS ou PaaS, a BRASCLOUD acredita que as pessoas constroem o sucesso com tecnologias descomplicadas que melhoram o mundo.
	    				<br><br>
	    				Nossa nuvem é mais que tecnologia, é uma relação de confiança de gente que faz porque gosta de gente vivendo melhor. <br>
                        Em um cenário cada vez mais disruptivo, reduzir custos é uma necessidade ainda maior no movimento de convergência digital.
                        <br><br>
                        Poder economizar até 40% do custo total com Cloud Pública parece uma excelente ideia, não é mesmo? Com uma proposta ousada, a BRASCLOUD é a opção para atender as necessidade de Cloud Pública de DEVs, Brokers, Consultorias e Outsourcing. -->
	    			</p>
	    		</div>
	    	</div>
	    	<div class="conteudo1-cards d-flex">
	    		<div class="conteudo1-box d-flex">
                    <div class="box-img d-flex">
                        <img class="img-box1" src="imagens/brascloud/escalavel.png">
                    </div>
                    <div class="box-text text-center d-flex">
                        <h2><?=$sobre_card_titulo_1?></h2>
                        <p><?=$sobre_card_texto_1?></p>
                    </div>
                </div>
                <div class="conteudo1-box d-flex">
                    <div class="box-img d-flex">
                        <img class="img-box2" src="imagens/brascloud/dinamica.png">
                    </div>
                    <div class="box-text text-center d-flex">
                        <h2><?=$sobre_card_titulo_2?></h2>
                        <p><?=$sobre_card_texto_2?></p>
                    </div>
                </div>
                <div class="conteudo1-box d-flex">
                    <div class="box-img d-flex">
                        <img class="img-box3" src="imagens/brascloud/faturamento.png">
                    </div>
                    <div class="box-text text-center d-flex">
                        <h2><?=$sobre_card_titulo_3?></h2>
                        <p><?=$sobre_card_texto_3?></p>
                    </div>
                </div>
                <div class="conteudo1-box d-flex">
                    <div class="box-img d-flex">
                        <img class="img-box4" src="imagens/brascloud/self-service.png">
                    </div>
                    <div class="box-text text-center d-flex">
                        <h2><?=$sobre_card_titulo_4?></h2>
                        <p><?=$sobre_card_texto_4?></p>
                    </div>
                </div>
	    	</div>
	    	<div class="conteudo1-video">
	    		<iframe src="https://www.youtube.com/embed/xzcKHwNEYcs?autoplay=1&mute=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	    	</div>
    	</div>
        <?php if(!empty($timeline)):?>
            <div class="conteudo2">
                <div class="content1 d-flex">
                    <img class="bg-linha" src="imagens/brascloud/bg-linha.png">
                    <img class="linha-left" src="imagens/brascloud/left.png">
                    <div class="linha-slider">
                        <?php foreach($timeline as $key => $t):?>
                            <div>
                                <div class="text-slide d-flex">
                                    <div class="ts-dot d-flex">
                                        <div class="dot-titulo d-flex">
                                            <h2 style="cursor: pointer;"><?=$t['ano']?></h2>
                                        </div>
                                        <div class="dot-bg">
                                            <img src="imagens/brascloud/bg-barra.png">
                                        </div>
                                    </div>
                                    <div class="ts-titulo d-flex"  style="cursor: pointer;">
                                        <h2><?=$t['titulo']?></h2>
                                        <p>
                                           <?=$t['texto']?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                    <img class="linha-right" src="imagens/brascloud/right.png">
                </div>
            </div>
        <?php endif;?>
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

    <section class="conteudo3 d-flex">
        <div class="content1 d-flex">
            <div class="conteudo3-img d-flex">
                <img src="imagens/brascloud/img1.png">
            </div>
            <div class="conteudo3-text d-flex">
                <h2><?=$termos_legais_titulo?></h2>
                <p><?=$termos_legais_texto?></p>
                <a href="<?=$_SESSION['IDIOMA_URL']?>/lgpd"><?=$continuar_lendo?></a>
            </div>
        </div> 
    </section>
</main>