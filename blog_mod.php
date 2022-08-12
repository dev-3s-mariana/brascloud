<main>  
    <section class="banner d-flex">
        <div class="bn-img d-flex">
            <img class="bn-d" src="imagens/blog/banner.png">
            <img class="bn-m" src="imagens/blog/banner-m.png">
        </div>
        <div class="bn-text d-flex">
            <h1><?=$blog_da_brascloud?></h1>
        </div>
    </section>

    <?php
       $searchGoogle = strstr($_SERVER['REQUEST_URI'], "search=");
       if(!empty($searchGoogle)):
          $searchGoogle = strstr($searchGoogle, "=");
          $searchGoogle = str_replace("=", "", $searchGoogle);
          if(!empty($searchGoogle) && ($searchGoogle == "1") || $searchGoogle == "2"):
    ?>
          <input type="hidden" id="procurar-ativo" value="<?=$searchGoogle?>">
    <?php
          endif;
       endif;
    ?>

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
            <div class="bg-card d-flex" style="position: relative;">
                <?php foreach($posts as $key => $p):?>
                    <div class="box-bg d-flex <?=$key%2==0?'margin-r':''?>">
                        <div class="bg-box d-flex">
                            <div class="card-img d-flex">
                                <img src="admin/files/blog/thumb_<?=$p['imagem']?>">
                            </div>
                            <div class="card-text d-flex">
                                <h3><?=$p['nome']?></h3>
                                <p>
                                    <?=$p['resumo']?>
                                </p>
                                <a href="<?=$_SESSION['IDIOMA_URL']?>/blog/<?=$p['urlrewrite']?>"><?=$continue_lendo?> <img src="imagens/blog/seta.png"></a>
                            </div>
                        </div>
                    </div>    
                <?php endforeach;?>
            </div>

            <?php include_once('includes/paginacao.php');?>

            <!-- <div class="paginacao d-flex">
                <div class="pag d-flex">
                    <a class="d-flex" href="">1</a>
                </div>
                <div class="pag d-flex">
                    <a class="d-flex" href="">2</a>
                </div>
                <div class="pag d-flex">
                    <a class="d-flex" href="">3</a>
                </div>
            </div> -->
        </div>   
    </section>
    
</main>