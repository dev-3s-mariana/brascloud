<main>  
    <section class="banner">
        <div class="d-flex bn-filtro">
            <img src="imagens/sobre/filtro.png">
        </div>
        <div class="d-flex bn-text">
            <h2>OverView</h2>
        </div>
        <div class="d-flex bn-redes">
            <a href=""><img src="imagens/home/home/face1.png"></a>
            <a href=""><img src="imagens/home/home/insta1.png"></a>
            <a href=""><img src="imagens/home/home/whats1.png"></a>
        </div>
    </section>

    <section class="blog d-flex">
        <div class="blog-coluna d-flex">
            <div class="bg-pesquisar d-flex">
                <button class="d-flex"><img src="imagens/blog/search.png"></button>
                <input type="" name="" placeholder="Pesquisar...">
                
            </div>
            <div class="bg-categoria d-flex">
                <div class="categoria-title">
                    <h3>Categorias</h3>
                </div>
                <?php foreach($categorias as $key => $c):?>
                    <div>
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$c['urlrewrite']?>"><?=$c['nome']?> <span>(<?=str_pad($c['qtd_count'], 2, '0', STR_PAD_LEFT)?>)</span></a>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="bg-tags d-flex">
                <div class="tags-title d-flex">
                    <h3>Tags</h3>
                </div>
                <?php foreach($tags as $key => $t):?>
                    <div class="bg-div">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$t['urlrewrite']?>"><?=$t['titulo']?></a>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="bg-arquivos d-flex">
                <h3>Arquivos</h3>
                <?php foreach($arquivos as $key => $a):?>
                    <div>
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/arquivos/<?=$a['ano_']?>-<?=$a['mes_']?>"><?=utf8_decode($a['mes_form'])?> <?=$a['ano_']?> <span>(<?=str_pad($a['qtd_post'], 2, '0', STR_PAD_LEFT)?>)</span></a>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="bg-news d-flex">
                <form class="news-content d-flex" id="form-newsletter">
                    <h3>Fique por dentro</h3>
                    <div>
                        <input class="required" type="text" name="nome" placeholder="Nome">
                    </div>
                    <div>
                        <input class="required" type="text" name="email" placeholder="E-mail">
                    </div>
                    <div id="enviar-newsletter" class="botao-a d-flex" style="cursor: pointer;">
                        <a>CADASTRE-SE</a>
                    </div>
                </form>   
            </div>
        </div>

        <div class="blog-content d-flex">
            <?php foreach($posts as $key => $p):?>
                <div class="bg-card d-flex" style="position: relative;">
                    <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$p['urlrewrite']?>" class="box-link"></a>
                    <div class="card-img d-flex">
                        <img src="admin/files/blog/<?=$p['imagem']?>" height="378">
                    </div>
                    <div class="card-text d-flex">
                        <h3><?=$p['nome']?></h3>
                        <p>
                            <?=$p['resumo']?>
                        </p>
                    </div>
                </div>
            <?php endforeach;?>

            <?php include_once('includes/paginacao.php');?>

           <!--  <div class="paginacao d-flex">
                <a href=""><img src="imagens/blog/left.png"></a>
                <div class="pag d-flex">
                    <a class="d-flex" href="">1</a>
                </div>
                <div class="pag d-flex">
                    <a class="d-flex" href="">2</a>
                </div>
                <div class="pag d-flex">
                    <a class="d-flex" href="">3</a>
                </div>
                <a href=""><img src="imagens/blog/right.png"></a>
            </div> -->
        </div>

        
    </section>
    
</main>