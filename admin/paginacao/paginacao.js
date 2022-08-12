
function atualizaPaginas(total_paginas, paginaAtual, total_registros, nomeTable){ 
       
       $('#pagina').val(paginaAtual); 
  
       $('.totalPagina').html("de " +total_paginas);
       
       //VERIFICA LINK PARA O PROXIMO
       if(paginaAtual < total_paginas){
            proximo = paginaAtual + 1;
            $('.next').attr("proximo", proximo); 
            $('.next').removeClass("disable").css("display","block");
       }else{
           $('.next').addClass("disable").css("display","none");
       }
       
       //VERIFICA LINK PARA O ANTERIOR
       if(paginaAtual != 1){
            anterior = paginaAtual - 1;
            $('.prev').attr("anterior", anterior); 
            $('.prev').removeClass("disable").css("display","block");
       }else{
           $('.prev').addClass("disable").css("display","none");
       }
       
       //VERIFICA 1-10 DE TOTAL DE PAGINAS        
       limit = $("#limit").val();
       
       //FIM
       fim = paginaAtual * limit;
       if(fim > total_registros){
           fim = total_registros;
       }
       
       //INICIO
       inicio = ((parseInt(paginaAtual) - 1) * limit) + 1;
       
       $(".pagination .one").html(inicio +" - "+ fim +" de "+ total_registros+" itens"); 
       totalPaginasGrid = total_paginas;
}