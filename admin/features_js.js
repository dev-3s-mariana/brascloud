// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
var flag = "";

function preTableFeatures(){
		 $("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableFeatures();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableFeatures();
	             }else{
	                 msgErro("Número de página deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableFeatures();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableFeatures();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&titulo="+$("#buscarapida").val();
	            dataTableFeatures();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
          $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableFeatures();
	    });

	    $(".ordem").click(function(e){
	         e.preventDefault();
	         ordem = $(this).attr("ordem");
	         dir = $(this).attr("order");
	         $(".ordem").removeClass("action");
	         $(".ordem").removeClass("actionUp");
	         if($(this).attr("order") == "asc"){
	             $(this).attr("order","desc");
	             $(this).removeClass("action");
	             $(this).addClass("actionUp");
	         }else{
	             $(this).attr("order","asc");
	             $(this).removeClass("actionUp");
	             $(this).addClass("action");
	         }
	         dataTableFeatures();
	   }); 

      $('.table').on("click",".ordemUp",function(e){ 
          var params = { idfeatures: $(this).attr("codigo")}
          $.post( 'features_script.php?opx=alteraOrdemCima', params,
                function(data){  
                  var resultado = new String (data.status); 
                  if(resultado.toString() == 'sucesso'){ 
                       dataTableFeatures(); 
                  }  
                  else if (resultado == 'falha'){ 
                       alert('Não foi possível atender a sua solicitação.')
                  }
              },'json'
          );  
      });  
         

    $('.table').on("click", ".ordemDown", function(e){  
        var params = {idfeatures: $(this).attr("codigo")}  
        $.post( 'features_script.php?opx=alteraOrdemBaixo', params,
         function(data){  
            var resultado = new String (data.status);  
            if(resultado.toString() == 'sucesso'){ 
              dataTableFeatures(); 
            }  
            else if (resultado == 'falha'){ 
              alert('Não foi possível atender a sua solicitação.')
            } 
          },'json' 
        );  
    }); 
}

var myColumnDefs = [
	{key:"idfeatures", sortable:true, label:"ID", print:true, data:true},
	{key:"titulo", sortable:true, label:"Título", print:true, data:true}, 
   {key:"home_nome", sortable:true, label:"Página", print:true, data:true}, 
  // {key:"descricao", sortable:false, label:"Descrição", print:true, data:true},
  { key: "ordem", sortable: false, label: "Ordem", print: true, data: false },
  { key: "ordemUp", sortable: false, label: "Subir", print: false, data: true },
  { key: "ordemDown", sortable: false, label: "Descer", print: false, data: true }
]

function columnFeatures(){ 
    tr = "";
    $.each(myColumnDefs, function(col, ColumnDefs){
    	if(ColumnDefs['data']){
            orderAction = "";
            ordena = "";
            if(ColumnDefs['key'] == ordem){
                if(dir == "desc"){
                    orderAction = "actionUp";
                }else{
                    orderAction = "action";
                }
            }
            if(ColumnDefs['sortable']){
                ordena = 'ordem="'+ColumnDefs['key']+'" class="ordem '+orderAction+'" order="'+dir+'"';
            }
            tr += '<th><a href="#" '+ ordena +'>'+ColumnDefs['label']+'</a></th>';
        }
    });
    tr += "<th></th>";
    $('#listagem').find("thead").append(tr);
}

function dataTableFeatures(){
    limit = $("#limit").val();
    pagina = $("#pagina").val();
    pagina = parseInt(pagina) - 1;
    colunas = myColumnDefs;
    colunas = JSON.stringify(colunas);
    queryDataTable = requestInicio+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas;
    $.ajax({
          url: "base_proxy.php",
          dataType: "json",
          type: "post",
          data: requestInicio+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir,
          beforeSend: function () {
                Loader.show();
                $('#listagem').find("tbody tr").remove();
          },
          success:function(data){
                tr = "";
                if(data.totalRecords > 0){
                    $.each(data.records, function(index, value){
                    tr += '<tr>';
                    $.each(myColumnDefs, function(col, ColumnDefs){
                    	if(ColumnDefs['data']){
                    		key = ColumnDefs['key'];
                    		tr += '<td><span>'+value[key]+'</span></td>';
                    	}
                    });  
                      tr += '<td><div class="acts">';
                      tr += '<a href="index.php?mod=features&acao=formFeatures&met=editFeatures&idu='+value.idfeatures+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                      tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idfeatures+' ?\',\'php\', \'features_script.php?opx=deletaFeatures&idu='+value.idfeatures+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
                      tr += '</div></td>';
                    }); 
                    $('#listagem').find("tbody").append(tr);
                    atualizaPaginas(data.pageSize, (pagina + 1) , data.totalRecords);
                    $('.pagination').show(); 
                }else{
                     $('#listagem').find("tbody").append('<tr class="odd pesquisa_error"><td colspan="'+myColumnDefs.length+'">Nenhum resultado encontrado</td></tr>');
                     $('.pagination').hide();
                }
          },
          complete:function(){
              Loader.hide();
          }
    });
}

