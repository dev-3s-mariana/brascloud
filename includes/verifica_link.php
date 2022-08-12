<?php
    include_once __DIR__.'/../admin/includes/functions.php';
    include_once __DIR__.'/../admin/blog_post_class.php';
    include_once __DIR__.'/../admin/blog_categoria_class.php';
    include_once __DIR__.'/../admin/blog_tags_class.php';
    include_once __DIR__.'/../admin/blog_comentarios_class.php';
    include_once __DIR__.'/../admin/banner_class.php';
    include_once __DIR__.'/../admin/seo_class.php';
    include_once __DIR__.'/../admin/integracoes_class.php';
    include_once __DIR__.'/../admin/solucoes_class.php';
    include_once __DIR__.'/../admin/planos_class.php';
    include_once __DIR__.'/../admin/parceiros_class.php';
    include_once __DIR__.'/../admin/tecnologias_class.php';
    include_once __DIR__.'/../admin/servicos_adicionais_class.php';
    include_once __DIR__.'/../admin/depoimento_class.php';
    include_once __DIR__.'/../admin/landing_page_class.php';
    include_once __DIR__.'/../admin/categoria_suporte_class.php';
    include_once __DIR__.'/../admin/suporte_class.php';
    include_once __DIR__.'/../admin/faq_class.php';
    include_once __DIR__.'/../admin/equipe_class.php';
    include_once __DIR__.'/../admin/segmento_class.php';
    include_once __DIR__.'/../admin/cliente_class.php';
    include_once __DIR__.'/../admin/projeto_class.php';
    include_once __DIR__.'/../admin/timeline_class.php';
    include_once __DIR__.'/../admin/politicas_class.php';
    include_once __DIR__.'/../admin/links_class.php';
    include_once __DIR__.'/../admin/contratar_class.php';

    $integracoes = buscaIntegracoes(array('ordem'=>'idintegracoes','dir'=>'asc'));
    if(!empty($integracoes)){
        $googleApi = $integracoes[0]['token'];
        $googleMaps = $integracoes[1]['token'];
    }

    $MODULO = strtolower($MODULO);
    $MODULO = empty($MODULO)?'home':$MODULO;

    //header
    $lingua = traduzir($_SESSION['IDIDIOMA'], 'lingua');
    $criar_conta = traduzir($_SESSION['IDIDIOMA'], 'criar_conta');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');

    //menu
    $home = traduzir($_SESSION['IDIDIOMA'], 'home');
    $servico = traduzir($_SESSION['IDIDIOMA'], 'servico');
    $suporte = traduzir($_SESSION['IDIDIOMA'], 'suporte');
    $inovacao = traduzir($_SESSION['IDIDIOMA'], 'inovacao');
    $clientes = traduzir($_SESSION['IDIDIOMA'], 'clientes');
    $blog = traduzir($_SESSION['IDIDIOMA'], 'blog');
    $contato = traduzir($_SESSION['IDIDIOMA'], 'contato');
    $conhecer_planos = traduzir($_SESSION['IDIDIOMA'], 'conhecer_planos');

    //footer
    $clique_e_veja_o = traduzir($_SESSION['IDIDIOMA'], 'clique_e_veja_o');
    $novidades_titulo = traduzir($_SESSION['IDIDIOMA'], 'novidades_titulo');
    $novidades_texto = traduzir($_SESSION['IDIDIOMA'], 'novidades_texto');
    $novidades_email_placeholder = traduzir($_SESSION['IDIDIOMA'], 'novidades_email_placeholder');

    //404
    $pagina_nao_encontrada = traduzir($_SESSION['IDIDIOMA'], 'pagina_nao_encontrada');

    //home
    $home_titulo_pequeno_1 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_pequeno_1');
    $home_titulo_grande_1 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_grande_1');
    $home_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'home_texto_1');
    $home_titulo_pequeno_2 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_pequeno_2');
    $home_titulo_grande_2 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_grande_2');
    $home_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'home_texto_2');
    $saiba_mais = traduzir($_SESSION['IDIDIOMA'], 'saiba_mais');
    $home_titulo_pequeno_3 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_pequeno_3');
    $home_titulo_grande_3 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_grande_3');
    $home_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'home_texto_3');
    $home_card_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'home_card_titulo_1');
    $home_card_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'home_card_texto_1');
    $home_card_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'home_card_titulo_2');
    $home_card_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'home_card_texto_2');
    $home_card_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'home_card_titulo_3');
    $home_card_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'home_card_texto_3');
    $home_card_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'home_card_titulo_4');
    $home_card_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'home_card_texto_4');
    $home_titulo_grande_4 = traduzir($_SESSION['IDIDIOMA'], 'home_titulo_grande_4');
    $beneficios_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_titulo_1');
    $beneficios_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_texto_1');
    $beneficios_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_titulo_2');
    $beneficios_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_texto_2');
    $beneficios_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_titulo_3');
    $beneficios_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_texto_3');
    $beneficios_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_titulo_4');
    $beneficios_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'beneficios_texto_4');
    $nossos_planos = traduzir($_SESSION['IDIDIOMA'], 'nossos_planos');
    $crie_uma_conta = traduzir($_SESSION['IDIDIOMA'], 'crie_uma_conta');
    $home_planos_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'home_planos_titulo_1');
    $home_planos_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'home_planos_titulo_2');
    $home_planos_texto = traduzir($_SESSION['IDIDIOMA'], 'home_planos_texto');
    $selos_e_partners = traduzir($_SESSION['IDIDIOMA'], 'selos_e_partners');

    //serviços
    $nossos_servicos = traduzir($_SESSION['IDIDIOMA'], 'nossos_servicos');

    //suporte
    $suporte_brascloud = traduzir($_SESSION['IDIDIOMA'], 'suporte_brascloud');
    $veja_a_wiki = traduzir($_SESSION['IDIDIOMA'], 'veja_a_wiki');
    $duvidas_titulo = traduzir($_SESSION['IDIDIOMA'], 'duvidas_titulo');
    $duvidas_nome = traduzir($_SESSION['IDIDIOMA'], 'duvidas_nome');
    $duvidas_telefone = traduzir($_SESSION['IDIDIOMA'], 'duvidas_telefone');
    $duvidas_email = traduzir($_SESSION['IDIDIOMA'], 'duvidas_email');
    $duvidas_assunto = traduzir($_SESSION['IDIDIOMA'], 'duvidas_assunto');
    $duvidas_mensagem = traduzir($_SESSION['IDIDIOMA'], 'duvidas_mensagem');
    $enviar_duvida = traduzir($_SESSION['IDIDIOMA'], 'enviar_duvida');

    //inovação
    $inovacao_e_tecnologias = traduzir($_SESSION['IDIDIOMA'], 'inovacao_e_tecnologias');
    $inovacao_titulo = traduzir($_SESSION['IDIDIOMA'], 'inovacao_titulo');
    $inovacao_texto = traduzir($_SESSION['IDIDIOMA'], 'inovacao_texto');
    $inovacao_card_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_titulo_1');
    $inovacao_card_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_texto_1');
    $inovacao_card_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_titulo_2');
    $inovacao_card_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_texto_2');
    $inovacao_card_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_titulo_3');
    $inovacao_card_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_texto_3');
    $inovacao_card_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_titulo_4');
    $inovacao_card_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'inovacao_card_texto_4');
    $arquitetos = traduzir($_SESSION['IDIDIOMA'], 'arquitetos');
    $converse_com_nossos_arquitetos = traduzir($_SESSION['IDIDIOMA'], 'converse_com_nossos_arquitetos');
    $vantagens_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_1');
    $vantagens_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_2');
    $vantagens_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_3');
    $vantagens_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_4');
    $vantagens_titulo_5 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_5');
    $vantagens_titulo_6 = traduzir($_SESSION['IDIDIOMA'], 'vantagens_titulo_6');
    $nossos_pilares = traduzir($_SESSION['IDIDIOMA'], 'nossos_pilares');
    $pilares_card_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_titulo_1');
    $pilares_card_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_texto_1');
    $pilares_card_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_titulo_2');
    $pilares_card_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_texto_2');
    $pilares_card_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_titulo_3');
    $pilares_card_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_texto_3');
    $pilares_card_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_titulo_4');
    $pilares_card_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'pilares_card_texto_4');
    $outros_servicos = traduzir($_SESSION['IDIDIOMA'], 'outros_servicos');
    $ver_todas_as_solucoes = traduzir($_SESSION['IDIDIOMA'], 'ver_todas_as_solucoes');

    //clientes
    $nossos_clientes = traduzir($_SESSION['IDIDIOMA'], 'nossos_clientes');
    $clientes_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'clientes_titulo_1');
    $clientes_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'clientes_titulo_2');
    $clientes_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'clientes_titulo_3');
    $seja_o_proximo_case = traduzir($_SESSION['IDIDIOMA'], 'seja_o_proximo_case');

    //blog
    $blog_da_brascloud = traduzir($_SESSION['IDIDIOMA'], 'blog_da_brascloud');
    $categorias = traduzir($_SESSION['IDIDIOMA'], 'categorias');
    $tags = traduzir($_SESSION['IDIDIOMA'], 'tags');
    $arquivos = traduzir($_SESSION['IDIDIOMA'], 'arquivos');
    $continue_lendo = traduzir($_SESSION['IDIDIOMA'], 'continue_lendo');
    $compartilhe = traduzir($_SESSION['IDIDIOMA'], 'compartilhe');

    //brascloud
    $sobre_a_brascloud = traduzir($_SESSION['IDIDIOMA'], 'sobre_a_brascloud');
    $sobre_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'sobre_texto_1');
    $sobre_card_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_titulo_1');
    $sobre_card_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_texto_1');
    $sobre_card_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_titulo_2');
    $sobre_card_texto_2 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_texto_2');
    $sobre_card_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_titulo_3');
    $sobre_card_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_texto_3');
    $sobre_card_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_titulo_4');
    $sobre_card_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'sobre_card_texto_4');
    $termos_legais_titulo = traduzir($_SESSION['IDIDIOMA'], 'termos_legais_titulo');
    $termos_legais_texto = traduzir($_SESSION['IDIDIOMA'], 'termos_legais_texto');
    $continuar_lendo = traduzir($_SESSION['IDIDIOMA'], 'continuar_lendo');

    //contato
    $fale_com_a_gente_agora = traduzir($_SESSION['IDIDIOMA'], 'fale_com_a_gente_agora');
    $enviar_contato = traduzir($_SESSION['IDIDIOMA'], 'enviar_contato');
    $info_escritorio = traduzir($_SESSION['IDIDIOMA'], 'info_escritorio');
    $info_email = traduzir($_SESSION['IDIDIOMA'], 'info_email');
    $info_telefone_comercial = traduzir($_SESSION['IDIDIOMA'], 'info_telefone_comercial');
    $zonas_de_disponibilidade = traduzir($_SESSION['IDIDIOMA'], 'zonas_de_disponibilidade');
    $regioes_em_breve = traduzir($_SESSION['IDIDIOMA'], 'regioes_em_breve');

    //planos
    $confira_nossos_planos = traduzir($_SESSION['IDIDIOMA'], 'confira_nossos_planos');
    $planos_titulo_1 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_1');
    $planos_texto_1 = traduzir($_SESSION['IDIDIOMA'], 'planos_texto_1');
    $planos_titulo_2 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_2');
    $planos_titulo_3 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_3');
    $planos_texto_3 = traduzir($_SESSION['IDIDIOMA'], 'planos_texto_3');
    $planos_titulo_4 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_4');
    $planos_texto_4 = traduzir($_SESSION['IDIDIOMA'], 'planos_texto_4');
    $planos_titulo_5 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_5');
    $planos_titulo_6 = traduzir($_SESSION['IDIDIOMA'], 'planos_titulo_6');
    $mes = traduzir($_SESSION['IDIDIOMA'], 'mes');
    $hora = traduzir($_SESSION['IDIDIOMA'], 'hora');
    $preco_mes = traduzir($_SESSION['IDIDIOMA'], 'preco_mes');
    $option_zona = traduzir($_SESSION['IDIDIOMA'], 'option_zona');
    $option_zona_1 = traduzir($_SESSION['IDIDIOMA'], 'option_zona_1');
    $option_zona_2 = traduzir($_SESSION['IDIDIOMA'], 'option_zona_2');
    $option_zona_3 = traduzir($_SESSION['IDIDIOMA'], 'option_zona_3');
    $planos_cabecalho_servicos = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_servicos');
    $planos_cabecalho_velocidade = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_velocidade');
    $planos_cabecalho_vcpu = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_vcpu');
    $planos_cabecalho_memoria = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_memoria');
    $planos_cabecalho_iops = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_iops');
    $planos_cabecalho_zona_sp01 = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_zona_sp01');
    $planos_cabecalho_zona_sp02 = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_zona_sp02');
    $planos_cabecalho_zona_rs01 = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_zona_rs01');
    $planos_cabecalho_disco = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_disco');
    $planos_resumo_cabecalho = traduzir($_SESSION['IDIDIOMA'], 'planos_resumo_cabecalho');
    $planos_resumo_observacao = traduzir($_SESSION['IDIDIOMA'], 'planos_resumo_observacao');
    $contratar_agora = traduzir($_SESSION['IDIDIOMA'], 'contratar_agora');
    $planos_cabecalho_valores = traduzir($_SESSION['IDIDIOMA'], 'planos_cabecalho_valores');

    
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');
    $entrar = traduzir($_SESSION['IDIDIOMA'], 'entrar');

    $REQUEST_P = empty($_REQUEST['p'])?'home':$_REQUEST['p'];

    if ($MODULO == 'home'){
        $banner = buscaBanner(array('status'=>1, 'ordem'=>'ordem', 'dir'=>'asc'));
        $maisRecentes = buscaBlog_post(array('status'=>'1', 'ordem'=>'data_hora', 'dir'=>'desc'));
        $solucoes = buscaSolucoes(array('status'=>1));
        $planos = buscaPlanos(array('status'=>1));
        $parceiros = buscaParceiros(array('status'=>'A'));
    }
    elseif($MODULO == 'contato'){

    }
    elseif($MODULO == 'lgpd'){
        $politicas = buscaPoliticas(array('status'=>'A', 'ordem'=>'ordem', 'dir'=>'asc'));
        $faq = buscaFaq(array('status'=>'A', 'ordem'=>'ordem', 'dir'=>'asc', 'idsuporte'=>0));
        $links = buscaLinks();
    }
    elseif($MODULO == 'planos'){
        $planos = buscaPlanos(array('status'=>1));
        $servicos_adicionais = buscaServicos_adicionais();
        $contratar = buscaContratar();
        $tecnologias = buscaTecnologias(array('status'=>'A'));
        $depoimento = buscaDepoimento(array('status'=>1, 'ordem'=>'ordem', 'dir'=>'asc'));
        $solucoes = buscaSolucoes(array('status'=>1));
        $seo_interna = buscaSeo(array('urlrewrite'=>$REQUEST_P));
        if(!empty($seo_interna)){
            $seo_interna = $seo_interna[0];
        }
    }
    elseif($MODULO == 'brascloud'){
        $timeline = buscaTimeline(array('status'=>1, 'ordem'=>'ano', 'dir'=>'asc'));
        $parceiros = buscaParceiros(array('status'=>'A'));
    }
    elseif($MODULO == 'clientes'){
        $segmento = buscaSegmento();
        $clientes = buscaCliente(array('status'=>'A'));
        $depoimento = buscaDepoimento(array('status'=>1, 'ordem'=>'ordem', 'dir'=>'asc'));
    }
    elseif($MODULO == 'inovacao'){
        $equipe = buscaEquipe(array('status'=>1));
        $solucoes = buscaSolucoes(array('status'=>1));
    }
    elseif($MODULO == 'suporte'){
        $categoria_suporte = buscaCategoria_suporte();
        $suporte = buscaSuporte();
        $idcategoria_suporte = $categoria_suporte[0]['idcategoria_suporte'];
    }
    elseif($MODULO == 'publica'){
        $landing_page = buscaLanding_page();
        $lp = $landing_page[0];
        $itens = buscaItens(array('idlanding_page'=>$lp['idlanding_page']));
        $difs = buscaDifs(array('idlanding_page'=>$lp['idlanding_page']));
        $indicacoes = buscaIndicacoes(array('idlanding_page'=>$lp['idlanding_page']));
        $abrangencia = buscaAbrangencia(array('idlanding_page'=>$lp['idlanding_page']));
        $depoimento = buscaDepoimento(array('status'=>1, 'ordem'=>'ordem', 'dir'=>'asc'));
    }
    elseif($MODULO == 'servicos'){
        $solucoes = buscaSolucoes(array('status'=>1));
        $verifica_solucoes = buscaSolucoes(array('status'=>1, 'urlrewrite'=>$_SESSION['idu']));

        if(!empty($verifica_solucoes)){
            $MODULO = 'servicos-interna';
            $seo_interna = buscaSeo(array('urlrewrite'=>$REQUEST_P));
            if(!empty($seo_interna)){
                $seo_interna = $seo_interna[0];
            }
            $vs = $verifica_solucoes[0];
            $seo_title = $vs['nome'];
            $recursos = buscaRecursos(array('idsolucoes'=>$vs['idsolucoes'], 'ordem'=>'ordem', 'dir'=>'asc'));
            $testes = buscaTestes(array('idsolucoes'=>$vs['idsolucoes'], 'ordem'=>'ordem', 'dir'=>'asc'));
            $diferenciais = buscaDiferenciais(array('idsolucoes'=>$vs['idsolucoes'], 'ordem'=>'ordem', 'dir'=>'asc'));
            $tecnologias = buscaTecnologias(array('status'=>'A'));
            $planos = buscaPlanos(array('status'=>1));
            $servicos_adicionais = buscaServicos_adicionais();
            $contratar = buscaContratar();
            $outras_solucoes = buscaSolucoes(array('status'=>1, 'not_idsolucoes'=>$vs['idsolucoes']));
            $depoimento = buscaDepoimento(array('status'=>1, 'ordem'=>'ordem', 'dir'=>'asc'));
        }
    }
    elseif($MODULO == 'blog'){
        $limit = 6;
        $pag = 0;
        $interna = false;
        $urlrewrite = "";
        $maisLidos = buscaBlog_post(array('status'=>'1', 'ordem'=>'contador', 'dir'=>'desc', 'limit'=>$limit));
        $arquivos_blog = buscaBlog_post(array('busca4data'=>true));
        $verifica_post = buscaBlog_post(array('urlrewrite'=>$_SESSION['idu']));
        $verfica_categoria_post = buscaBlog_categoria(array('urlrewrite'=>$_SESSION['idu']));
        $verifica_tags_post = buscaBlog_tags(array('urlrewrite'=>$_SESSION['idu']));
    
        //==Subitens do Menu Blog ==//
        $categorias = buscaBlog_categoria(array('status' => 1));
        $tags = buscaBlog_tags(array('status' => 1));
        $categoria_blog = buscaBlog_categoria(array('inner_post'=>true));
        $maisLidos = buscaBlog_post(array('status'=>'1', 'ordem'=>'contador', 'dir'=>'desc', 'limit'=>$limit));
        $arquivos = buscaBlog_post(array('busca4data'=>true));
    
        $totalBlog = array('status'=>'1','ordem'=>'data_hora asc', 'limit'=>$limit,'totalRecords'=>true, 'pagina'=>$pag, '    totalRecords'=>true);
    
        if (!empty($verifica_post) && !empty($_SESSION['idu']) && empty($verfica_categoria_post)){
            $MODULO = 'blog-interna';
            $interna = true;
            $post = buscaBlog_post(array('urlrewrite'=>$_SESSION['idu']));
            $p = $post[0];
            $seo_title = $p['nome'];
            if(isset($p['idblog_post'])){
                $postGaleria = buscaBlog_post_imagem(array('idblog_post'=>$p['idblog_post']));
            }else{
                $postGaleria = array();
            }
            UpdateContador(array('idblog_post'=> $p['idblog_post']));
            $comen = buscaBlog_comentarios(array('idblog_post'=>$p['idblog_post'], 'status'=>2));
            $relacionados = buscaBlog_post(array('limit'=>4, 'not_idblog_post'=>$p['idblog_post'], 'status'=>'1', 'idblog_categoria'=>$p['idblog_categoria']));
            $galeria = buscaBlog_post_imagem(array('idblog_post' => $p['idblog_post']));
        }
        if(!empty($_SESSION['idu']) && is_numeric($_SESSION['idu'])){
            if($_SESSION['idu'] == 1)
            {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location:".ENDERECO."blog");
            }
            $pag = $_SESSION['idu'] - 1;
        }else if($_SESSION['idu'] == 'arquivos'){
                $interna = false;
                $MODULO = 'blog';
        }
        if(!$interna){
            if(isset($_POST['busca_blog'])){
                $posts = buscaBlog_post(array('status'=>'1','ordem'=>'data_hora', 'dir'=>'DESC', 'limit'=>$limit, 'nome'=>$_POST['    busca_blog'], 'pagina'=>$pag));
                $totalBlog['nome'] = $_POST['busca_blog'];
                $termoBusca = $_POST['busca_blog'];
            }
            else if (!empty($_SESSION['idu']) && !empty($verfica_categoria_post)){
                $vcp =  $verfica_categoria_post[0];
                $pag = !empty($_SESSION['extra']) ? (int)$_SESSION['extra'] -1 : 0;
                $posts = buscaBlog_post(array('status'=>'1','ordem'=>'data_hora asc', 'limit'=>$limit,'idblog_categoria'=>$vcp['idblog_categoria'], 'pagina'=>$pag));
                $totalBlog['idblog_categoria'] = $vcp['idblog_categoria'];
    
            }else if (!empty($_SESSION['idu']) && !empty($verifica_tags_post)){
                $vtp =  $verifica_tags_post[0];
                $pag = !empty($_SESSION['extra']) ? (int)$_SESSION['extra'] -1 : 0;
                $posts = buscaBlog_post(array('status'=>'1','ordem'=>'data_hora asc', 'limit'=>$limit,'tags'=>$vtp['idblog_tags'], 'pagina'=>$pag));
                // $totalBlog['idblog_categoria'] = $vtp['idblog_categoria'];
    
            }else if($_SESSION['idu'] == 'arquivos'){
                // echo '<pre>';var_dump($_SESSION['extra']);exit;
                $pag = !empty($_SESSION['extra2']) ? (int)$_SESSION['extra2'] -1 : 0;
                $totalBlog['dataBusca'] = $_SESSION['extra'];
                $posts = buscaBlog_post(array('limit'=>$limit, 'pagina'=>$pag, 'dataBusca'=>$_SESSION['extra']));
            }else{
                $posts = buscaBlog_post(array('status'=>'1','ordem'=>'data_hora', 'dir'=> 'desc', 'limit'=>$limit, 'pagina'=>$pag));
            };
    
            //busca total de postagens
            $totalBlog = buscaBlog_post($totalBlog);
            $totalBlog = $totalBlog[0]['totalRecords'];
            $totalPaginas = ceil($totalBlog / $limit);
            $total = $totalPaginas;
            $urlpag = ENDERECO."blog".$urlrewrite;
        }
    }
    else{
        $MODULO = '404';
        $REQUEST_P = '404';
    }
    // else{
    //     header("HTTP/1.1 301 Moved Permanently");
    //     header("Location:".ENDERECO);
    // }


    // ================SEO================= 
    $seo = buscaSeo(array('urlrewrite'=>$REQUEST_P));
    if(!empty($seo)){
        $seo = $seo[0];
    }else{
        switch ($MODULO) {
            case 'home':
                $seo['title'] = 'Home';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case '404':
                $seo['title'] = 'STATUS CODE 404';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'planos':
                $seo['title'] = 'Planos';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'blog':
                $seo['title'] = 'Blog';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'brascloud':
                $seo['title'] = 'Brascloud';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'blog-interna':
                $seo['title'] = $seo_title;
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'servicos-interna':
                $seo['title'] = $seo_title;
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'clientes':
                $seo['title'] = 'Clientes';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'contato':
                $seo['title'] = 'Contato';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'inovacao':
                $seo['title'] = 'Inovação';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'lgpd':
                $seo['title'] = 'LGPD';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'publica':
                $seo['title'] = 'Landing Page';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;
            
            case 'servicos':
                $seo['title'] = 'Serviços';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            case 'suporte':
                $seo['title'] = 'Suporte';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;

            default:
                $seo['title'] = '';
                $seo['description'] = '';
                $seo['keywords'] = '';
                break;
        }
    }
?>