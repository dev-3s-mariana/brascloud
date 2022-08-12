// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableDepoimento() {
	$("#limit").change(function () {
		$("#pagina").val(1);
		dataTableDepoimento();
	});

	$("#pagina").keyup(function (e) {
		if (e.keyCode == 13) {
			if (totalPaginasGrid >= $(this).val() && $(this).val() > 0) {
				dataTableDepoimento();
			} else {
				msgErro("numero de pagina deve ser entre 1 e " + totalPaginasGrid);
			}
		}
	});

	$(".next").click(function (e) {
		e.preventDefault();
		$("#pagina").val($(this).attr('proximo'));
		dataTableDepoimento();
	});

	$(".prev").click(function (e) {
		e.preventDefault();
		$("#pagina").val($(this).attr('anterior'));
		dataTableDepoimento();
	});


	//LISTAGEM BUSCA
	$("#buscarapida").keyup(function (event) {
		event.preventDefault();
		if (event.keyCode == '13') {
			$('#pagina').val(1);
			pesquisar = "&nome=" + $("#buscarapida").val();
			dataTableDepoimento();
		}
		return true;
	});

	$("#filtrar").click(function (e) {
		e.preventDefault();
		$('#pagina').val(1);
		pesquisar = "&" + $("#formAvancado").serialize();
		dataTableDepoimento();
	});

	$(".ordem").click(function (e) {
		e.preventDefault();
		ordem = $(this).attr("ordem");
		dir = $(this).attr("order");
		$(".ordem").removeClass("action");
		$(".ordem").removeClass("actionUp");
		if ($(this).attr("order") == "asc") {
			$(this).attr("order", "desc");
			$(this).removeClass("action");
			$(this).addClass("actionUp");
		} else {
			$(this).attr("order", "asc");
			$(this).removeClass("actionUp");
			$(this).addClass("action");
		}
		dataTableDepoimento();
	});

	$('.table').on("click", ".ordemUp", function (e) {
		var params = {
			iddepoimento: $(this).attr("codigo")
		}

		$.post(
			'depoimento_script.php?opx=alteraOrdemCima',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableDepoimento();
				}
				else if (resultado == 'falha') {
					alert('Não foi possível atender a sua solicitação.')
				}

			}, 'json'
		);
	});

	$('.table').on("click", ".ordemDown", function (e) {
		var params = {
			iddepoimento: $(this).attr("codigo")
		}

		$.post(
			'depoimento_script.php?opx=alteraOrdemBaixo',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableDepoimento();
				}
				else if (resultado == 'falha') {
					alert('Não foi possível atender a sua solicitação.')
				}

			}, 'json'
		);
	});

	$('.table').on("click", ".inverteStatus", function (e) {
		var params = {
			iddepoimento: $(this).attr("codigo")
		}

		$.post(
			'depoimento_script.php?opx=inverteStatus',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableDepoimento();
				}
				else if (resultado == 'falha') {
					alert('Não foi possível atender a sua solicitação.')
				}

			}, 'json'
		);
	});

}

var myColumnDefs = [
	{ key: "iddepoimento", sortable: true, label: "ID", print: true, data: false },
	{ key: "imagem", sortable: false, label: "Imagem", print: true, data: false },
	{ key: "imagem-caminho", sortable: false, label: "Imagem", print: false, data: true },
	{ key: "nome", sortable: true, label: "Nome", print: true, data: true },
	{ key: "subtitulo", sortable: true, label: "Subtítulo", print: true, data: true },
	{ key: "depoimento", sortable: true, label: "Depoimento", print: true, data: false },
	{ key: "ordem", sortable: false, label: "Ordem", print: true, data: false },
	{ key: "ordemUp", sortable: false, label: "Subir", print: false, data: true },
	{ key: "ordemDown", sortable: false, label: "Descer", print: false, data: true },
	{ key: "status_icone", sortable: false, label: "Status", print: false, data: true },
	{ key: "status_nome", sortable: false, label: "Status", print: true, data: false }
]

