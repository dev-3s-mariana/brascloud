<main>
	<section class="banner d-flex">
		<div class="bn-img d-flex">
			<img src="admin/files/solucoes/<?=$vs['banner_topo']?>">
			<div class="bn-filtro"></div>
		</div>
		<div class="bn-text d-flex">
			<h1><?=$vs['nome']?></h1>
		</div>
	</section>

	<section class="conteudo1 d-flex">
		<div class="content1 d-flex">
			<div class="conteudo1-text d-flex text-center text-white">
				<h1><?=$vs['titulo']?></h1>
				<p>
					<?=nl2br($vs['descricao'])?>
				</p>
			</div>
			<div class="conteudo1-cards d-flex">
                <?php foreach($recursos as $key => $r):?>
    				<div class="conteudo1-box d-flex">
    					<div class="box1-img d-flex">
                            <?php if(!empty($r['imagem'])):?>
    						    <img src="admin/files/recursos/<?=$r['imagem']?>">
                            <?php else:?>
                                <i class="fas fa-<?=$r['nome_icone']?> fa-5x" style="color: #FE8001"></i>
                            <?php endif;?>
    					</div>
    					<div class="box1-text d-flex">
    						<h2 class="text-orange"><?=$r['nome']?></h2>
    						<div class="box1-hover">
    							<p>
    								<?=$r['descricao']?>
    							</p>
    						</div>
    					</div>
    				</div>
                <?php endforeach;?>
			</div>
		</div>
        <?php if(!empty($testes)):?>
    		<div class="conteudo1-content d-flex">
    			<div class="conteudo1-box2 d-flex">
    				<div class="conteudo1-title d-flex">
    					<h1 class="text-white">Aplicações</h1>
    				</div>
    				<div class="conteudo1-cards2 d-flex">
                        <?php foreach($testes as $key => $t):?>
        					<div class="box2-text">
        						<h2 class="text-orange"><?=$t['nome']?></h2>
        						<p class="text-white">
        							<?=$t['descricao']?>
        						</p>
        					</div>
                        <?php endforeach;?>
    				</div>
    			</div>
    			<div class="conteudo1-img d-flex">
    				<img class="img-m" src="imagens/servicos/img1.png">
    			</div>
    		</div>
        <?php endif;?>
	</section>
	
    <?php if(!empty($diferenciais)):?>
    	<section class="conteudo2 d-flex">
    		<div class="conteudo2-content d-flex">
    			<div class="conteudo2-img">
    				
    			</div>
    			<div class="conteudo2-texts">
    				<div class="text-1 d-flex">
    					<h1>Diferenciais</h1>
    					<p>
    						Nossos serviços atendem as mais diversas demandas de trabalho. O self-service permite maior liberdade para ajustes sem depender de terceiros, reduzindo os custos operacionais e de gestão de infraestrutura. Além de um suporte 24 horas, com atendimento em português. 
    					</p>
    				</div>
    				<div class="list-card d-flex">
                        <?php foreach($diferenciais as $key => $d):?>
        					<div class="list-box d-flex">
        						<img src="imagens/publica/check.png">
        						<span><?=$d['nome']?></span>
        					</div>
                        <?php endforeach;?>
    				</div>
    			</div>
    		</div>
    	</section>
    <?php endif;?>

    <?php if(!empty($tecnologias)):?>
    	<section id="tecnologia" class="tecnologias d-flex">
    		<div class="content1 d-flex">
    			<div class="tecnologias-text text-center d-flex">
    				<h1>Tecnologias suportadas</h1>
    				<p>
    					A Brascloud disponibiliza uma tecnologia de alto nível, que suporta os principais sistemas e se adapta às suas necessidades. O volume total de dados e o número de objetos que você pode armazenar são ilimitados. Mais segurança e eficiência para o seu negócio!
    				</p>
    			</div>
    			<div class="tecnologias-cards d-flex">
                    <?php foreach($tecnologias as $key => $tc):?>
        				<div class="tecnologias-box d-flex">
        					<img src="admin/files/tecnologias/<?=$tc['imagem']?>">
        				</div>
                    <?php endforeach;?>
    			</div>
    		</div>
    	</section>
    <?php endif;?>

	<section id="plano2" class="planos2 d-flex">
		<div class="content1 d-flex">
			<div class="planos2-filter d-flex">
				<div class="planos2-text text-center">
					<h1>Investimento</h1>
					<p>
						Valores menores que as principais clouds mundiais
					</p>	
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
						<h4>Mês</h4>
						<div class="toggle slide">
							<input class="filtro-2" id="c" type="checkbox" />
							<label for="c">
								<div class="card slide"></div>    
							</label>
						</div>
						<h4>Hora</h4>
					</div>
					<div class="barra"></div>
					<div class="select d-flex">
						<select class="filtro-3">
                            <option value="0">Zona</option>
							<option value="1">Zona 1</option>
							<option value="2">Zona 2</option>
							<option value="3">Zona 3</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="content1 content1-1 d-flex">
			<div class="planos2-table d-flex">
				<table>
                    <tr class="topo3">
                        <th class="borda-topo1">SERVIÇOS</th>
                        <th>VELOCIDADE</th>
                        <th>VCPU</th>
                        <th>MEMÓRIA</th>
                        <th>IOPS</th>
                        <th>ZONA SP01</th>
                        <th>ZONA SP02</th>
                        <th>ZONA RS01</th>
                        <th>DISCO</th>
                        <th class="th-preco borda-topo2">PREÇO/MÊS</th>
                    </tr>
                    <?php foreach($planos as $key => $p):?>
                        <tr class="td-padd <?=$key%2==1?'bg-color':''?> <?=$p['windows']==1?'windows':''?> <?=$p['linux']==1?'linux':''?> <?=$p['zona_sp01']==1?'zona_sp01':''?> <?=$p['zona_sp02']==1?'zona_sp02':''?> <?=$p['zona_rs01']==1?'zona_rs01':''?>">
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
				<h1>Você pode contratar até</h1>
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
							<h2>RESUMO DO PEDIDO</h2>
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
                                    <h3 id="resumo-precom" data-totalm="0.00">R$0,00 <span>/</span> mês</h3>
                                </div>
                                <div class="resumo-hora d-flex">
                                    <h3 id="resumo-precoh" data-totalh="0.00">R$0,00 <span>/</span> hora</h3>
                                </div>
                            </div>
							<div class="resumo-valor text-white text-center d-flex">
								<span>Valores podem sofrer variações</span>
							</div>
						</div>
					</div>
							
					<div class="resumo-botao text-orange text-center d-flex">
						<a class="abrir-publica" style="cursor: pointer;">CONTRATAR AGORA</a>
					</div>
				</div>
			</div>
		</div>
	</section>

    <?php if(!empty($outras_solucoes)):?>
    	<section id="solucoes-home" class="solucoes d-flex">
        	<div class="solucoes-content d-flex">
        		<div class="solucoes-text d-flex text-center">
    	    		<div class="solucoes-h d-flex">
    	    			<h1>Outros serviços</h1>
    	    		</div>
    	    	</div>
    	    	<div class="solucoes-slider">
                    <?php foreach($outras_solucoes as $key => $os):?>
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
        	</div>
        </section>
    <?php endif;?>

	<section id="comentarios" class="comentarios d-flex">
        <?php if(!empty($depoimento)):?>
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
    	    	<a class="botao-red abrir-publica">SEJA O PRÓXIMO CASE</a>
    	    </div>
        <?php endif;?>
	    <div class="text-bottom d-flex" style="display: none;">
            <div class="text-tags d-flex">
                <h3>Tags:</h3>
                <span><?=nl2br($seo_interna['keywords'])?></span>
            </div>
            <div class="text-compartilhe d-flex">
                <h3>Compartilhe:</h3>
                <a href="https://www.facebook.com/sharer.php?u=<?=ENDERECO;?>servicos/<?=$vs['urlrewrite'];?>" target="_blank"><img src="imagens/publica/face.png"></a>
                <a href="https://twitter.com/intent/tweet?text=<?=ENDERECO;?>servicos/<?=$vs['urlrewrite'];?>" target="_blank"><img src="imagens/publica/twitter.png"></a>
                <a><img src="imagens/publica/share.png"></a>
            </div>
        </div>
	</section>
</main>