function carregaIconeServico() {
    $('i.icone_icone_categ').click(function (e) {
        e.preventDefault();
        var nome = $(this).data('nome');
        var id = $(this).data('id');
        var icone = '';
        icone = '<i class="fa fa-' + nome + ' fa-2x" data-id="' + id + '"></i>';
        icone += '<input type="hidden" name="icone_categ" id="icone_categ" data-icone="' + nome + '" value="' + id + '">';
        $('div#mostrar_icone_categ').html(icone);
    });
}

function carregaIconeAcao() {
    $('#icone_pai').on("click", ".icone_icone", function (e) {
        e.preventDefault();
        var nome = $(this).data('nome');
        var id = $(this).data('id');
        // var icone = '';
        $("#current_icon").removeClass().addClass('fas fa-'+nome+' fa-2x');
        $("#current_icon").attr('data-id', id);
        $("#current_icon").attr('title', nome);
        $("#imagem_icone").val(id);
        // icone = '<i class="fa fa-' + nome + ' fa-2x" data-id="' + id + '" title="'+nome+'"></i>';
        // icone += '<input type="hidden" name="icone" id="icone" value="' + id + '">';
        $("#icone_name").val(nome);
        // $('div#mostrar_icone').html(icone);
    });
}

function iconeQuantidade(){
   return $.ajax({
      url: 'js/fontawesome-5.15.json',
      dataType: 'json',
      type: 'post',
      success: function(data){

      },
      error: function(xhr, ajaxOptions, thrownError){
         alert(xhr.responseText);
         alert(thrownError);
      },
      complete: function(){

      }
   });
}

function iconePaginacao(pagina, pesquisa = ''){
   if(pagina == 1){
      var primeiroIcone = 0;
   }else{
      var primeiroIcone = 390 * (pagina - 1);
   }
   var ultimoIcone = 390 * pagina;
   var pagIcone = pagina;
   var append = '';
   $.ajax({
      url: 'js/fontawesome-5.15.json',
      dataType: 'json',
      type: 'post',
      success: function(data){
         $("#icone_pai").html('');
         for(var i=primeiroIcone;i>=primeiroIcone && i<ultimoIcone;i++){
            if(data.solid[i] != null){
               append = '<div style="width:6%; display: inline-block;" data-toggle="tooltip" title="'+data.solid[i].replace("fas ","").replace("fa-","")+'">';
               append += '<i class="'+data.solid[i]+' icone_icone" data-id="'+i+'" data-nome="'+data.solid[i].replace("fas ","").replace("fa-","")+'" title="'+data.solid[i].replace("fas ","").replace("fa-","")+'" style="padding:11px; cursor: pointer;"></i>';
               append += '<span style="display: none;">'+data.solid[i].replace("fas ","").replace("fa-","")+'</span>';
               append += '</div>';
               $("#icone_pai").append(append);
            }
         }
         if(pesquisa != ''){
            buscaIcone(pesquisa);
         }
      },
      error: function(xhr, ajaxOptions, thrownError){
         alert(xhr.responseText);
         alert(thrownError);
      }
   });
}

function buscaIcone() {
   var input, filter, div, tr, td, i, txtValue;
   input = document.getElementById("pesquisar_icone");
   filter = input.value.toLowerCase();
   div = document.getElementById("icone_pai");
   tr = div.getElementsByTagName("div");
   for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("span")[0]
      // td2 = tr[i].getElementsByTagName("td")[1];
      if (td || td2) {
         txtValue = (td.textContent || td.innerText);
         // txtValue2 = (td2.textContent || td2.innerText);
         if (txtValue.toLowerCase().indexOf(filter) > -1) {
            tr[i].style.display = "inline-block";
         } 
         // else if (txtValue2.toLowerCase().indexOf(filter) > -1) {
         //    tr[i].style.display = "";
         // } 
         else {
            tr[i].style.display = "none";
         }
      }
   }
}

