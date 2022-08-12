// Versao do modulo: 2.20.130114

var preventCache = Math.random();
var requestInicio = ""
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
var flag = "";

function preTableBlog_comentarios(buscar){
	$("#limit").change(function(){
         $("#pagina").val(1);
         dataTableBlog_comentarios(buscar);
    });

    $("#pagina").keyup(function(e){
         if(e.keyCode == 13){
             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
                 dataTableBlog_comentarios(buscar);
             }else{
                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
             }
         }
    });

    $(".next").click(function(e){
          e.preventDefault();
          $("#pagina").val($(this).attr('proximo'));
          dataTableBlog_comentarios(buscar);
    });

    $(".prev").click(function(e){
         e.preventDefault();
         $("#pagina").val($(this).attr('anterior'));
         dataTableBlog_comentarios(buscar);
    });


    //LISTAGEM BUSCA
    $("#buscarapida").keyup(function(event){
        event.preventDefault();
        if(event.keyCode == '13') {
            pesquisar = "&nome="+$("#buscarapida").val();
            dataTableBlog_comentarios(buscar); 
             $(".abas_list").find("li").removeClass("action");
        	$(".abas_list li:first").addClass("action");
        }
        return true;
    });

	 $("#filtrar").click(function(e){
        e.preventDefault();
        pesquisar = "&"+$("#formAvancado").serialize(); 
        dataTableBlog_comentarios(buscar);
        $(".abas_list").find("li").removeClass("action");
        $(".abas_list li:first").addClass("action");
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
         dataTableBlog_comentarios(buscar);
    });

    $(".listaFlag").click(function(e){ 
        e.preventDefault();    
        flag = $(this).attr("codigo");  
        $(".abas_list").find("li").removeClass("action");
        $(this).parent().addClass("action");
        $("#pagina").val(1);
        dataTableBlog_comentarios(buscar);
    }); 
}

var myColumnDefs = [
	{key:"idblog_comentarios", sortable:true, label:"ID", print:true, data:true},
  {key:"idblog_post", sortable:true, label:"Id Post", print:false, data:false},
  {key:"nomePost", sortable:true, label:"Post", print:true, data:true},
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
	{key:"comentario", sortable:true, label:"Comentário", print:true, data:true},	
	// {key:"status", sortable:true, label:"Status", print:true, data:true},
  { key: "status_icone", sortable: false, label: "Status", print: false, data: true },
  { key: "status_nome", sortable: false, label: "Status", print: true, data: false },
  {key:"data", sortable:true, label:"Data", print:true, data:true}
]


function columnBlog_comentarios(){
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
function dataTableBlog_comentarios(buscar){

	idblog_post = "";
    idg = "";
    if($("#idg").length){
       idblog_post = "&idblog_post="+$("#idg").val();
       idg = "&idg="+$("#idg").val();
    } 

    flag = "&status="+flag;

    limit = $("#limit").val();
    pagina = $("#pagina").val();
    pagina = parseInt(pagina) - 1;
    colunas = myColumnDefs;
    colunas = JSON.stringify(colunas);
    queryDataTable = "&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas+idblog_post+flag;
    $.ajax({
       url: "base_proxy.php",
       dataType: "json",
       type: "post",
       data: requestInicio+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir+idblog_post+flag,
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
								if(key == 'status'){
									tr += '<td><span>'+value[key]+'</span></td>';
								}else if(key != 'email' && key != 'data' && key != 'status'){
									tr += '<td><span>'+value[key]+'</span></td>';
								}else if( key == 'data'){
									split = value[key].split(/\D/);  
									novadata = split[2] + "/" +split[1]+"/"+split[0]+" "+split[3]+":"+split[4];
                                    tr += '<td><span>'+novadata+'</span></td>';
 								} 
							}
                        });

                        tr += '<td><div class="acts">';
                        tr += '<a href="../admin/?mod=blog_comentarios&acao=formBlog_comentarios&met=editBlog_comentarios&idu='+value.idblog_comentarios+idg+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                        tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.nome+' ?\',\'php\', \'blog_comentarios_script.php?opx=deletaBlog_comentarios&idu='+value.idblog_comentarios+idg+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
        location.href='index.php?mod=blog_comentarios&acao=listarBlog_comentarios';
    });

	/*$(".abas_list_li a").click(function(){
		$(this).parent().parent().find(".abas_list_li").removeClass("action");
		if(typeof $(this).attr("status2") !== 'undefined'){
			if(typeof $(this).attr("idblog_post") !== 'undefined'){
				requestInicio = "tipoMod=blog_comentarios&p="+preventCache+"&status2="+$(this).attr("status2")+"&idblog_post="+$(this).attr("idblog_post");
			}else{
				requestInicio = "tipoMod=blog_comentarios&p="+preventCache+"&status2="+$(this).attr("status2");
			}
		}
		else{
			if(typeof $(this).attr("idblog_post") !== 'undefined'){
				requestInicio = "tipoMod=blog_comentarios&p="+preventCache+"&idblog_post="+$(this).attr("idblog_post");
			}else{
				requestInicio = "tipoMod=blog_comentarios&p="+preventCache+"&";
			}
		}
		$(this).parent().addClass("action");
		dataTableBlog_comentarios();
	});*/

	$('.comparar').change(function(){

		var dataDe = $('#dataDe');
		var dataAte = $('#dataAte');
		if(dataAte.val()!=''){

			 if (dataDe.val() > dataAte.val()) {
		        msgErro("Data não pode ser maior que a data final");
		        dataDe.val('');
		        dataAte.val('');
		        return false;
		    } else {
		        return true;
    }

		}
	});
});
