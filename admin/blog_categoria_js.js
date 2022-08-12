// Versao do modulo: 2.20.130114

var preventCache = Math.random();
var requestInicio = ""
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1
function preTableBlog_categoria(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableBlog_categoria();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableBlog_categoria();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableBlog_categoria();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableBlog_categoria();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
	            pesquisar = "&nome="+$("#buscarapida").val();
	            dataTableBlog_categoria();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableBlog_categoria();
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
	         dataTableBlog_categoria();
	    });

	    $('.table').on("click", ".inverteStatus", function (e) {
			var params = {
				idblog_categoria: $(this).attr("codigo")
			}

			$.post(
				'blog_categoria_script.php?opx=inverteStatus',
				params,
				function (data) {
					var resultado = new String(data.status);

					if (resultado.toString() == 'sucesso') {
						dataTableBlog_categoria();
					}
					else if (resultado == 'falha') {
						alert('Não foi possível atender a sua solicitação.')
					}

				}, 'json'
			);
		});

}
	var myColumnDefs = [
		{key:"idblog_categoria", sortable:true, label:"ID", print:true, data:true},
		{key:"nome", sortable:true, label:"Nome", print:true, data:true},
		{key:"status", sortable:false, label:"Status", print:true, data:false},
  		{key:"status_icone", sortable:false, label:"Status", print:false, data:true}
	
	]
function columnBlog_categoria(){
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

function dataTableBlog_categoria(){
	
	    limit = $("#limit").val();
        pagina = $("#pagina").val();
        pagina = parseInt(pagina) - 1;
        colunas = myColumnDefs;
        colunas = JSON.stringify(colunas);
        queryDataTable = "&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas;
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
                                tr += '<a href="index.php?mod=blog_post&acao=listarBlog_post&idg='+value.idblog_categoria+'"><img src="images/modulos/blog_post_cinza.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Posts</span><span class="two"></span></div></a>';
                                tr += '<a href="index.php?mod=blog_categoria&acao=formBlog_categoria&met=editBlog_categoria&idu='+value.idblog_categoria+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idblog_categoria+' ?\',\'php\', \'blog_categoria_script.php?opx=deletaBlog_categoria&idu='+value.idblog_categoria+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
        location.href='index.php?mod=blog_categoria&acao=listarBlog_categoria';
    });

    $("#urlrewrite").blur(function (event) {
        event.preventDefault();
        if ($(this).val() != "" || $("#nome").val() != "") {
            url = $(this).val();
            if (url == "") {
                url = $("#nome").val();
                $("#urlrewrite").val($("#nome").val()).closest(".box_ip").addClass("focus");
            }
            verificaUrlrewrite(url);
        }
    });
    $("#nome").blur(function (event) {
        url = $("#urlrewrite").val();
        if (url == "" && $("#nome").val() != '') {
            nome = $("#nome").val();
            verificaUrlrewrite(nome);
        }
    });
});

function verificaUrlrewrite(url) {
    id = $("input[name=idblog_categoria]").val();

    $.ajax({
        url: 'blog_categoria_script.php',
        dataType: 'json',
        data: "opx=verificarUrlRewrite&idblog_categoria=" + id + "&urlrewrite=" + url,
        type: 'post',
        beforeSend: function () {
            Loader.show();
        },
        success: function (data) {
            
            if (!data.status) {
                msgErro("Url já cadastrada");
                $("#urlrewrite").val($("#urlrewriteantigo").val());
            } else {
                $("#urlrewrite").val(data.url);
                $("#urlrewrite").parent().addClass('focus');
            }
        },
        complete: function () {
            Loader.hide();
        }
    });
}