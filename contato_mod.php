<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/contato/banner.png">
            <img class="bn-m" src="imagens/contato/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$fale_com_a_gente_agora?></h1>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="content1 d-flex">
			<form class="d-flex" id="form-contato2">
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
    			<div class="botao-padrao d-flex">
    	    		<a id="enviar-contato2" class="botao-red" href=""><?=$enviar_contato?></a>
    	    	</div>
			</form>
			<div class="conteudo1-info d-flex">
				<div class="info1 d-flex">
					<span><?=$info_escritorio?></span>
					<a href="">Rua Plínio Salgado 433, Neva - Cascavel - PR</a>
				</div>
				<div class="info1 d-flex">
					<span><?=$info_email?></span>
					<a href="mailto:comercial@brascloud.com.br">comercial@brascloud.com.br</a>
				</div>
				<div class="info1 d-flex">
					<span><?=$info_telefone_comercial?></span>
					<a href="tel:+554533264568">+55 (45) 3326-4568 | +55 (45) 9 9127-8107</a>
				</div>
			</div>
		</div>
	</section>

	<section class="mapa d-flex">
		<div class="mapa-title d-flex">
			<h2><?=$zonas_de_disponibilidade?></h2>
		</div>
		<img src="imagens/contato/mapa.png" usemap="#image-map">

        <map name="image-map">
            <area class="blue" coords="269,188,217,149" shape="rect">
            <area class="yellow" coords="170,214,235,257" shape="rect">
            <area class="darkblue" coords="518,603,561,633" shape="rect">
            <area class="darkyellow" coords="477,630,533,661" shape="rect">
            <area class="orange" coords="468,662,519,689" shape="rect">
        </map>

        <div class="box-map yellow">
            <span>São Paulo</span>
            <p>From basic business websites to major</p>
        </div>
        <div class="box-map blue">
            <span>São Paulo</span>
            <p>From basic business websites to major</p>
        </div>
        <div class="box-map orange">
            <span>Osasco SP</span>
            <p>SP02 DC Ascenty SP3</p>
        </div>
        <div class="box-map darkyellow">
            <span>Santana de Parnaíba - SP</span>
            <p>SP01 DC ODATA</p>
        </div>
        <div class="box-map darkblue">
            <span>São Paulo</span>
            <p>From basic business websites to major</p>
        </div>

        <div class="mapa-bottom content1 d-flex">
        	<h3><?=$regioes_em_breve?></h3>
        	<ul>
        		<li>Curitiba - PR</li>
        		<li>Porto Alegre - RS</li>
        	</ul>
        </div>
	</section>
</main>