function columnDepoimento() {
	tr = "";
	$.each(myColumnDefs, function (col, ColumnDefs) {
		if (ColumnDefs['data']) {
			orderAction = "";
			ordena = "";
			if (ColumnDefs['key'] == ordem) {
				if (dir == "desc") {
					orderAction = "actionUp";
				} else {
					orderAction = "action";
				}
			}
			if (ColumnDefs['sortable']) {
				ordena = 'ordem="' + ColumnDefs['key'] + '" class="ordem ' + orderAction + '" order="' + dir + '"';
			}
			tr += '<th><a href="#" ' + ordena + '>' + ColumnDefs['label'] + '</a></th>';
		}
	});
	tr += "<th></th>";
	$('#listagem').find("thead").append(tr);
}
function dataTableDepoimento() {
	limit = $("#limit").val();
	pagina = $("#pagina").val();
	pagina = parseInt(pagina) - 1;
	colunas = myColumnDefs;
	colunas = JSON.stringify(colunas);
	queryDataTable = requestInicio + "&ordem=" + ordem + pesquisar + "&dir=" + dir + "&colunas=" + colunas;
	$.ajax({
		url: "base_proxy.php",
		dataType: "json",
		type: "post",
		data: requestInicio + "&limit=" + limit + "&pagina=" + pagina + "&ordem=" + ordem + pesquisar + "&dir=" + dir,
		beforeSend: function () {
			Loader.show();
			$('#listagem').find("tbody tr").remove();
		},
		success: function (data) {
			tr = "";
			if (data.totalRecords > 0) {
				$.each(data.records, function (index, value) {
					tr += '<tr>';
					$.each(myColumnDefs, function (col, ColumnDefs) {
						if (ColumnDefs['data']) {
							key = ColumnDefs['key'];
							tr += '<td><span>' + value[key] + '</span></td>';
						}
					});

					tr += '<td><div class="acts">';
					tr += '<a href="index.php?mod=depoimento&acao=formDepoimento&met=editDepoimento&idu=' + value.iddepoimento + '"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
					tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro ' + value.iddepoimento + ' ?\',\'php\', \'depoimento_script.php?opx=deletaDepoimento&idu=' + value.iddepoimento + '\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
					tr += '</div></td>';
				});
				$('#listagem').find("tbody").append(tr);
				atualizaPaginas(data.pageSize, (pagina + 1), data.totalRecords);
				$('.pagination').show();
			} else {
				$('#listagem').find("tbody").append('<tr class="odd pesquisa_error"><td colspan="' + myColumnDefs.length + '">Nenhum resultado encontrado</td></tr>');
				$('.pagination').hide();
			}
		},
		complete: function () {
			Loader.hide();
		}
	});
}

$(document).ready(function () {

    $(".cancelar").click(function(event){
        location.href='index.php?mod=depoimento&acao=listarDepoimento';
    });

	$(".bt_save").click(function (event) {
		event.preventDefault();

        var valida = true;
        msg = "";
        $("#inputImage").css("border", "solid 1px  #e2e4e7");
        $('#formDepoimento').find('.required').each(function(){
              $(this).css("border","1px solid #e2e4e7");

              if($(this).attr('name') == 'imagemCadastrar' && $(this).val() == ""){ 
                  	$("#inputImage").css("border", "solid 1px  red");
                  	valida = false; 
              }
              else{
                  if($.trim($(this).val())==''){
                      	$(this).css("border", "solid 1px  red");
                      	valida = false;
                  }
              }
        });

        if(valida){
            Loader.show(); 
   //          if ($("#inputImage").val() != '') {
			// 	coordenadas = $(".img-container>img").cropper('getData');
			// 	coordenadas = JSON.stringify(coordenadas, null, 4);
			// 	$("#coordenadas").val(coordenadas);
			// }
            $('#formDepoimento').submit();
        }else{
           msgErro('Preencha o(s) campo(s) obrigatórios!');
        }
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
        excluirImagem('depoimento', tipo, img, thisElem);
        $('.ui-dialog-titlebar-close').click();
    });

    $(".cancel").click(function(){
        $('.ui-dialog-titlebar-close').click();
    });
});