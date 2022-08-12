// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableNewsletter(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableNewsletter();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableNewsletter();
	             }else{
	                 msgErro("número de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableNewsletter();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableNewsletter();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&email="+$("#buscarapida").val();
	            dataTableNewsletter();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
            $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableNewsletter();
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
	         dataTableNewsletter();
	    });

}
	var myColumnDefs = [
		{key:"idnewsletter", sortable:true, label:"ID", print:false, data:true},
		{key:"email", sortable:true, label:"Email", print:false, data:true},
		{key:"nome", sortable:true, label:"Nome", print:false, data:true}
		
		
	]
function columnNewsletter(){
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
function dataTableNewsletter(){
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
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idnewsletter+' ?\',\'php\', \'newsletter_script.php?opx=deletaNewsletter&idu='+value.idnewsletter+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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


function valida_form(){
	var arquivo = document.getElementById("arquivo");
	var idnewsletter = document.getElementById("idnewsletter");
	var errors = false;

	var valida = validaForm({
	    form:$("form[name='formNewsletter']"),
	    notValidate:true,
	   // msgError: 'seu burro',
	    validate: true
	});
	if (arquivo.value == "" && idnewsletter.value !=="0" ){

		var errors = false;
	};
	if (arquivo.value == "" && idnewsletter.value =="0" ){
		msgErro('Preencha o(s) campo(s) obrigatório(s)');
		$('.newsletter').css('border', '1px solid red');

		var errors = true;
	};
		

	if (!valida) {
		errors = true;
	};

	
	return errors;

}

$(document).ready(function(){

    $(".cancelar").click(function(event){
        location.href='index.php?mod=newsletter&acao=listarNewsletter';
    });

	$('#enviar_newsletter').click(function(event){
			event.preventDefault();
			var valida = valida_form();

			if(!valida){
				$('#formNewsletter').submit();
			}else{
				msgErro('deu ruim');
			}

	});

	  $('select#idcampeonato').on("change", function(){
  //console.log('select');
        $.ajax({
            url: 'newsletter_mod.php',
            type: 'POST',
            data:{cmp: 'categoria',
                  acao: 'pegar_categoria',
                  id:  $('select#idcampeonato').val()
                },
            dataType: 'json',
            success: function (data) {
               // $('#modelo').html(data);
            //console.log(data);

            var categoria = '';
      for (var i = data.length - 1; i >= 0; i--) {
            categoria += ' <option value="'+data[i].idcategoria+'"> '+data[i].nome+'</option>';
            
      }


                $('select#idcategoria').parent().parent().parent().show();
                $('select#idcategoria').html(categoria);
                $('select#idcategoria').parent().parent().addClass("focus");
            }
        });
    });



})