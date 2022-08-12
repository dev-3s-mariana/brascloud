$(document).ready(function(){
    // Planos
    $('.selecionar-plano').click(function(){
        if($(this).prop('checked') == true){
            var planoNome = $(this).parent().parent().parent().find('.plano-nome').text();
            var planoVel = $(this).parent().parent().parent().find('.plano-vel').text();
            var planoVcpu = $(this).parent().parent().parent().find('.plano-vcpu').text();
            var planoMemoria = $(this).parent().parent().parent().find('.plano-memoria').text();
            var planoIops = $(this).parent().parent().parent().find('.plano-iops').text();
            var planoZ01 = $(this).parent().parent().parent().find('.plano-z01').text();
            var planoZ02 = $(this).parent().parent().parent().find('.plano-z02').text();
            var planoZ03 = $(this).parent().parent().parent().find('.plano-z03').text();
            var planoDisco = $(this).parent().parent().parent().find('.plano-disco').text();
            var idPlano = $(this).data('idplano');

            var planoPrecoh = $(this).data('precoh');
            var planoPrecom = $(this).data('precom');
            var precoH = parseFloat(planoPrecoh.replace('.', '').replace(',', '.'));
            var precoM = parseFloat(planoPrecom.replace('.', '').replace(',', '.'));
            var mTotal = parseFloat($('#resumo-precom').data('totalm'));
            var hTotal = parseFloat($('#resumo-precoh').data('totalh'));
            var append = '';

            mTotal = mTotal + precoM;
            hTotal = hTotal + precoH;

            append += '<tr class="d-flex plano-'+idPlano+'">';
            append += '    <td class="td-1 d-flex"><span class="text-white">'+planoNome+'</span></td>';
            append += '    <td class="td-2 d-flex"><span class="text-orange">'+planoVel+'</span></td>';
            append += '    <td class="td-3 d-flex"><span class="text-white">RS'+planoPrecoh+'/h</span></td>';
            append += '</tr>';

            $('#resumo-precoh').text('R$'+hTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})+' / hora');
            $('#resumo-precom').text('R$'+mTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})+' / mês');
            $('#resumo-precoh').data('totalh', hTotal);
            $('#resumo-precom').data('totalm', mTotal);
            $('#resumo-pedido').append(append);
        }else{
            var idPlano = $(this).data('idplano');
            var planoPrecoh = $(this).data('precoh');
            var planoPrecom = $(this).data('precom');
            var precoH = parseFloat(planoPrecoh.replace('.', '').replace(',', '.'));
            var precoM = parseFloat(planoPrecom.replace('.', '').replace(',', '.'));
            var mTotal = parseFloat($('#resumo-precom').data('totalm'));
            var hTotal = parseFloat($('#resumo-precoh').data('totalh'));

            mTotal = mTotal - precoM;
            hTotal = hTotal - precoH;

            $('#resumo-precoh').text('R$'+hTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})+' / hora');
            $('#resumo-precom').text('R$'+mTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2,maximumFractionDigits: 2})+' / mês');
            $('#resumo-precoh').data('totalh', hTotal);
            $('#resumo-precom').data('totalm', mTotal);
            $('.plano-'+idPlano).remove();
        }
    });

    // Cliente
    $('.li-projeto').click(function(){
        var dataIdp = $(this).data('idp');
        var dataIdc = $(this).data('idc');
        $(this).parent('ul').find('.active-coluna').removeClass('active-coluna');
        $(this).find('a').addClass('active-coluna');
        $('.box-conteudo').hide();
        $('.box-conteudo[data-idp='+dataIdp+']').show();
    });

    // Contato
    $('.blue').mouseover(function(){
        $('.box-map.blue').show();
    });
    $('.blue').mouseout(function(){
        $('.box-map.blue').hide();
    });

    $('.orange').mouseover(function(){
        $('.box-map.orange').show();
    });
    $('.orange').mouseout(function(){
        $('.box-map.orange').hide();
    });

    $('.yellow').mouseover(function(){
        $('.box-map.yellow').show();
    });
    $('.yellow').mouseout(function(){
        $('.box-map.yellow').hide();
    });

    $('.darkblue').mouseover(function(){
        $('.box-map.darkblue').show();
    });
    $('.darkblue').mouseout(function(){
        $('.box-map.darkblue').hide();
    });

    $('.darkyellow').mouseover(function(){
        $('.box-map.darkyellow').show();
    });
    $('.darkyellow').mouseout(function(){
        $('.box-map.darkyellow').hide();
    });

    // Suporte
    $('.categoria-suporte').click(function(){
        var dataIdcs = $(this).data('idcs');
        $('.div-suporte').each(function(i, v){
            if($(this).data('idcs') == dataIdcs){
                $(this).show();
                $('.div-suporte[data-idcs='+dataIdcs+']').eq(0).trigger('click');
            }else{
                $(this).hide();
            }
        });
    });

    $('.div-suporte').click(function(){
        var dataIds = $(this).data('ids');
        $('.categoria2-active').removeClass('categoria2-active');
        $(this).find('.categoria2-link').addClass('categoria2-active');
        $('.conteudo-suporte').hide();
        $('.conteudo-suporte[data-ids='+dataIds+']').show();
    });

    // Planos
    let filtro1l = '';
    let filtro1w = '';
    $('.filtro-1').change(function(){
        if($('.l-check').prop('checked') == true && $('.w-check').prop('checked') == true){
            filtro1l = '.linux';
            filtro1w = '.windows';
            $('.td-padd').hide();
            $('.td-padd.linux').show();
            $('.td-padd.windows').show();
            if($('.filtro-3').val() != 0){
                $('.filtro-3').trigger('change');
            }
        }else if($('.l-check').prop('checked') == true && $('.w-check').prop('checked') == false){
            filtro1l = '.linux';
            filtro1w = '';
            $('.td-padd').hide();
            $('.td-padd.linux').show();
            if($('.filtro-3').val() != 0){
                $('.filtro-3').trigger('change');
            }
        }else if($('.l-check').prop('checked') == false && $('.w-check').prop('checked') == true){
            filtro1l = '';
            filtro1w = '.windows';
            $('.td-padd').hide();
            $('.td-padd.windows').show();
            if($('.filtro-3').val() != 0){
                $('.filtro-3').trigger('change');
            }
        }else{
            filtro1l = '';
            filtro1w = '';
            $('.td-padd').show();
            if($('.filtro-3').val() != 0){
                $('.filtro-3').trigger('change');
            }
        }
    });

    $('.filtro-2').change(function(){
        if($(this).prop('checked') == true){
            $('.preco_mes').hide();
            $('.preco_hora').show();
            $('.th-preco').text('PRECO/HORA');
        }else{
            $('.preco_mes').show();
            $('.preco_hora').hide();
            $('.th-preco').text('PRECO/MÊS');
        }
    });

    $('.filtro-3').change(function(){
        if($(this).val() == 1){
            $('.td-padd').hide();
            if(filtro1l != ''){
                $('.td-padd.zona_sp01'+filtro1l).show();
            }
            if(filtro1w != ''){
                $('.td-padd.zona_sp01'+filtro1w).show();
            }
            if(filtro1l == '' && filtro1w == ''){
                $('.td-padd.zona_sp01').show();
            }
        }else if($(this).val() == 2){
            $('.td-padd').hide();
            if(filtro1l != ''){
                $('.td-padd.zona_sp02'+filtro1l).show();
            }
            if(filtro1w != ''){
                $('.td-padd.zona_sp02'+filtro1w).show();
            }
            if(filtro1l == '' && filtro1w == ''){
                $('.td-padd.zona_sp02').show();
            }
        }else if($(this).val() == 3){
            $('.td-padd').hide();
            if(filtro1l != ''){
                $('.td-padd.zona_rs01'+filtro1l).show();
            }
            if(filtro1w != ''){
                $('.td-padd.zona_rs01'+filtro1w).show();
            }
            if(filtro1l == '' && filtro1w == ''){
                $('.td-padd.zona_rs01').show();
            }
        }else{
            $('.td-padd').show();
        }
    });

    //Contadores dinâmicos
    $(window).scroll(function(){
        if($('.count').length > 0){
            if(checkVisible(document.getElementById('footer'))){
                $('.count:not(.started)').each(function () {
                    $(this).addClass('started');
                    $(this).prop('Counter',0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(this.Counter).toLocaleString());
                        }
                    });
                });
            }
        }
    });

   //Máscaras
   $('.date').mask('00/00/0000');
   $('.time').mask('00:00:00');
   $('.date_time').mask('00/00/0000 00:00:00');
   $('.cep').mask('00000-000');
   $('.phone').mask('0000-0000');
   $('.phone_with_ddd').mask('(00) 0000-0000');
   $('.phone_br').mask('(00) 0 0000-0000', {clearIfNotMatch: true});
   $('.phone_us').mask('(000) 000-0000');
   $('.mixed').mask('AAA 000-S0S');
   $('.cpf').mask('000.000.000-00', {reverse: true});
   $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
   $('.money').mask('000.000.000.000.000,00', {reverse: true});
   $('.money2').mask("#.##0,00", {reverse: true});
   $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
     translation: {
       'Z': {
         pattern: /[0-9]/, optional: true
       }
     }
   });
   $('.ip_address').mask('099.099.099.099');
   $('.percent').mask('##0,00%', {reverse: true});
   $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
   $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
   $('.fallback').mask("00r00r0000", {
       translation: {
         'r': {
           pattern: /[\/]/,
           fallback: '/'
         },
         placeholder: "__/__/____"
       }
     });
   $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

   //Pesquisa
   var procurarAtivo = $("#procurar-ativo").val();
   if(procurarAtivo == 1){
      $("#blog-pesquisa").css("display","unset");
      $('#blog-artigos, #blog-paginacao').hide();
   }else if(procurarAtivo == 2){
      $("#blog-pesquisa").css("display","unset");
      $('#blog-artigos, #blog-paginacao, #blog-side, #blog-descricao').hide();
      $("#blog-titulo").text('Pesquisa');
      // $("#blog-navegacao").text('Pesquisa');
   }

   // else{
      // $("#blog-pesquisa").hide();
   // }

   $(".btn-search, .img-search").on("click", function(e){
      e.preventDefault();
      if($(this).closest("form").find("input[name=q]").val() == ""){
         $(this).closest("form").find("input[name=q]").attr("style","border: 1px solid red !important");
         msgErro('Preencha o(s) campo(s) obrigatório(s)');
      }else{
         $(this).closest("form").find("input[name=q]").attr("style","border: 1px solid grey !important");
         $(this).closest("form").submit();
      }
   });

    //FAQ
    $(".faq-perguntas").click(function(){
        if($(this).hasClass('faq-active')){
            $('.faq-perguntas.faq-active').find('.duvidas-descricao').slideUp();
            $('.faq-perguntas.faq-active').removeClass('faq-active').removeClass('faq-color');
        }else{
            $('.faq-perguntas.faq-active').find('.duvidas-descricao').slideUp();
            $('.faq-perguntas.faq-active').removeClass('faq-active').removeClass('faq-color');
            $(this).find('.duvidas-descricao').slideDown();
            $(this).addClass('faq-active').addClass('faq-color');
        }
    });

    //PLANO 3
    $(".plano3-option").click(function(){
        if($(this).hasClass('faq-active')){
            $('.plano3-option.faq-active').find('.descricao').slideUp();
            $('.plano3-option.faq-active').removeClass('faq-active').removeClass('faq-color');
        }else{
            $('.plano3-option.faq-active').find('.descricao').slideUp();
            $('.plano3-option.faq-active').removeClass('faq-active').removeClass('faq-color');
            $(this).find('.descricao').slideDown();
            $(this).addClass('faq-active').addClass('faq-color');
        }
    });

    //Abrir menu mobile
    $('.menu-hamburguer').click(function(){
        $('.menu-list').css('display' , 'flex');
    })

    $('.close-mobile').click(function(){
        $('.menu-list').css('display' , 'none');
    })

    //Abrir publica-modal
    $('.abrir-publica').click(function(){
        $('.modal-publica').css('display' , 'flex');
    })

    $('.close-publica').click(function(){
        $('.modal-publica').css('display' , 'none');
    })

    //Abrir pesquisa
    $('.botao-pesq').click(function(){
        $('.div-pesq').addClass("visible");
    })

    $(document).click(function(event) {
      if (!$(event.target).closest(".div-pesq,.botao-pesq").length) {
        $("body").find(".div-pesq").removeClass("visible");
         $('.botao-pesq').css('display', 'flex');
      }
    });

    $('.botao-pesq2').click(function(){
        $('.div-pesq2').addClass("visible");
        $('.botao-pesq2').css('display', 'none');
    })

    //BANNER
    $('.banner-slider').slick({
      infinite: true,
      autoplay: false,
      dots: true,
      arrows: false,
      slidesToShow: 1,
      slidesToScroll: 1,
    });

    //SOLUCOES
    $('.solucoes-slider').slick({
      infinite: true,
      centerMode: true,
      autoplay: true,
      arrows: false,
      dots: false,
      slidesToShow: 4.65,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //PLANOS1
    $('.planos1-slider').slick({
      infinite: true,
      centerMode: true,
      autoplay: true,
      arrows: false,
      dots: false,
      slidesToShow: 4.65,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1500,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //SELOS
    $('.selos-slider').slick({
      infinite: true,
      centerMode: true,
      arrows: false,
      dots: false,
      focusOnSelect: true,
      slidesToShow: 8,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 6,
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });
    $('.div-segmento').eq(0).trigger('click');

    //SELOS
    $('.blog-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      prevArrow: $(".blog-left"),
      nextArrow: $(".blog-right"),
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //COMENTARIOS
    $('.comentarios-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      arrows: false,
      slidesToShow: 3.3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //CATEGORIAS
    $('.categoria-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      arrows: false,
      slidesToShow: 8,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 6,
          }
        },
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
          }
        }
      ]
    });

    //CATEGORIAS
    $('.categoria3-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      arrows: false,
      slidesToShow: 6,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 4,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 3,
          }
        }
      ]
    });

    // //SUPORTE
    // $('.conteudo-slider').slick({
    //   slidesToShow: 1,
    //   slidesToScroll: 1,
    //   arrows: false,
    //   fade: true,
    //   asNavFor: '.categoria2-slider'
    // });
    // $('.categoria2-slider').slick({
    //   slidesToShow: 5,
    //   slidesToScroll: 1,
    //   vertical: true,
    //   dots: false,
    //   arrows: false,
    //   centerMode: true,
    //   focusOnSelect: true,
    //     responsive: [
    //         {
    //           breakpoint: 1100,
    //           settings: {
    //             slidesToShow: 4,
    //           }
    //         },
    //       ]
    //     });

    //COMENTARIOS
    $('.arquitetos-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      arrows: false,
      autoplay: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 800,
          settings: {
            slidesToShow: 3,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //COMENTARIOS
    $('.vantagens-slider').slick({
      infinite: true,
      centerMode: false,
      dots: false,
      focusOnSelect: true,
      arrows: false,
      autoplay: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1
          }
        }
      ]
    });

    //Linha do tempo
    $('.linha-slider').slick({
      infinite: true,
      autoplay: false,
      dots: false,
      prevArrow: $(".linha-left"),
      nextArrow: $(".linha-right"),
      focusOnSelect: true,
      centerMode: false,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1100,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });

    //CLIENTES

    $('.cliente-slider').slick({
        slidesToShow: 1,
        draggable: false,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        arrows: false,
        asNavFor: '.segmento-slider',
        responsive: [
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
    });
    var qtdSlideSegmento = parseInt($('.segmento-slider').data('total'));
    qtdSlideSegmento = (qtdSlideSegmento < 6?qtdSlideSegmento:6);
    $('.segmento-slider').slick({
        slidesToShow: qtdSlideSegmento,
        slidesToScroll: 1,
        asNavFor: '.cliente-slider',
        dots: false,
        centerMode: true,
        arrows: false,
        focusOnSelect: true,
        responsive: [
            {
              breakpoint: 1610,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1450,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1100,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });

    $('.cliente-slider2').slick({
        slidesToShow: 8,
        slidesToScroll: 1,
        dots: false,
        arrows: false,
        centerMode: true,
        variableWidth: true,
        focusOnSelect: true,
          responsive: [
            {
              breakpoint: 1610,
              settings: {
                slidesToShow: 7,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1450,
              settings: {
                slidesToShow: 6,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1400,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1100,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 480,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1
              }
            }
          ]
        });

    $('.segmento-slider').on('afterChange', function(event, slick, currentSlide){
        var currentSlideDom = $(slick.$slides.get(currentSlide));
        var dataIds = $(currentSlideDom).data('ids');
        $('.conteudo-slider').hide();
        $('.div-cliente.slick-slide[data-ids='+dataIds+']:not(.slick-cloned)').eq(0).trigger('click');
    });

    $('.cliente-slider2').on('afterChange', function(event, slick, currentSlide){
        var currentSlideDom = $(slick.$slides.get(currentSlide));
        var dataIdc = $(currentSlideDom).data('idc');
        $('.conteudo-slider').hide();
        $('.conteudo-slider[data-idcliente='+dataIdc+']').show();
        $('.conteudo-slider[data-idcliente='+dataIdc+']').find('.li-projeto').eq(0).trigger('click');
    });

    // $('.conteudo-slider').slick({
    //     slidesToShow: 1,
    //     draggable: false,
    //     slidesToScroll: 1,
    //     arrows: false,
    //     fade: true,
    //     asNavFor: '.cliente-slider2'
    // });
    // $('.cliente-slider2').slick({
    //     slidesToShow: 8,
    //     slidesToScroll: 1,
    //     asNavFor: '.conteudo-slider',
    //     dots: false,
    //     centerMode: true,
    //     focusOnSelect: false
    // });

    // $('.clientes-slider1').slick({
    //   infinite: true,
    //   centerMode: true,
    //   dots: false,
    //   arrows: false,
    //   slidesToShow: 6,
    //   slidesToScroll: 1,
    //   responsive: [
    //     {
    //       breakpoint: 1400,
    //       settings: {
    //         slidesToShow: 5,
    //       }
    //     },
    //     {
    //       breakpoint: 1300,
    //       settings: {
    //         slidesToShow: 4,
    //       }
    //     },
    //     {
    //       breakpoint: 480,
    //       settings: {
    //         slidesToShow: 3,
    //       }
    //     }
    //   ]
    // });

    // $('.conteudo-slider').slick({
    //     slidesToShow: 1,
    //     slidesToScroll: 1,
    //     arrows: false,
    //     fade: true,
    //     asNavFor: '.clientes-slider2'
    // });
    // $('.clientes-slider2').slick({
    //     slidesToShow: 6,
    //     slidesToScroll: 1,
    //     asNavFor: '.conteudo-slider',
    //     infinite: true,
    //     centerMode: true,
    //     arrows: false,
    //     dots: false,
    //     responsive: [
    //       {
    //         breakpoint: 1100,
    //         settings: {
    //           slidesToShow: 4,
    //         }
    //       },
    //       {
    //         breakpoint: 480,
    //         settings: {
    //           slidesToShow: 1
    //         }
    //       }
    //     ]
    // });

    //Formulários
    $("#enviar-newsletter").on("click",function(e){
        e.preventDefault();
        enviarFormEmail("newsletter",true);
    });
    $("#enviar-contato").on("click",function(e){
        e.preventDefault();
        if($('#resumo-precom').length > 0){
            var resumoPedido = '';
            $('#resumo-pedido tr').each(function(i, v){
                var td1 = $(this).find('.td-1').find('span').text();
                var td2 = $(this).find('.td-2').find('span').text();
                var td3 = $(this).find('.td-3').find('span').text();
                if(i == 0){
                    resumoPedido += td1 + '--' + td2 + '--'+ td3;
                }else{
                    resumoPedido += ';' + td1 + '--' + td2 + '--'+ td3;
                }
            });
            $('#resumo_pedido').val(resumoPedido);
            $('#total_pedido').val($('#resumo-precom').text());
        }
        enviarFormEmail("contato",true);
    });
    $("#enviar-contato2").on("click",function(e){
        e.preventDefault();
        enviarFormEmail("contato2",true);
    });
    $("#enviar-duvida").on("click",function(e){
        e.preventDefault();
        enviarFormEmail("duvida",true);
    });

   //Trabalhe conosco
   var arquivo;
   $('#trabalhe-curriculo').change(function(){
      var filename = $(this).val();
      var extension = filename.replace(/^.*\./, '');

      arquivo = $(this)[0].files[0];

      $("#curriculo-name").text(arquivo.name);

      if (extension == filename) { 
          extension = '';
      }
      else{ 
          extension = extension.toLowerCase(); 
      }
     
      if(extension!='doc' && extension!='docx' && extension!='pdf'){
        msgErro('A extensão deste arquivo não é permitida!');
        $("#trabalhe-curriculo").val('');
        $("#curriculo-name").text('Anexar currículo');
        // $("#curriculo-name").css('border', '1px solid red');
        $("#curriculo-arquivo").css('border', '1px solid red');
        return false;
      }

      var tamanhoMaximo ;
      tamanhoMaximo = ($("#maxFileSize").val())*1000000; 
      if($(this)[0].files[0].size >  tamanhoMaximo){
          msgErro('Arquivo muito grande!');
          $("#curriculo-name").text('Anexar currículo');
          $("#curriculo-arquivo").css('border', '1px solid red');
          return false;
      }
   });
  
   $("#enviar-trabalhe-conosco").on("click", function(e){
        e.preventDefault();
        var filename = $('#trabalhe-curriculo').val();
        var extension = filename.replace(/^.*\./, '');
        var valida = validaForm({
            form: $("form#form-trabalhe-conosco"),
            notValidate: true,
            validate: true
        });
        var valida = true;

        var formdata = new FormData();
        formdata.append("nome", $('#trabalhe-nome').val());
        formdata.append("email", $('#trabalhe-email').val());
        formdata.append("telefone", $('#trabalhe-telefone').val());
        formdata.append("mensagem", $('#trabalhe-mensagem').val());
        formdata.append("idarea_pretendida", $('#trabalhe-area').val());
        formdata.append("arquivo", arquivo);

        if(valida == false){
            msgErro('E-mail inválido');
            $("#form-trabalhe-conosco input[name='email']").val('');
        }else{
            arquivo = $('#trabalhe-curriculo')[0].files[0];

            if (extension == filename) { 
                extension = '';
            }
            else{ 
                extension = extension.toLowerCase(); 
            }
           
            if(extension!='doc' && extension!='docx' && extension!='pdf'){
              msgErro('A extensão deste arquivo não é permitida!');
              $("#trabalhe-curriculo").val('');
              $("#curriculo-name").text('Anexar currículo');
              $("#curriculo-arquivo").css('border', '1px solid red');
              valida = false;
              return false;
            }

            var tamanhoMaximo;
            tamanhoMaximo = ($("#maxFileSize").val())*1000000; 
            if($('#trabalhe-curriculo')[0].files[0].size >  tamanhoMaximo){
                msgErro('Arquivo muito grande!');
                $("#trabalhe-curriculo").val('');
                $("#curriculo-name").text('Anexar currículo');
                $("#curriculo-arquivo").css('border', '1px solid red');
                valida = false;
                return false;
            }

            var valida = validaForm({
                form: $("form#form-trabalhe-conosco"),
                notValidate: true,
                validate: true,
            });
            if (valida) {
                $.ajax({
                    url: 'admin/trabalhe_conosco_script.php?ajax=true&opx=cadastroTrabalhe_conosco',
                    type: 'post',
                    dataType: 'json',
                    data: formdata,
                    processData: false,
                    contentType: false,
                    beforeSend:function(){
                        Loader.show();
                    }
                }).done(function (e) {
                    Loader.hide();
                    if (e.status) {
                        msgSucesso('Seu currículo foi enviado com sucesso!');
                        $('form#form-trabalhe-conosco')[0].reset();
                        setTimeout(function(){
                            document.location.reload(true)
                        }, 1200)
                    } else {
                        msgErro('Falha ao enviar formulário!');
                    }
                });
            }
        }
    });

   //Blog comentário
   $("#enviar-blog-comentario").on("click", function(e){
        e.preventDefault();
        var formData = new FormData($('#form-blog-comentario')[0]);
        var valida = validaForm({
            form: $("form#form-blog-comentario"),
            notValidate: true,
            validate: true
        });
        var valida = validateEmail($("#form-blog-comentario input[name='email']").val());
        // var valida = true;
        if(valida == false){
            msgErro('E-mail inválido');
            $("#form-blog-comentario input[name='email']").val('');
            $("#form-blog-comentario input[name='email']").addClass("border-error");
        }else{
            $("#form-blog-comentario input[name='email']").removeClass('border-error').addClass("border-complete");
            var valida = validaForm({
                form: $("form#form-blog-comentario"),
                notValidate: true,
                validate: true
            });
            if (valida) {
                $.ajax({
                    url: 'admin/blog_comentarios_script.php?opx=cadastroBlog_comentarios&ajax=true',
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    // data: $('form#form-blog-comentario').serialize(),
                    beforeSend:function(){
                        Loader.show();
                    }
                }).done(function (e) {
                    Loader.hide();
                    if (e.status) {
                        msgSucesso('Seu Comentário foi enviado com sucesso!');
                        $('form#form-blog-comentario')[0].reset();
                        setTimeout(function(){
                            document.location.reload(true)
                        }, 1200)
                    } else {
                        msgErro('Falha ao enviar formulário!');
                    }
                });
            }
        }
   });

   //Anexar imagem blog comentário
   $("#anexar-imagem").change(function(){
      if ($("#anexar-imagem")[0].files && $("#anexar-imagem")[0].files[0]) {
         var filename = $(this).val();
         var reader = new FileReader();
         var extension = filename.replace(/^.*\./, '');
         if (extension == filename) { extension = '';
         }else{ extension = extension.toLowerCase(); }

         if(extension!='jpg' && extension!='png' && extension!='gif' && extension!='jpeg' ){
           msgErro('A extensão deste arquivo não é permitida!');
           $("#anexar-imagem").val('');
           return false;
         }

         var tamanhoMaximo ;
         tamanhoMaximo = ($("#maxFileSize").val())*1000000;
         if($(this)[0].files[0].size >  tamanhoMaximo){
             msgErro('Arquivo muito grande!');
             $("#anexar-imagem").val('');
             return false;
         }

         reader.onload = function(e) {
            $('#imagem-upload').attr('src', e.target.result);
            // $('#imagem-upload').css("display","block");
            // $('#imagem-upload').css("margin-bottom","15px");
            // $('#icone-comentario').css("display","none");
         }
         reader.readAsDataURL($("#anexar-imagem")[0].files[0]);
      }
   });
});

function checkVisible(elm) {
    var rect = elm.getBoundingClientRect();
    var viewHeight = Math.max(document.documentElement.clientHeight, window.innerHeight);
    return !(rect.bottom < 0 || rect.top - viewHeight >= 0);
}

function callbackCEP(conteudo) {
    if (!("erro" in conteudo)) {
        //Atualiza os campos com os valores.
        document.getElementById('endereco').value=(conteudo.logradouro);
        document.getElementById('bairro').value=(conteudo.bairro);
        document.getElementById('cidade').value=(conteudo.localidade);
        document.getElementById('uf').value=(conteudo.uf);
    } //end if.
    else {
        //CEP não Encontrado.
        $("#cep").addClass('border-error');
        msgErro('CEP não Encontrado.');
        Loader.hide();
    }
}

function pesquisaCEP(valor) {
    //Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    //Verifica se campo cep possui valor informado.
    if (cep != "") {
        //Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        //Valida o formato do CEP.
        if(validacep.test(cep)) {

            //Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('endereco').value="";
            document.getElementById('bairro').value="";
            document.getElementById('cidade').value="";
            document.getElementById('uf').value="";

            //Cria um elemento javascript.
            var script = document.createElement('script');

            //Sincroniza com o callback.
            script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=callbackCEP';

            //Insere script no documento e carrega o conteúdo.
            document.body.appendChild(script);

        } //end if.
        else {
            //cep é inválido.
            return false;
        }
    } //end if.
    else {
        return false;
    }
}

function validarCPF(cpf) {  
    cpf = cpf.replace(/[^\d]+/g,'');    
    if(cpf == '') return false; 
    // Elimina CPFs invalidos conhecidos    
    if (cpf.length != 11 || 
        cpf == "00000000000" || 
        cpf == "11111111111" || 
        cpf == "22222222222" || 
        cpf == "33333333333" || 
        cpf == "44444444444" || 
        cpf == "55555555555" || 
        cpf == "66666666666" || 
        cpf == "77777777777" || 
        cpf == "88888888888" || 
        cpf == "99999999999")
            return false;       
    // Valida 1o digito 
    add = 0;    
    for (i=0; i < 9; i ++)      
        add += parseInt(cpf.charAt(i)) * (10 - i);  
        rev = 11 - (add % 11);  
        if (rev == 10 || rev == 11)     
            rev = 0;    
        if (rev != parseInt(cpf.charAt(9)))     
            return false;       
    // Valida 2o digito 
    add = 0;    
    for (i = 0; i < 10; i ++)       
        add += parseInt(cpf.charAt(i)) * (11 - i);  
    rev = 11 - (add % 11);  
    if (rev == 10 || rev == 11) 
        rev = 0;    
    if (rev != parseInt(cpf.charAt(10)))
        return false;       
    return true;   
}

function validarCNPJ(cnpj) {
 
    cnpj = cnpj.replace(/[^\d]+/g,'');
 
    if(cnpj == '') return false;
     
    if (cnpj.length != 14)
        return false;
 
    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" || 
        cnpj == "11111111111111" || 
        cnpj == "22222222222222" || 
        cnpj == "33333333333333" || 
        cnpj == "44444444444444" || 
        cnpj == "55555555555555" || 
        cnpj == "66666666666666" || 
        cnpj == "77777777777777" || 
        cnpj == "88888888888888" || 
        cnpj == "99999999999999")
        return false;
         
    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;
         
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;
           
    return true;
    
}

function enviarFormEmail(local, verificarEmail){
   var form = "form-"+local;
   var formData = new FormData($('#'+form)[0]);   
   var valida = validaForm({
      form: $("form#"+form),
      notValidate: true,
      validate: true
   });
   if(verificarEmail == true){
      var valida = validateEmail($("form#"+form+" input[name='email']").val());
   }else{
      var valida = true;
   }
   if(valida == false){
      msgErro('E-mail inválido');
      $("form#"+form+" input[name='email']").val('');
      $("form#"+form+" input[name='email']").addClass("border-error");
   }else{
      $("form#"+form+" input[name='email']").removeClass('border-error').addClass("border-complete");
      var valida = validaForm({
         form: $("form#"+form),
         notValidate: true,
         validate: true
      });
      if (valida) {
         $.ajax({
            url: 'admin/email_script.php?opx='+local,
            type: 'post',
            dataType : "json",
            data: formData,
            processData: false,
            contentType: false,
            // data: $("form#"+form).serialize(),
            beforeSend:function(){
               Loader.show();
            }
         }).done(function (e) {
            Loader.hide();
            if (e.status) {
               msgSucesso('Formulário enviado com sucesso!');
               $('#'+form)[0].reset();
            } else {
               msgErro('Falha ao enviar formulário!');
            }
         });
      }
   }
}
function validaForm(params)
{
    var valida = true;
    var notpermitidos = ['', '__/__/____', undefined, null];
    var config = {
        form    : $(params.form.selector),
        notValidate : false,
        msgError  : 'Preencha o(s) campo(s) obrigatório(s)',
        validate  : false,
        msgValidate :  'O formulário foi validado com sucesso.',
        validaEmail : false
    }
    $.extend(config, params);
    var $form = config.form;
    $form.find(':input.required', 'select.required').each(function () {
        var border = (!$(this).val()) ? 'border-error' : 'border-complete';
        if ($.inArray($(this).val(), notpermitidos) == 0){
            valida = false;
        }
        $(this).closest('input, textarea, select').removeClass('border-error').addClass(border);

        if($(this).attr("id") == "arquivo"){
            $("#area_files").addClass("border-error");
        }else{
            $("#area_files").removeClass('border-error').addClass("border-complete");
        }
    });
    if (config.notValidate && !valida){
        msgErro(config.msgError);
    }else{
        $form.find(':input.validate_email').each(function (){
            if(!validateEmail($(this).val()))
            {
                $(this).css('border', '1px solid red');
                config.msgError = "E-mail inválido, verifique";
                valida = false;
            };
        });
        if (config.notValidate && !valida)
            msgErro(config.msgError);
    }
    return valida;
}
function msgErro(msg, pagina) {
    jError(
        msg,
        {
            autoHide: true, // added in v2.0
            clickOverlay: true, // added in v2.0
            MinWidth: 250,
            TimeShown: 3000,
            ShowTimeEffect: 200,
            HideTimeEffect: 200,
            LongTrip: 20,
            HorizontalPosition: 'center',
            VerticalPosition: 'top',
            ShowOverlay: true,
            ColorOverlay: '#000',
            OpacityOverlay: 0.3,
            onClosed: function () { // added in v2.0

            },
            onCompleted: function () { // added in v2.0

            }
        });
}
function msgSucesso(msg, pagina) {
    jSuccess(
        msg,
        {
            autoHide: true, // added in v2.0
            clickOverlay: true, // added in v2.0
            MinWidth: 250,
            TimeShown: 3000,
            ShowTimeEffect: 200,
            HideTimeEffect: 200,
            LongTrip: 20,
            HorizontalPosition: 'center',
            VerticalPosition: 'top',
            ShowOverlay: true,
            ColorOverlay: '#000',
            OpacityOverlay: 0.3,
            onClosed: function () { // added in v2.0
                if (pagina) {
                    if (pagina != "") {
                        if (pagina == "home")
                            location.href = jQuery("#_endereco").val();
                        else if (pagina == "location")
                            location.reload();
                        else
                            location.href = jQuery("#_endereco").val() + pagina;
                    }
                }
            },
            onCompleted: function () { // added in v2.0

            }
        });
}

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

// Verifica se o elemento está na visível na tela
function elementInView(elem){
    return $(window).scrollTop() < $(elem).offset().top + $(elem).height() ;
}
// Usage example
// $(window).scroll(function(){
//     if (elementInView($('#your-element'))){
//         console.log('there it is, wooooohooooo!');
//     }
// });

/*-----------------------------------------
Loader
-----------------------------------------*/
const Loader = {
    show() {
        // Inicia o Loader
        document.querySelector('.loader').classList.add('active');
    },
    hide() {
        // Inicia o Loader
        document.querySelector('.loader').classList.remove('active');
    }
}

/*-----------------------------------------
LGPD
-----------------------------------------*/
// Adiciona a classe ao body
document.body.className = document.body.className + " js_enabled";

// Variaveis do modal.
var modalLGPD = document.querySelector('.lgpd-cookies');
var botaoContinuar = document.querySelector('.lgpd-botao.continuar');
var botaoSair = document.querySelector('.lgpd-botao.sair');


/* Verifica se o modal já foi visualizado 
** e não o mostra novamente */
function primeiroAcesso() {
    /**
     * Set cookie
     *
     * @param string name
     * @param string value
     * @param int days
     * @param string path
     * @see http://www.quirksmode.org/js/cookies.html
    */

    function createCookie(name, value, days, path) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=" + path;
    }

    /**
     * Read cookie
     * @param string name
     * @returns {*}
     * @see http://www.quirksmode.org/js/cookies.html
    */

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Clique no botão de continuar, fecha o modal e salva que já foi visualizado.
    botaoContinuar.addEventListener('click', function () {
        modalLGPD.style.display = 'none';
        createCookie('lgpd-PROJETO-visualizada', 'yes', cookieExpiry, cookiePath);
    });

    // Clique no botão de sair, limpa os cookies e retorna ao site acessado anteriormente.
    botaoSair.addEventListener('click', function () {
        modalLGPD.style.display = 'none';

        // Capta os cookies criados.
        var cookies = document.cookie.split("; ");

        // Deleta os cookies criados.
        for (var c = 0; c < cookies.length; c++) {
            var d = window.location.hostname.split(".");
            while (d.length > 0) {
                var cookieBase = encodeURIComponent(cookies[c].split(";")[0].split("=")[0]) +
                    '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; domain=' + d.join('.') + ' ;path=';
                var p = location.pathname.split('/');
                document.cookie = cookieBase + '/';
                while (p.length > 0) {
                    document.cookie = cookieBase + p.join('/');
                    p.pop();
                };
                d.shift();
            }
        }

        createCookie('lgpd-PROJETO-visualizada', 'no', cookieExpiry, cookiePath);

        // Retona a página de navegação anterior.
        history.go(-1);
    });

    var cookieMessage = document.querySelector('.lgpd-cookies');

    if (cookieMessage == null) {
        return;
    }
    
    var cookie = readCookie('lgpd-PROJETO-visualizada');

    if (cookie != null && cookie == 'yes') {
        cookieMessage.style.display = 'none';
    } else {
        cookieMessage.style.display = 'flex';
    }

    // Configura / atualiza o cookie.
    var cookieExpiry = cookieMessage.getAttribute('data-cookie-expiry');
    if (cookieExpiry == null) {
        cookieExpiry = 60;
    }
    var cookiePath = cookieMessage.getAttribute('data-cookie-path');
    if (cookiePath == null) {
        cookiePath = "/";
    }
}

// Carrega o LGPD após a página carregar completamente.
window.onload = primeiroAcesso();