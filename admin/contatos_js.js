// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;

$(document).ready(function(){

});

function preTableContatos(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableContatos();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableContatos();
	             }else{
	                 msgErro("Número de página deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableContatos();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableContatos();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&nome="+$("#buscarapida").val();
	            dataTableContatos();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
            $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableContatos();
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
	         dataTableContatos();
	    });

}
var myColumnDefs = [
	{key:"idcontatos", sortable:true, label:"ID", print:true, data:true}, 
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
	{key:"email", sortable:true, label:"E-mail", print:true, data:true},
	{key:"telefone", sortable:true, label:"Telefone", print:true, data:true},
	{key:"assunto", sortable:true, label:"Assunto", print:true, data:true}
	// {key:"idioma", sortable:true, label:"Idioma", print:true, data:true}
]

function columnContatos(){
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

function dataTableContatos(){
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
                            tr += '<a href="index.php?mod=contatos&acao=formContatos&met=editContatos&idu='+value.idcontatos+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                            tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idcontatos+' ?\',\'php\', \'contatos_script.php?opx=deletaContatos&idu='+value.idcontatos+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
        location.href='index.php?mod=contatos&acao=listarContatos';
    });
});