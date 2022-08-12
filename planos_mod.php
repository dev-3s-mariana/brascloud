<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img class="bn-d" src="imagens/planos/banner.png">
            <img class="bn-m" src="imagens/planos/banner-m.png">
		</div>
		<div class="bn-text d-flex">
			<h1><?=$confira_nossos_planos?></h1>
		</div>
	</section>

	<section id="plano2" class="planos2 d-flex">
		<div class="content1 d-flex">
			<div class="planos2-filter d-flex">
				<div class="planos2-text text-center">
					<h1><?=$planos_titulo_1?></h1>
					<p><?=$planos_texto_1?></p>	
				</div>
				<div class="planos2-option d-flex">
					<div class="option1 d-flex">
						<div class="d-flex">
							<label class="container">
							  <input type="checkbox" class="filtro-1 l-check" value="linux">
							  <span class="checkmark"></span>
							  <span>Linux</span>
							</label>
						</div>
						<div class="d-flex">
							<label class="container">
							  <input type="checkbox" class="filtro-1 w-check" value="windows">
							  <span class="checkmark"></span>
							  <span>Windows</span>
							</label>
						</div>
					</div>
					<div class="barra"></div>
					<div class="option2 d-flex">
						<h4><?=$mes?></h4>
						<div class="toggle slide">
							<input class="filtro-2" id="c" type="checkbox" />
							<label for="c">
								<div class="card slide"></div>    
							</label>
						</div>
						<h4><?=$hora?></h4>
					</div>
					<div class="barra"></div>
					<div class="select d-flex">
						<select class="filtro-3">
                            <option value="0"><?=$option_zona?></option>
							<option value="1"><?=$option_zona_1?></option>
							<option value="2"><?=$option_zona_2?></option>
							<option value="3"><?=$option_zona_3?></option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="content1 content1-1 d-flex">
			<div class="planos2-table d-flex">
				<table>
					<tr class="topo3">
						<th class="borda-topo1"><?=$planos_cabecalho_servicos?></th>
						<th><?=$planos_cabecalho_velocidade?></th>
						<th><?=$planos_cabecalho_vcpu?></th>
						<th><?=$planos_cabecalho_memoria?></th>
						<th><?=$planos_cabecalho_iops?></th>
						<th><?=$planos_cabecalho_zona_sp01?></th>
						<th><?=$planos_cabecalho_zona_sp02?></th>
						<th><?=$planos_cabecalho_zona_rs01?></th>
						<th><?=$planos_cabecalho_disco?></th>
						<th class="th-preco borda-topo2"><?=$preco_mes?></th>
					</tr>
                    <?php foreach($planos as $key => $p):?>
    					<tr class="td-padd <?=$p['windows']==1?'windows':''?> <?=$p['linux']==1?'linux':''?> <?=$p['zona_sp01']==1?'zona_sp01':''?> <?=$p['zona_sp02']==1?'zona_sp02':''?> <?=$p['zona_rs01']==1?'zona_rs01':''?>">
    						<td class="plano-nome"><?=$p['nome']?></td>
    						<td class="plano-vel"><?=$p['velocidade']?></td>
    						<td class="plano-vcpu"><?=$p['vcpu']?></td>
    						<td class="plano-memoria"><?=$p['memoria']?></td>
    						<td class="plano-iops"><?=$p['iops']?></td>
    						<td class="plano-z01"><?=$p['zona_sp01']==1?'Ativo':'-'?></td>
    						<td class="plano-z02"><?=$p['zona_sp02']==1?'Ativo':'-'?></td>
    						<td class="plano-z03"><?=$p['zona_rs01']==1?'Ativo':'-'?></td>
    						<td class="plano-disco"><?=$p['disco']?></td>
    						<td class="color-red preco_hora" style="display: none;">R$ <?=$p['preco_hora']?></td>
                            <td class="color-red preco_mes">R$ <?=$p['preco_mes']?></td>
                            <td>
                                <label class="container">
                                    <input type="checkbox" class="selecionar-plano" data-precoh="<?=$p['preco_hora']?>" data-precom="<?=$p['preco_mes']?>" data-idplano="<?=$p['idplanos']?>">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
    					</tr>
                    <?php endforeach;?>
				</table>
			</div>			
		</div>
	</section>

	<section id="plano3" class="planos3 d-flex">
		<div class="content1 d-flex">
			<div class="plano3-title d-flex">
				<h1><?=$planos_titulo_2?></h1>
			</div>
			<div class="plano3-content d-flex">
				<div class="plano3-tabela d-flex">
	                <div class="plano3-direction d-flex">
                        <?php foreach($contratar as $key => $c):?>
                            <div class="plano3-option d-flex <?=($key+1) == count($servicos_adicionais)?'border-bottom':''?>">
                                <div class="plano3-item d-flex">
                                    <h3><?=$c['nome']?></h3>
                                    <img class="change-color" src="imagens/servicos/faq.png">
                                    <div class="plano3-hover">
                                    	<img src="imagens/servicos/info.png">
    	                                <div class="info-hover">
    	                                	<p>
    	                                		<?=$c['descricao']?>
    	                                	</p>
    	                                </div>
                                    </div>
                                </div>

                                <div class="descricao d-flex">
                                    <p>
                                    	<?=$c['texto']?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach;?>
	                </div>
				</div>

				<div class="plano3-resumo d-flex">
					<div class="plano3-coluna d-flex">
						<div class="resumo-title d-flex">
							<h2><?=$planos_resumo_cabecalho?></h2>
						</div>

						<div class="resumo-cards d-flex">
							<table class="resumo-box d-flex" id="resumo-pedido">
								<!-- <tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr>
								<tr class="d-flex">
									<td class="td-1 d-flex"><span class="text-white">Item</span></td>
									<td class="td-2 d-flex"><span class="text-orange">800</span></td>
									<td class="td-3 d-flex"><span class="text-white">RS0,54/h</span></td>
								</tr> -->
							</table>
							<div class="resumo-final text-white d-flex">
								<div class="resumo-mes d-flex">
									<h3 id="resumo-precom" data-totalm="0.00">R$0,00 <span>/</span> <?=$mes?></h3>
								</div>
								<div class="resumo-hora d-flex">
									<h3 id="resumo-precoh" data-totalh="0.00">R$0,00 <span>/</span> <?=$hora?></h3>
								</div>
							</div>
							<div class="resumo-valor text-white text-center d-flex">
								<span><?=$planos_resumo_observacao?></span>
							</div>
						</div>
					</div>
					<div class="resumo-botao text-orange text-center d-flex">
						<a class="abrir-publica" style="cursor: pointer;"><?=$contratar_agora?></a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="content1 d-flex">
			<div class="conteudo1-title d-flex">
				<h1><?=$planos_titulo_3?></h1>
			</div>
			<div class="conteudo1-text d-flex">
				<p><?=$planos_texto_3?></p>
			</div>
			<div class="conteudo1-table d-flex">
				<table>
					<tr class="topo1">
						<th class="borda-topo1"><?=$planos_cabecalho_servicos?></th>
						<th class="borda-topo2"><?=$planos_cabecalho_valores?></th>
					</tr>
                    <?php foreach($servicos_adicionais as $key => $sa):?>
					    <tr class="td-padd">
						    <td><?=$sa['nome']?></td>
						    <td class="color-td">R$ <?=$sa['preco']?></td>
					    </tr>
                    <?php endforeach;?>
				</table>
			</div>
		</div>
	</section>

	<section id="tecnologia" class="tecnologias d-flex">
		<div class="content1 d-flex">
			<div class="tecnologias-text text-center d-flex">
				<h1><?=$planos_titulo_4?></h1>
				<p><?=$planos_texto_4?></p>
			</div>
			<div class="tecnologias-cards d-flex">
                <?php foreach($tecnologias as $key => $t):?>
    				<div class="tecnologias-box d-flex">
    					<img src="admin/files/tecnologias/<?=$t['imagem']?>">
    				</div>
                <?php endforeach;?>
			</div>
			<div class="tecnologia-img d-flex">
				<img src="imagens/planos/img1.png">
			</div>
		</div>
	</section>

	<?php if(!empty($solucoes)):?>
    	<section id="solucoes-home" class="solucoes d-flex">
        	<div class="solucoes-content d-flex">
        		<div class="solucoes-text d-flex text-center">
    	    		<div class="solucoes-h d-flex">
    	    			<h1><?=$planos_titulo_5?></h1>
    	    		</div>
    	    	</div>
    	    	<div class="solucoes-slider">
                    <?php foreach($solucoes as $key => $os):?>
        	    		<div>
        	    			<div class="so-slide d-flex">
                                <a class="box-link" href="<?=$_SESSION['IDIOMA_URL']?>/servicos/<?=$os['urlrewrite']?>"></a>
        	    				<div class="so-img d-flex">
                                    <?php if(strlen($os['icone']) > 4):?>
        	    					    <img src="admin/files/solucoes/<?=$os['icone']?>">
                                    <?php else:?>
                                        <i style="color: #c4c4c4" class="fas fa-<?=$os['icone_name']?> fa-5x"></i>
                                    <?php endif;?>
        	    				</div>
        	    				<div class="so-text d-flex text-center">
        	    					<h2><?=$os['nome']?></h2>
        	    				</div>
        	    			</div>
        	    		</div>
                    <?php endforeach;?>
    	    	</div>
    	    	<div class="botao-padrao d-flex">
			    	<a href="<?=$_SESSION['IDIOMA_URL']?>/servicos" class="botao-red">Veja outros serviços</a>
			    </div>
        	</div>
        </section>
    <?php endif;?>

	<section id="comentarios" class="comentarios d-flex">
        <?php if(!empty($depoimento)):?>
    		<div class="comentarios-titulo d-flex text-center">
    			<h1><?=$planos_titulo_6?></h1>
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
        <?php endif;?>
		<div class="botao-padrao d-flex" style="display: none;">
	    	<a class="botao-red abrir-publica"><?=$seja_o_proximo_case?></a>
	    </div>
	    <div class="text-bottom d-flex" style="display: none;">
            <div class="text-tags d-flex">
                <h3>Tags:</h3>
                <span><?=nl2br($seo_interna['keywords'])?></span>
            </div>
            <div class="text-compartilhe d-flex">
                <h3><?=$compartilhe?></h3>
                <a href="https://www.facebook.com/sharer.php?u=<?=ENDERECO;?>planos" target="_blank"><img src="imagens/publica/face.png"></a>
                <a href="https://twitter.com/intent/tweet?text=<?=ENDERECO;?>planos" target="_blank"><img src="imagens/publica/twitter.png"></a>
                <a><img src="imagens/publica/share.png"></a>
            </div>
        </div>
	</section>
</main>