$(document).ready(function(){
    $(".cancelar").click(function(event){
        location.href='index.php?mod=features&acao=listarFeatures';
    });

    var pagsIcone = iconeQuantidade().done(function(data){
        var paginas = 0;
        $("#div-page-icon").append('<span class="span-page-chevron" data-direction="left"><i class="fas fa-chevron-left"></i></span>');
        for (var i = 1; i <= data.solid.length; i+=390) {
            paginas+=1;
            if(i == 1){
                $("#div-page-icon").append('<span class="span-choose-icon span-page-active pagination" data-page="'+paginas+'">'+paginas+'</span>');
            }
            else{
                $("#div-page-icon").append('<span class="span-choose-icon span-page pagination" data-page="'+paginas+'">'+paginas+'</span>');
            }
        }
        $("#div-page-icon").append('<span class="span-page-chevron" data-direction="right"><i class="fas fa-chevron-right"></i></span>');
    });

    iconePaginacao(1);

    $("#div-page-icon").on("click", ".pagination", function(e){
        if($('input#pesquisar_icone').val() != ''){
            var pesquisa = $('input#pesquisar_icone').val();
            iconePaginacao($(this).attr('data-page'), pesquisa);
        }else{
            iconePaginacao($(this).attr('data-page'));
        }
        $(this).removeClass("span-page").addClass("span-page-active");
        $(this).siblings().not(".span-page-chevron").removeClass("span-page-active").addClass("span-page");
    });

    $("#div-page-icon").on("click", ".span-page-chevron", function(){
        var paginaSelecionada = $(".span-page-active").data('page');
        if($(this).data("direction") == "right"){
            if((paginaSelecionada+1) <= $(".pagination").length){
                if($('input#pesquisar_icone').val() != ''){
                    var pesquisa = $('input#pesquisar_icone').val();
                    iconePaginacao(paginaSelecionada+1, pesquisa);
                }
                else{
                    iconePaginacao(paginaSelecionada+1);
                }
                $(".span-choose-icon").each(function(){
                    if($(this).data("page") == (paginaSelecionada+1)){
                        $(this).removeClass("span-page").addClass("span-page-active");
                        $(this).siblings().not(".span-page-chevron").removeClass("span-page-active").addClass("span-page");
                    }
                });
            }
        }
        else{
            if((paginaSelecionada-1) >= 1){
                if($('input#pesquisar_icone').val() != ''){
                    var pesquisa = $('input#pesquisar_icone').val();
                    iconePaginacao(paginaSelecionada-1, pesquisa);
                }
                else{
                    iconePaginacao(paginaSelecionada-1);
                }
                $(".span-choose-icon").each(function(){
                    if($(this).data("page") == (paginaSelecionada-+1)){
                        $(this).removeClass("span-page").addClass("span-page-active");
                        $(this).siblings().not(".span-page-chevron").removeClass("span-page-active").addClass("span-page");
                    }
                });
            }
        }
    });

    $('input#pesquisar_icone').keyup(function (event) {
        var pesquisa = $(this).val();
        buscaIcone(pesquisa);
    });
    carregaIconeAcao();

    $('ul.tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });
    
    $('input#numero').keyup(function() {
        $(this).val(this.value.replace(/\D/g, ''));
    });

    $("#icone_upload").change(function(){
        var preview = $("#icone");
        var file = $(this)[0].files[0];
        var reader = new FileReader();

        reader.addEventListener("load", function(){
            $(preview).attr('src', reader.result);
        }, false);

        if(file){
            reader.readAsDataURL(file);
        }
        $("#icone").show();
    });

    let tipo, img, thisElem;
    $(".excluir-imagem").click(function(){
        tipo = $(this).attr('data-tipo');
        img = $(this).attr('data-img');
        thisElem = $(this);
        $("#modal-confirmacao").dialog({
            resizable: true,
            height:140,
            width:330,
            modal: true,  
            title:'Excluir imagem'    
        });
    });

    $(".confirm").click(function(){
        excluirImagem('features', tipo, img, thisElem);
        $('.ui-dialog-titlebar-close').click();
    });

    $(".cancel").click(function(){
        $('.ui-dialog-titlebar-close').click();
    });
});