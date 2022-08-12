// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableRelatorios(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableRelatorios();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableRelatorios();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableRelatorios();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableRelatorios();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&titulo="+$("#buscarapida").val();
	            dataTableRelatorios();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
            $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableRelatorios();
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
	         dataTableRelatorios();
	    });


	    $('.table').on("click", ".inverteStatus", function (e) {
		var params = {
			idrelatorios: $(this).attr("codigo")
		}

		$.post(
			'relatorios_script.php?opx=inverteStatus',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableRelatorios();
				}
				else if (resultado == 'falha') {
					alert('Não foi possível atender a sua solicitação.')
				}

			}, 'json'
		);
	});

}
	var myColumnDefs = [
		{key:"idrelatorios", sortable:true, label:"ID", print:true, data:true},
		{key:"titulo", sortable:true, label:"Título", print:true, data:true},
		{key:"ano", sortable:false, label:"Ano", print:false, data:true},
    	{key:"status_icone", sortable:false, label:"Status",  print:false, data:true},
    	{key:"status_nome", sortable:false, label:"Status",  print:true, false:false} 
	]
function columnRelatorios(){
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
function dataTableRelatorios(){
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
                                tr += '<a href="index.php?mod=relatorios&acao=formRelatorios&met=editRelatorios&idu='+value.idrelatorios+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.titulo_aba+' ?\',\'php\', \'relatorios_script.php?opx=deletaRelatorios&idu='+value.idrelatorios+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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

$(document).ready(function(){
    $(".cancelar").click(function(event){
        location.href='index.php?mod=relatorios&acao=listarRelatorios';
    });

    $(".excluir-arquivo").click(function(e){
        e.preventDefault();
        var thisElement = $(this);
        $.ajax({
            url: "relatorios_script.php",
            type: "POST",
            dataType: "json",
            data: {opx: 'excluirArquivo', idrelatorios: $("#idrelatorios").val(), arquivo: $(thisElement).attr('data-arquivo')},
            beforeSend: function () {
                Loader.show();
            },
            success: function (data) {
                if (data.status == true) {
                    msgSucesso('Arquivo excluído com sucesso!');
                    $(thisElement).parent().remove();
                } else {
                    msgErro('Erro ao excluir arquivo!');
                }
                Loader.hide();
            },
            complete: function () {
                Loader.hide();
            }
        });
    });
})