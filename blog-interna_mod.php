<main>  
    <section class="banner d-flex">
        <div class="bn-img d-flex">
            <img class="bn-d" src="imagens/blog/banner.png">
            <img class="bn-m" src="imagens/servicos/banner-m.png">
        </div>
        <div class="bn-text d-flex">
            <h1><?=$blog_da_brascloud?></h1>
        </div>
    </section>

    <section class="content1 d-flex">
        <div class="blog-coluna d-flex">
            <form class="bg-pesquisar d-flex" method="get" action="blog">
                <button class="d-flex"><img src="imagens/blog/search.png"></button>
                <input type="text" name="q" placeholder="Pesquisar...">
                <input type="hidden" name="search" value="1">
            </form>
            <div class="bg-categoria d-flex">
                <div class="categoria-title">
                    <h3><?=$categorias?></h3>
                </div>
                <div>
                    <?php foreach($categorias as $key => $c):?>
                        <div class="div-a">
                            <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$c['urlrewrite']?>"><?=$c['nome']?> <span>(<?=$c['qtd_count']<10?'0'.$c['qtd_count']:$c['qtd_count']?>)</span></a>
                        </div>      
                    <?php endforeach;?>
                </div>
            </div>
            <div class="bg-tags d-flex">
                <div class="tags-title d-flex">
                    <h3><?=$tags?></h3>
                </div>
                <div class="bg-div d-flex">
                    <?php foreach($tags as $key => $t):?>
                        <div class="div-tag">
                            <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$t['urlrewrite']?>"><?=$t['titulo']?></a>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            <div class="bg-arquivos d-flex">
                <h3><?=$arquivos?></h3>
                <div>
                    <?php foreach($arquivos as $key => $a):?>
                        <div class="div-a">
                            <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$a['ano_']?>-<?=$a['mes_']?>"><?=utf8_decode($a['mes_form'])?> <?=$a['ano_']?> <span>(<?=$c['qtd_post']<10?'0'.$c['qtd_count']:$c['qtd_count']?>)</span></a>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>

        <div class="blog-content d-flex">
            <div class="blog-titulo d-flex">
                <h2><?=$p['nome']?></h2>
            </div>
            <div class="blog-subtitulo d-flex">
                <div class="d-flex">
                    <img src="imagens/blog/Calendar.png">
                    <h3><?=$p['dia_']?> <?=utf8_decode($p['mes_form'])?> <?=$p['ano_']?></h3>
                </div>  
                <!-- <div class="d-flex">
                    <img src="imagens/blog/Comment.png">
                    <h3>12 Comentários</h3>
                </div> -->        
            </div>
            <div class="blog-text d-flex">
                <div class="text-img d-flex">
                    <img src="admin/files/blog/<?=$p['imagem']?>">
                </div>
                <div class="text-p d-flex">
                    <?=$p['descricao']?>
                    <!-- <p>
                        To support performance in playing, a professional e-sport player must use the right device. To support performance in playing, a professional e-sport player must use the right e-sport-support smartphone.<br><br>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>

                    <div class="text-p-bg d-flex">
                        <p>
                            “ lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ”<br>
                            <span>- Sam Alabama</span>
                        </p>
                    </div>
                    <div class="text-p-img d-flex">
                        <div class="d-flex">
                            <img src="imagens/blog/img5.png">
                        </div>
                        <div class="d-flex">
                            <img src="imagens/blog/img4.png">
                        </div>
                    </div>
                        
                    <p>
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.<br><br>
                        Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?
                    </p> -->
                </div>
            </div>
            <div class="text-bottom d-flex">
                <div class="text-tags d-flex">
                    <h3>Tags:</h3>
                    <?php
                        $arrTags = explode(',', $p['tags']);
                    ?>
                    <?php foreach($arrTags as $key => $at):?>
                        <?php $tag = buscaBlog_tags(array('idblog_tags'=>$at));?>
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$tag[0]['urlrewrite']?>"><?=$tag[0]['titulo']?></a>
                        <?php if($key != (count($arrTags)-1)):?>
                            <span>,</span>
                        <?php endif;?>
                    <?php endforeach;?>
                </div>
                <div class="text-compartilhe d-flex">
                    <h3><?=$compartilhe?></h3>
                    <a href="https://www.facebook.com/sharer.php?u=<?=ENDERECO;?>blog/<?=$p['urlrewrite'];?>" target="_blank"><img src="imagens/blog/face.png"></a>
                    <a href="https://twitter.com/intent/tweet?text=<?=ENDERECO;?>blog/<?=$p['urlrewrite'];?>" target="_blank"><img src="imagens/blog/twitter.png"></a>
                    <a href=""><img src="imagens/blog/share.png"></a>
                </div>
            </div>
            
            <!-- <div class="comentarios d-flex">
                <div class="comentarios-qtd d-flex">
                    <h3><span>6</span> Comentarios</h3>
                </div>
                <div class="coments d-flex">
                    <div class="coments-user d-flex">
                        <div class="coments-img d-flex ">
                            <img src="imagens/blog-i/user.png">
                        </div>
                        <div class="coments-txt d-flex">
                            <h4>Lorem Ipsum Brasil</h4>
                            <h3>29/10/2021</h3>
                        </div>
                    </div>
                    <div class="d-flex">
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                        </p>
                    </div>
                    <div class="d-flex">
                        <a href="">Responder comentário</a>
                    </div>
                </div>
                <div class="comentarios-user d-flex"> 
                    <form class="area-texto d-flex" id="form-blog-comentario">
                        <div class="img-div d-flex">
                            <div class="cc-img d-flex">
                                <label for="anexar-imagem">
                                    <img id="imagem-upload" src="imagens/blog-i/user.png" width="28" alt="imagem" pagespeed_url_hash="619827108" onload="pagespeed.CriticalImages.checkImageForCriticality(this);">
                                    <input type="file" hidden="" id="anexar-imagem" name="imagem">
                                </label>
                            </div>
                        </div>
                        <div class="input-div d-flex">
                            <input type="text" name="nome" class="required" placeholder="Nome">
                            <input type="text" name="email" class="required" placeholder="E-mail">
                        </div>

                        <div class="input-text d-flex">
                            <input type="hidden" name="idblog_post" value="8">
                            <textarea id="comment-textarea" name="comentario" placeholder="Escreva aqui seu comentário..." class="required"></textarea>
                        </div>
                        <button id="enviar-blog-comentario">ENVIAR COMENTÁRIO</button>
                    </form>
                </div>
            </div> -->
        </div>
    </section>
    
</main>