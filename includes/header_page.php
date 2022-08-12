<header>
    <div class="topo d-flex">
        <div class="topo-conteudo d-flex">
            <div class="topo1 d-flex">
                <ul class="d-flex">
                    <li class="pesquisar">
                        <a class="botao-pesq" style="cursor: pointer;"><img src="imagens/header-footer/Search.png"></a>
                        <div class="div-pesquisar div-pesq">
                            <input type="" name="" placeholder="Buscar...">
                            <a class="botao-pesq1" style="cursor: pointer;"><img src="imagens/header-footer/Search.png">
                        </div>
                    </li>
                    <li>
                        <a href="https://www.instagram.com/brascloud/" target="_blank"><img src="imagens/header-footer/insta1.png"></a>
                    </li>
                    <li>
                        <a href="https://pt-br.facebook.com/brascloud/" target="_blank"><img src="imagens/header-footer/face1.png"></a>
                    </li>
                    <li>
                        <a href="https://mobile.twitter.com/bras_cloud" target="_blank"><img src="imagens/header-footer/twitter1.png"></a>
                    </li>
                    <li>
                        <a href="https://www.linkedin.com/company/9223844/admin/" target="_blank"><img src="imagens/header-footer/linke1.png"></a>
                    </li>
                </ul>
            </div>

            <div class="topo2 d-flex">
                <ul class="d-flex">
                    <li class="h-mobile">
                        <a class="text-white" href="tel:+5545991278107"><img src="imagens/header-footer/call1.png">(45) 9 9127-8107</a>
                    </li>
                    <li class="h-mobile">
                        <a class="text-white" href="mailto:comercial@brascloud.com.br"><img src="imagens/header-footer/mail1.png">comercial@brascloud.com.br</a>
                    </li>
                    <li class="topo-barra h-mobile"></li>
                    <li>
                        <a class="text-white" href="https://portal.brascloud.com.br/#/index/login" target="_blank"><?=$entrar?></a>
                    </li>
                    <li>
                        <span class="text-white d-flex">|</span>
                    </li>
                    <li>
                        <a class="text-white" href="https://portal.brascloud.com.br/#/index/signup" target="_blank"><?=$criar_conta?></a>
                    </li>
                    <li>
                        <div class="text-right col-7 col-md-6">
                            <div class="dropdown d-inline-block">
                                <a class="dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$lingua?>: <img src="<?=ENDERECO?>admin/files/idiomas/<?=$idioma['bandeira']?>" class="img-fluid"></a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <?php foreach($arrayIdiomas as $key => $idioma):?>
                                        <a class="dropdown-item" href="<?=ENDERECO.$idioma['urlamigavel']?>/<?=empty($_SESSION['extra'])?$_SESSION['idu']:'home'?>">
                                          <img src="<?=ENDERECO?>admin/files/idiomas/<?=$idioma['bandeira']?>" class="img-fluid">
                                        </a>
                                    <?php endforeach;?>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
        

    <div class="topo-menu">
        <div class="menu-conteudo d-flex" id="menu">
            <div class="logo d-flex">
                <a href="home"><img src="imagens/header-footer/logo1.png" alt="imagem"></a>
            </div>

            <div class="menu d-flex">
                <div class="menu-hamburguer">
                    <i class="fas fa-bars"></i>
                </div>
                <ul class="menu-list d-flex text-white">
                    <li class="close-mobile">
                        <i class="fas fa-times"></i>
                    </li>
                    <li class="d-flex">
                        <a href=""><?=$home?></a>
                        <span class="<?=$MODULO=='home'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/servicos"><?=$servico?></a>
                        <span class="<?=$MODULO=='servicos'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/suporte"><?=$suporte?></a>
                        <span class="<?=$MODULO=='suporte'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/inovacao"><?=$inovacao?></a>
                        <span class="<?=$MODULO=='inovacao'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/clientes"><?=$clientes?></a>
                        <span class="<?=$MODULO=='clientes'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/blog"><?=$blog?></a>
                        <span class="<?=$MODULO=='blog'?'borda-menu':''?>"></span>
                    </li>
                    <!-- <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/parceiros">PARCEIROS</a>
                        <span class="<?=$MODULO=='parceiros'?'borda-menu':''?>"></span>
                    </li> -->
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/brascloud">BRASCLOUD</a>
                        <span class="<?=$MODULO=='brascloud'?'borda-menu':''?>"></span>
                    </li>
                    <li class="d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/contato"><?=$contato?></a>
                        <span class="<?=$MODULO=='contato'?'borda-menu':''?>"></span>
                    </li>
                    <li class="menu-botao d-flex">
                        <a href="<?=$_SESSION['IDIOMA_URL']?>/planos"><?=$conhecer_planos?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>