// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "ordem";
var dir = "desc";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableProdutos(){
	$("#limit").change(function(){
			$("#pagina").val(1);
			dataTableProdutos();
	});

	$("#pagina").keyup(function(e){
			if(e.keyCode == 13){
				if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
					dataTableProdutos();
				}else{
					msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
				}
			}
	});

	$(".next").click(function(e){
			e.preventDefault();
			$("#pagina").val($(this).attr('proximo'));
			dataTableProdutos();
	});

	$(".prev").click(function(e){
			e.preventDefault();
			$("#pagina").val($(this).attr('anterior'));
			dataTableProdutos();
	});


	//LISTAGEM BUSCA
	$("#buscarapida").keyup(function(event){
		event.preventDefault();
		if(event.keyCode == '13') {
			$('#pagina').val(1);
			pesquisar = "&pergunta="+$("#buscarapida").val();
			dataTableProdutos();
		}
		return true;
	});

	$("#filtrar").click(function(e){
		e.preventDefault();
		$('#pagina').val(1);
		pesquisar = "&"+$("#formAvancado").serialize();
		dataTableProdutos();
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
			dataTableProdutos();
	});
	
	$('.table').on("click",".ordemUp",function(e){
		var params = {
			idprodutos: $(this).attr("codigo") 
		}

		$.post(
			'produtos_script.php?opx=alteraOrdemCima',
			params,
			function(data){
				var resultado = new String (data.status);

				if(resultado.toString() == 'sucesso'){
						dataTableProdutos();
				}
				else if (resultado == 'falha'){
						alert('N??o foi poss??vel atender a sua solicita????o.')
				}

			},'json'
		);
	});
	
	$('.table').on("click", ".ordemDown", function(e){
		var params = {
				idprodutos: $(this).attr("codigo") 
		}

		$.post(
			'produtos_script.php?opx=alteraOrdemBaixo',
			params,
			function(data){
				var resultado = new String (data.status);

				if(resultado.toString() == 'sucesso'){
					dataTableProdutos();
				}
				else if (resultado == 'falha'){
					alert('N??o foi poss??vel atender a sua solicita????o.')
				}

			},'json'
		);
	});

	$('.table').on("click", ".inverteStatus", function (e) {
		var params = {
			idprodutos: $(this).attr("codigo")
		}

		$.post(
			'produtos_script.php?opx=inverteStatus',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableProdutos();
				}
				else if (resultado == 'falha') {
					alert('N??o foi poss??vel atender a sua solicita????o.')
				}

			}, 'json'
		);
	});

}
var myColumnDefs = [
	{key:"idprodutos", sortable:true, label:"ID", print:true, data:true},
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
    {key:"nome_categoria", sortable:true, label:"Categoria", print:true, data:true}
]

function columnProdutos(){
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

function dataTableProdutos(){
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
						tr += '<a href="index.php?mod=produtos&acao=formProdutos&met=editProdutos&idu='+value.idprodutos+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
						tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idprodutos+' ?\',\'php\', \'produtos_script.php?opx=deletaProdutos&idu='+value.idprodutos+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
        location.href='index.php?mod=produtos&acao=listarProdutos';
    });
});