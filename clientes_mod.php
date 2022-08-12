<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/clientes/banner.png">
            <img class="bn-m" src="imagens/clientes/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$nossos_clientes?></h1>
		</div>
	</section>

	<section class="conteudo1">
		<div class="conteudo1-title d-flex">
			<h1><?=$clientes_titulo_1?></h1>
		</div>
		<div class="clientes-slider1 segmento-slider" data-total="<?=count($segmento)?>">
            <?php foreach($segmento as $key => $s):?>
    			<div class="div-segmento" data-ids="<?=$s['idsegmento']?>">
    				<div class="cs-box1 d-flex">
    					<div class="box1-img d-flex">
                            <?php if(strlen($s['icone']) > 4):?>
    						    <img src="admin/files/segmento/<?=$s['icone']?>">
                            <?php else:?>
                                <i class="fas fa-<?=$s['icone_name']?> fa-3x"></i>
                            <?php endif;?>
    					</div>
    					<div class="box1-text d-flex">
    						<h2><?=$s['titulo']?></h2>
    					</div>
    				</div>
    			</div>
            <?php endforeach;?>
		</div>
	</section>

	<section class="conteudo2">
		<div class="conteudo2-title">
			<h1><?=$clientes_titulo_2?></h1>
		</div>
		<div class="clientes-slider2 cliente-slider">
            <?php foreach($segmento as $key => $s):?>
                <?php $cliente = buscaCliente(array('status'=>'A', 'idsegmento'=>$s['idsegmento']));?>
                <div class="cliente-slider2">
                    <?php foreach($cliente as $key => $c):?>
                        <?php $projeto = buscaProjeto(array('status'=>'A', 'idcliente'=>$c['idcliente']));?>
                        <div class="div-cliente" data-idc="<?=$c['idcliente']?>" data-ids="<?=$s['idsegmento']?>">
                            <div class="d-flex div-selo">
                                <img src="admin/files/cliente/<?=$c['imagem']?>" alt="">
                                <img class="bg-barra" src="imagens/clientes/barra2.png">
                            </div>
                            <!-- <div class="conteudo-box">
                                <div class="box-coluna d-flex projeto-slider">
                                    <ul class="d-flex">
                                        <?php foreach($projeto as $key => $p):?>
                                            <li class="d-flex li-projeto" data-idp="<?=$p['idprojeto']?>" data-idc="<?=$p['idcliente']?>">
                                                <a class="<?=$key==0?'active-coluna':''?>"><?=$p['titulo']?></a>
                                            </li>
                                        <?php endforeach;?>
                                    </ul>
                                </div>
                                <?php foreach($projeto as $key => $p):?>
                                    <div class="box-conteudo d-flex" style="display: <?=$key==0?'flex':'none'?>" data-idp="<?=$p['idprojeto']?>" data-idc="<?=$p['idcliente']?>">
                                        <p>
                                            <?=$p['descricao']?>
                                        </p>
                                        <img src="admin/files/projeto/<?=$p['imagem']?>">
                                        <div class="conteudo-p">
                                            <h2><?=$p['titulo_caixa']?></h2>
                                            <p>
                                                <?=$p['texto']?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div> -->
                        </div>
                    <?php endforeach;?>
                </div>
            <?php endforeach;?>
		</div>
	</section>

    <?php foreach($clientes as $key => $cs):?>
        <?php $projeto = buscaProjeto(array('status'=>'A', 'idcliente'=>$cs['idcliente']));?>
    	<section class="conteudo-slider content1" style="display: <?=$key==0?'block':'none'?>" data-idcliente="<?=$cs['idcliente']?>">
            <div>
            	<div class="conteudo-box d-flex">
            		<div class="box-coluna d-flex projeto-slider">
            			<ul class="d-flex ul-projeto" data-idc="<?=$c['idcliente']?>">
                            <?php foreach($projeto as $key =>$p):?>
                				<li class="d-flex li-projeto" data-idp="<?=$p['idprojeto']?>" data-idc="<?=$cs['idcliente']?>">
                					<a class="<?=$key==0?'active-coluna':''?>"><?=$p['titulo']?></a>
                				</li>
                            <?php endforeach;?>
            			</ul>
            		</div>
                    <?php foreach($projeto as $key =>$p):?>
                		<div class="box-conteudo d-flex" style="display: <?=$key==0?'block':'none'?>" data-idp="<?=$p['idprojeto']?>" data-idc="<?=$c['idcliente']?>">
                			<p>
                				<?=$p['descricao']?>
                			</p>
                            <?php if(!empty($p['imagem'])):?>
                			    <img src="admin/files/projeto/<?=$p['imagem']?>">
                            <?php endif;?>
                            <?php if(!empty($p['titulo_caixa']) || !empty($p['texto'])):?>
                    			<div class="conteudo-p">
                    				<h2><?=$p['titulo_caixa']?></h2>
                    				<p>
                    					<?=$p['texto']?>
                    				</p>
                    			</div>
                            <?php endif;?>
                		</div>
                    <?php endforeach;?>
            	</div>
            </div>
    	</section>
    <?php endforeach;?>

    <?php foreach($depoimento as $key => $d):?>
    	<section id="comentarios" class="comentarios d-flex">
    		<div class="comentarios-titulo d-flex text-center">
    			<h1><?=$clientes_titulo_3?>O que estão falando de nós</h1>
    		</div>
    		<div class="comentarios-slider">
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
		    </div>
    		<div class="botao-padrao d-flex">
    	    	<a class="botao-red abrir-publica"><?=$seja_o_proximo_case?></a>
    	    </div>
    	</section>
    <?php endforeach;?>
</main>