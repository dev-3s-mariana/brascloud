// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTablePlanos() {
	$("#limit").change(function () {
		$("#pagina").val(1);
		dataTablePlanos();
	});

	$("#pagina").keyup(function (e) {
		if (e.keyCode == 13) {
			if (totalPaginasGrid >= $(this).val() && $(this).val() > 0) {
				dataTablePlanos();
			} else {
				msgErro("numero de página deve ser entre 1 e " + totalPaginasGrid);
			}
		}
	});

	$(".next").click(function (e) {
		e.preventDefault();
		$("#pagina").val($(this).attr('proximo'));
		dataTablePlanos();
	});

	$(".prev").click(function (e) {
		e.preventDefault();
		$("#pagina").val($(this).attr('anterior'));
		dataTablePlanos();
	});


	//LISTAGEM BUSCA
	$("#buscarapida").keyup(function (event) {
		event.preventDefault();
		if (event.keyCode == '13') {
			$('#pagina').val(1);
			pesquisar = "&nome=" + $("#buscarapida").val();
			dataTablePlanos();
		}
		return true;
	});

	$("#filtrar").click(function (e) {
		e.preventDefault();
		$('#pagina').val(1);
		pesquisar = "&" + $("#formAvancado").serialize();
		dataTablePlanos();
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
		dataTablePlanos();
	});

   $('.table').on("click", ".inverteStatus", function (e) {
      var params = {
         idplanos: $(this).attr("codigo")
      }

      $.post(
         'planos_script.php?opx=inverteStatus',
         params,
         function (data) {
            var resultado = new String(data.status);

            if (resultado.toString() == 'sucesso') {
               dataTablePlanos();
            }
            else if (resultado == 'falha') {
               alert('Não foi possível atender a sua solicitação.')
            }

         }, 'json'
      );
   });

}
var myColumnDefs = [
	{ key: "idplanos", sortable: true, label: "ID", print: true, data: true },
	{ key: "nome", sortable: true, label: "Nome", print: true, data: true },
   // { key: "destaque_nome", sortable: true, label: "Destaque", print: true, data: true },
	{key:"status_icone", sortable:false, label:"Status",  print:false, data:true},
   {key:"status_nome", sortable:false, label:"Status",  print:true, data:false}
]
function columnPlanos() {
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
function dataTablePlanos() {
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
					tr += '<a href="index.php?mod=planos&acao=formPlanos&met=editPlanos&idu=' + value.idplanos + '"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
					tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro ' + value.nome + ' ?\',\'php\', \'planos_script.php?opx=deletaPlanos&idu=' + value.idplanos + '\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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

// FUNCAO VERIFICA URL
function verificaUrlrewrite(url) {
	id = $("#idplanos").val();

	$.ajax({
		url: 'planos_script.php',
		dataType: 'json',
		data: "opx=verificarUrlRewrite&idplanos=" + id + "&urlrewrite=" + url,
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

let imgRec = '';
function carregaIconeAcao() {
   $('#icone_pai').on("click", ".icone_icone", function (e) {
      e.preventDefault();
      var nome = $(this).data('nome');
      var id = $(this).data('id');
      var grid = $("#box_icons").attr("data-grid");
      var key = $("#box_icons").attr("data-key");
      $("#current-icon-"+grid+'-'+key).removeClass().addClass('fas fa-'+nome+' fa-2x');
      $("#imagem_icone-"+grid+'-'+key).val(id);
      $("#nome_icone-"+grid+'-'+key).val(nome);

      imgRec = $(".img-upload.img-"+key).attr('src');
      imgRec = imgRec.replace('files/itns/', '');

      $(".img-upload.img-"+key).attr('src','https://via.placeholder.com/30?text=Upload+Foto');
      $(".file-upload.upload-"+key).val('');
      $(".file-upload.upload-"+key).siblings('.nome-img-cadastrada').val('');
   });
}

function carregaIconeAcaoNotGrid() {
   $('#icone_pai-not-grid').on("click", ".icone_icone", function (e) {
      e.preventDefault();
      var nome = $(this).data('nome');
      var id = $(this).data('id');
      $("#current_icon").removeClass().addClass('fas fa-'+nome+' fa-2x');
      $("#imagem_icone").val(id);
      $("#icone_name").val(nome);
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
    Loader.show();
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
         Loader.hide();
      },
      error: function(xhr, ajaxOptions, thrownError){
         alert(xhr.responseText);
         alert(thrownError);
      }
   });
}

function iconePaginacaoNotGrid(pagina, pesquisa = ''){
    Loader.show();
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
            $("#icone_pai-not-grid").html('');
            for(var i=primeiroIcone;i>=primeiroIcone && i<ultimoIcone;i++){
                if(data.solid[i] != null){
                    append = '<div style="width:6%; display: inline-block;" data-toggle="tooltip" title="'+data.solid[i].replace("fas ","").replace("fa-","")+'">';
                    append += '<i class="'+data.solid[i]+' icone_icone" data-id="'+i+'" data-nome="'+data.solid[i].replace("fas ","").replace("fa-","")+'" title="'+data.solid[i].replace("fas ","").replace("fa-","")+'" style="padding:11px; cursor: pointer;"></i>';
                    append += '<span style="display: none;">'+data.solid[i].replace("fas ","").replace("fa-","")+'</span>';
                    append += '</div>';
                    $("#icone_pai-not-grid").append(append);
                }
            }
            if(pesquisa != ''){
                buscaIconeNotGrid(pesquisa);
            }
            Loader.hide();
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(xhr.responseText);
            alert(thrownError);
            Loader.hide();
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

function buscaIconeNotGrid() {
    // var search = $('#pesquisar_icone-not-grid').val().toLowerCase();
    // $.ajax({
    //     url: 'js/fontawesome-5.15.json',
    //     dataType: 'json',
    //     type: 'post',
    //     success: function(data){
    //         var teste = data.solid;
    //         var iconName = '';
    //         for (var i = 0; i < teste.length; i++) {
    //             iconName = teste[i];
    //             iconName = iconName.replace('fas fa-','');
    //             if(iconName.search(search) >= 0){
    //                 console.log(iconName);
    //             }
    //         }
    //     }
    // });
   var input, filter, div, tr, td, i, txtValue;
   input = document.getElementById("pesquisar_icone-not-grid");
   filter = input.value.toLowerCase();
   div = document.getElementById("icone_pai-not-grid");
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

function addGrid(nome, uploadImagem = true, width = 50, height = 50){
    var novoinput = '';
    var nomeCapitalized = nome.charAt(0).toUpperCase() + nome.slice(1);
    novoinput += '<tr class="box-'+nome+' remove'+nomeCapitalized+'-'+$('.box-'+nome).length+'" data-key="'+$('.box-'+nome).length+'">';
    novoinput += '<td align="center" class="td-padding">';
    if(uploadImagem == true){
        novoinput += '<img src="https://via.placeholder.com/50?text=Upload+Foto" width="50" class="img-upload img-'+$('.box-'+nome).length+'" data-key="'+$('.box-'+nome).length+'" />';
        novoinput += '<input type="file" name="'+nome+'['+$('.box-'+nome).length+'][imagem]" class="file-upload upload-'+$('.box-'+nome).length+'" data-key="'+$('.box-'+nome).length+'">';
        novoinput += '<span class="fs-11">Tamanho recomendado '+width+'x'+height+'px'+' </span>';
        novoinput += '<br/><span><b>OU</b></span>';
        novoinput += '<div id="mostrar_icone_'+nome+'-'+$('.box-'+nome).length+'" class="m-15">';
        novoinput += '<i id="current-icon-'+nome+'-'+$('.box-'+nome).length+'" data-grid="'+nome+'"  class="current-icon fas fa- fa-2x"></i>';
        novoinput += '</div>';
        novoinput += '<input type="button" value="Escolher ícone" data-grid="'+nome+'" class="btn-choose-icon button-escolher-icone btn button-escolher-icone-'+nome+'" data-key="'+$('.box-'+nome).length+'">';
    }
    novoinput += '<input type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][imagem]" value="">';
    novoinput += '<input type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][icone]" value="" id="imagem_icone-'+nome+'-'+$('.box-'+nome).length+'">';
    novoinput += '<input type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][nome_icone]" value="" id="nome_icone-'+nome+'-'+$('.box-'+nome).length+'">';
    novoinput += '<input type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][id'+nome+']" value="0">';
    novoinput += '<input id="excluir'+nomeCapitalized+'-'+$('.box-'+nome).length+'" type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][excluirRecurso]" value="1">';
    novoinput += '</td>';

    novoinput += '<td colspan="2">';
    novoinput += '<input type="text" class="box_txt input'+nomeCapitalized+' w-100" name="'+nome+'['+$('.box-'+nome).length+'][nome]" placeholder="Titulo">';
    // novoinput += '<textarea rows="6" type="text" style="resize: vertical" class="box_txt input'+nomeCapitalized+' w-100" name="'+nome+'['+$('.box-'+nome).length+'][descricao]" placeholder="Descrição"></textarea>';
    novoinput += '</td>';

    novoinput += '<td align="center">';
    novoinput += '<span class="td-flex">'
    novoinput += '<span class="subir'+nomeCapitalized+'" data-key="'+$('.box-'+nome).length+'">'
    novoinput += '<b class="fas fa-arrow-up"></b>'
    novoinput += '</span>'
    novoinput += '<span class="descer'+nomeCapitalized+'" data-key="'+$('.box-'+nome).length+'">'
    novoinput += '<b class="fas fa-arrow-down"></b>'
    novoinput += '</span>'
    novoinput += '<span class="excluir'+nomeCapitalized+'" data-key="'+$('.box-'+nome).length+'">'
    novoinput += '<b class="fas fa-trash"></b>'
    novoinput += '</span>'
    novoinput += '<input type="hidden" name="'+nome+'['+$('.box-'+nome).length+'][ordem]" value="'+$('.box-'+nome).length+'">';
    novoinput += '</span>'
    novoinput += '</td>';
    novoinput += '</tr>';

    novoinput += '<tr class="remove'+nomeCapitalized+'-'+$('.box-'+nome).length+'">';
    novoinput += '<td colspan="4">';
    novoinput += '<div style="padding: 0 0 0 10px; width: 100%" data-grid="'+nome+'" data-key="'+$('.box-'+nome).length+'" class="div-show-icons box_ip div-icones" style="width: 100% !important;"></div>';
    novoinput += '</td>';
    novoinput += '</tr>';
    $('.'+nome).append(novoinput);
}

$(document).ready(function () {

    $('.bt_save').click(function(){
        if(imgRec != ''){
            excluirImagemOld('itns', imgRec);
        }
    });

    $('.botaoArquivo').click(function(){
        $('#icone_upload').click();
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
        $("#imagem_value").val($(this)[0].files[0].name);
        $("#inputArquivoBotao").find('.btn').val($(this)[0].files[0].name);
    });

    $(".excluir-arquivo").click(function(e){
      e.preventDefault();
      var thisElement = $(this);
      $.ajax({
         url: "planos_script.php",
         type: "POST",
         dataType: "json",
         data: {opx: 'excluirArquivo', idplanos: $("#idplanos").val(), arquivo: $(thisElement).attr('data-arquivo')},
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

	/////////////////URLREWRITE///////////////////////////////////
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

   //Static Icons
      $("#tab-1-not-grid").append($("#box_icons-not-grid"));
      $("#box_icons-not-grid").show();

      iconePaginacaoNotGrid(1);

      var pagsIcone = iconeQuantidade().done(function(data){
         var paginas = 0;
         $("#div-page-icon-not-grid").append('<span class="span-page-chevron-not-grid" data-direction="left"><i class="fas fa-chevron-left"></i></span>');
         for (var i = 1; i <= data.solid.length; i+=390) {
            paginas+=1;
            if(i == 1){
               $("#div-page-icon-not-grid").append('<span class="span-choose-icon-not-grid span-page-active-not-grid pagination-not-grid" data-page="'+paginas+'">'+paginas+'</span>');
            }
            else{
               $("#div-page-icon-not-grid").append('<span class="span-choose-icon-not-grid span-page pagination-not-grid" data-page="'+paginas+'">'+paginas+'</span>');
            }
         }
         $("#div-page-icon-not-grid").append('<span class="span-page-chevron-not-grid" data-direction="right"><i class="fas fa-chevron-right"></i></span>');
      });

      $("#div-page-icon-not-grid").on("click", ".pagination-not-grid", function(e){
         if($('input#pesquisar_icone-not-grid').val() != ''){
            var pesquisa = $('input#pesquisar_icone-not-grid').val();
            iconePaginacaoNotGrid($(this).attr('data-page'), pesquisa);
         }else{
            iconePaginacaoNotGrid($(this).attr('data-page'));
         }
         $(this).removeClass("span-page").addClass("span-page-active-not-grid");
         $(this).siblings().not(".span-page-chevron-not-grid").removeClass("span-page-active-not-grid").addClass("span-page");
      });

      $("#div-page-icon-not-grid").on("click", ".span-page-chevron-not-grid", function(){
         var paginaSelecionada = $(".span-page-active-not-grid").data('page');
         if($(this).data("direction") == "right"){
            if((paginaSelecionada+1) <= $(".pagination-not-grid").length){
               if($('input#pesquisar_icone-not-grid').val() != ''){
                  var pesquisa = $('input#pesquisar_icone-not-grid').val();
                  iconePaginacaoNotGrid(paginaSelecionada+1, pesquisa);
               }
               else{
                  iconePaginacaoNotGrid(paginaSelecionada+1);
               }
               $(".span-choose-icon-not-grid").each(function(){
                  if($(this).data("page") == (paginaSelecionada+1)){
                     $(this).removeClass("span-page").addClass("span-page-active-not-grid");
                     $(this).siblings().not(".span-page-chevron-not-grid").removeClass("span-page-active-not-grid").addClass("span-page");
                  }
               });
            }
         }
         else{
            if((paginaSelecionada-1) >= 1){
               if($('input#pesquisar_icone-not-grid').val() != ''){
                  var pesquisa = $('input#pesquisar_icone-not-grid').val();
                  iconePaginacaoNotGrid(paginaSelecionada-1, pesquisa);
               }
               else{
                  iconePaginacaoNotGrid(paginaSelecionada-1);
               }
               $(".span-choose-icon-not-grid").each(function(){
                  if($(this).data("page") == (paginaSelecionada-+1)){
                     $(this).removeClass("span-page").addClass("span-page-active-not-grid");
                     $(this).siblings().not(".span-page-chevron-not-grid").removeClass("span-page-active-not-grid").addClass("span-page");
                  }
               });
            }
         }
      });

      $('input#pesquisar_icone-not-grid').keyup(function (event) {
         var pesquisa = $(this).val();
         buscaIconeNotGrid(pesquisa);
      });

      carregaIconeAcaoNotGrid();
   //End Static Icons

   $('ul.tabs li').click(function () {
      var tab_id = $(this).attr('data-tab');

      $('ul.tabs li').removeClass('current');
      $('.tab-content').removeClass('current');

      $(this).addClass('current');
      $("#" + tab_id).addClass('current');
   });

   $("#formPlanos").on("click", ".btn-choose-icon", function(){
      var grid = $(this).attr("data-grid");
      var key = $(this).attr("data-key");
      // console.log(grid);
      $(".div-show-icons").each(function(){
         if($(this).attr("data-grid") == grid && $(this).attr("data-key") == key){
            // console.log($(this).attr("data-grid"));
            $("#box_icons").appendTo($(this));
            $("#box_icons").attr("data-grid",grid);
            $("#box_icons").attr("data-key",key);
            $("#box_icons").show();
         }
      });
   });

   //===== Ícones =====//
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
   //===== Fim ícones =====//

   //===== Grid itens =====//
      $(".btn-itns").on('click', function () {
         addGrid('itns', true, 30, 30);
      });

      $(".btn-testes").on('click', function () {
         addGrid('testes', false, 40, 40);
      });

      $(document).on('click', '.img-upload', function(){
         var key = $(this).parent().parent().attr('data-key');
         $(".upload-"+key).trigger("click");
      });

      $(document).on('change', ".file-upload", function(){
         var key = $(this).parent().parent().attr('data-key');
         var grid = $(this).attr('data-grid');
         if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
               $(".img-"+key).attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);

            $("#current-icon-"+grid+"-"+key).removeClass();
            $("#imagem_icone-"+grid+"-"+key).val('');
            $("#nome_icone-"+grid+"-"+key).val('');
         }
      });

      // $(document).on('click', '.icone-upload', function(){
      //    var key = $(this).attr('data-key');
      //    $(".upload-icone-"+key).trigger("click");
      // });

      // $(document).on('change', ".file-icone-upload", function(){
      //    var key = $(this).attr('data-key');
      //    if (this.files && this.files[0]) {
      //       var reader = new FileReader();
      //       reader.onload = function (e) {
      //          $(".icone-"+key).attr('src', e.target.result)
      //       };
      //       reader.readAsDataURL(this.files[0]);
      //    }
      // });

        $(document).on("click", ".subirItns", function () {
            var thisDataKey = $(this).parent().parent().parent().attr('data-key');
            var auxDiv = $('.div-aux');
            var thisTr = $(this).parent().parent().parent();
            var prevTr = $(thisTr).prevAll('.box-itns').first();
            var thisInputPos = $(thisTr).find('.td-flex').find('input');
            var prevInputPos = $(prevTr).find('.td-flex').find('input');
            var auxInputPos = $(thisInputPos).val();
            if($(prevTr).length > 0){
                $(thisInputPos).val($(prevInputPos).val());
                $(prevInputPos).val(auxInputPos);
                $(thisTr).children().appendTo(auxDiv);
                $(prevTr).children().appendTo(thisTr);
                $(auxDiv).children().appendTo(prevTr);
            }
        });

        $(document).on("click", ".descerItns", function () {
            var thisDataKey = $(this).parent().parent().parent().attr('data-key');
            var auxDiv = $('.div-aux');
            var thisTr = $(this).parent().parent().parent();
            var nextTr = $(thisTr).nextAll('.box-itns').first();
            var thisInputPos = $(thisTr).find('.td-flex').find('input');
            var nextInputPos = $(nextTr).find('.td-flex').find('input');
            var auxInputPos = $(thisInputPos).val();
            if($(nextTr).length > 0){
                $(thisInputPos).val($(nextInputPos).val());
                $(nextInputPos).val(auxInputPos);
                $(thisTr).children().appendTo(auxDiv);
                $(nextTr).children().appendTo(thisTr);
                $(auxDiv).children().appendTo(nextTr);
            }
        });

        $(document).on("click", ".excluirItns", function () {
            var key = $(this).parent().parent().parent().attr('data-key');
            $('.removeItns-'+key).hide();
            // $('.removeItns-'+key).next('tr').remove();
            $('#excluirRecurso-'+key).val('0');
            // $('.removeItns-'+key).remove();
        });

        $(document).on("click", ".subirTestes", function () {
            var thisDataKey = $(this).parent().parent().parent().attr('data-key');
            var auxDiv = $('.div-aux');
            var thisTr = $(this).parent().parent().parent();
            var prevTr = $(thisTr).prevAll('.box-testes').first();
            var thisInputPos = $(thisTr).find('.td-flex').find('input');
            var prevInputPos = $(prevTr).find('.td-flex').find('input');
            var auxInputPos = $(thisInputPos).val();
            if($(prevTr).length > 0){
                $(thisInputPos).val($(prevInputPos).val());
                $(prevInputPos).val(auxInputPos);
                $(thisTr).children().appendTo(auxDiv);
                $(prevTr).children().appendTo(thisTr);
                $(auxDiv).children().appendTo(prevTr);
            }
        });

        $(document).on("click", ".descerTestes", function () {
            var thisDataKey = $(this).parent().parent().parent().attr('data-key');
            var auxDiv = $('.div-aux');
            var thisTr = $(this).parent().parent().parent();
            var nextTr = $(thisTr).nextAll('.box-testes').first();
            var thisInputPos = $(thisTr).find('.td-flex').find('input');
            var nextInputPos = $(nextTr).find('.td-flex').find('input');
            var auxInputPos = $(thisInputPos).val();
            if($(nextTr).length > 0){
                $(thisInputPos).val($(nextInputPos).val());
                $(nextInputPos).val(auxInputPos);
                $(thisTr).children().appendTo(auxDiv);
                $(nextTr).children().appendTo(thisTr);
                $(auxDiv).children().appendTo(nextTr);
            }
        });

        $(document).on("click", ".excluirTestes", function () {
            var key = $(this).parent().parent().parent().attr('data-key');
            $('.removeTestes-'+key).hide();
            // $('.removeTestes-'+key).next('tr').remove();
            $('#excluirRecurso-'+key).val('0');
            // $('.removeTestes-'+key).remove();
        });
    //===== Fim grid itens =====//

   ////////////////////////////////////////////
   ///////// GALERIA DE IMAGENS ///////////////
   ////////////////////////////////////////////
 
      // ABRIR O BOX DE DESCRIÇÃO - da imagem
      $("#content-image").on("click",".editImagemDescricao", function(e){
          e.preventDefault();           
          $("#formDescricaoImagem").find("#idImagem").val($(this).attr('idimagem'));
          var idimagemdescricao = $(this).attr('idimagem');
          var posImagem = $(this).closest("li").attr("id");
          $("#formDescricaoImagem").find("#descricao_imagem").val($(this).closest("li").find("input[name='descricao_imagem[]']").val());
          $("#formDescricaoImagem").find("#posImagem").val(posImagem); 
          $( "#boxDescricao" ).dialog({
              resizable: true,
              height:140,
              width:500,
              modal: true,
              title:'Descrição da imagem:',
              open:function(event,ui){
                $(this).find('.ui-dialog .ui-dialog-content').css('background-image','none!important;');
              } 
          }); 
      });  

      //SALVAR DESCRIÇÃO - confirmacao da descricao da imagem
      $("#boxDescricao").on("click",".btSaveDescricao",function(e){
         e.preventDefault(); 
         descricao = $("#boxDescricao").find("#descricao_imagem").val();
         idImagem =  $("#boxDescricao").find("#idImagem").val(); 
         refImagem = $("#boxDescricao").find("#posImagem").val(); 
         $("#content-image li#"+refImagem).find("input[name='descricao_imagem[]']").val(descricao);

         if($("#mod").val() == "editar"){ 
             //se for editando - salva direto no banco de dados
             $.ajax({
                 url:'planos_script.php',
                 data:{ 
                     opx:'salvarDescricao',
                     idImagem: idImagem,
                     descricao: descricao
                 },
                 dataType:'json',
                 type:'post',
                 beforeSend:function(){
                     Loader.show();
                 },
                 success:function(data){

                     if(data.status == true){
                       $("#boxDescricao").dialog("close");
                       Loader.hide();
                       msgSucesso('Descrição salva com sucesso');
                     }else{
                       Loader.hide();
                       msgErro('Erro ao salvar descrição');
                     }
                 } 
             }); 
         }else{
            $("#boxDescricao").dialog("close");
         }
      }); 

      //BOTÃO EXCLUIR - na imagem       
      $("#content-image").on("click",".excluirImagem",function(e){
         e.preventDefault(); 
         ref = $(this).closest("li"); 
         
         $("#formDeleteImagem").find("#idPosicao").val($(ref).attr('id'));

         var idimagemdescricao = $(ref).attr('idimagem');
         $( "#excluirImagem" ).dialog({
             resizable: true,
             height:140,
             width:330,
             modal: true,  
             title:'Excluir imagem'    
         }); 
      }); 

      //EXCLUI A FOTO SELECIONADA
      $(".btExcluirImagem").click(function(e){
         
         e.preventDefault();
         idPosicao = $("#formDeleteImagem").find("#idPosicao").val();        
         idplanos = $("#formPlanos").find("#idplanos").val();
         idplanos_imagem = $("#"+idPosicao).find("input[name='idplanos_imagem[]']").val();
         imagem = $("#"+idPosicao).find("input[name='imagem_planos[]']").val();
         ref = $("#"+idPosicao); 
          
         imagemDelete = $("#"+idPosicao).find("img").attr("src"); 
         imagemDelete = $("#_endereco").val()+imagemDelete.replace('galeria/thumb/',"galeria/original/");  
         
         //excluir imagem do post no TINYMCE 
         // var post = tinyMCE.get("descricao").getContent();
         // imagePost =  tinyMCE.get("descricao").dom.select('img');
         // $.each(imagePost, function(nodes, name) {
         //    img = tinyMCE.get("descricao").dom.select('img')[nodes]; 
         //    img = $(img)[0];  

         //    if(img.src == imagemDelete){ 
         //       img.remove();
         //    }
         // });

         // var post2 = tinyMCE.get("descricao").getContent(); 
         $.ajax({
            url:'planos_script.php',
            type:'post',
            dataType:'json', 
            data:
            {
              opx:'excluirImagemGaleria',
              idplanos:idplanos,            
              imagem:imagem,
              idplanos_imagem:idplanos_imagem
              // descricao: post2
            },
            beforeSend:function(){
              Loader.show();
            },        
            success:function(data){
                if(data.status){ 
                    msgSucesso('Imagem excluída com sucesso!');
                    $(ref).remove();
                    resetOrdemImagens();
                }else{
                    msgErro('Erro ao excluir imagem, tente novamente');  
                }
            },
            complete:function(){
              Loader.hide();
              $("#excluirImagem").dialog("close");
            }  
         });
      });


      //EXCLUI A FOTO SELECIONADA
      $(".btCancelarExclusao").click(function(e){
         $("#excluirImagem").dialog("close");
      }); 

      //BOTÃO POST - subir a imagem no texto     
      $("#content-image").on("click",".postImagem",function(e){
         e.preventDefault(); 
         postImagem($(this)); 
      }); 


       //DRAG N DROP 
      $( "#sortable" ).sortable({   
         update: function(event, ui){
            resetOrdemImagens(); 
         }
      });

      //SORTABLE IMAGES
      $( "#sortable" ).disableSelection();  

      $("#image").change(function(){              
         enviaImagens(this);  
      });

   ////////////////////////////////////////////////
   ///////// FIM GALERIA DE IMAGENS ///////////////
   ////////////////////////////////////////////////

    $('.cropped-image').change(function(){
        if($(this).attr('id') == 'inputImage'){
            $('#img-container > img').cropper('setAspectRatio', $("#aspectRatioW").val()/$("#aspectRatioH").val());
            $('#cropper-modal').appendTo('#select-image-1');
            $('.save-cropped-image').attr('data-image-type','imagem');
            $('.save-cropped-image').show();
        }else{
            $('#img-container > img').cropper('setAspectRatio', $("#aspectRatioW2").val()/$("#aspectRatioH2").val());
            $('#cropper-modal').appendTo('#select-image-2');
            $('.save-cropped-image').attr('data-image-type','banner_topo');
            $('.save-cropped-image').show();
        }
    });

    $('.save-cropped-image').click(function(e){
        e.preventDefault();
        var dataImageType = $(this).attr('data-image-type');
        var formData = new FormData();
        var coordenadas = $("#img-container>img").cropper('getData');
        coordenadas = JSON.stringify(coordenadas, null, 4);

        if(dataImageType == 'imagem'){
            formData.append('imagemCadastrar', document.getElementById('inputImage').files[0]);
        }else{
            formData.append('imagemCadastrar', document.getElementById('inputImage2').files[0]);
        }
        formData.append('coordenadas', coordenadas);
        formData.append('opx', 'salvaImagem');
        formData.append('tipo', dataImageType);
        formData.append("idplanos", $('input[name=idplanos]').val());
        if(dataImageType == 'imagem'){
            formData.append("dimensaoWidth", $("#aspectRatioW").val());
            formData.append("dimensaoHeight", $("#aspectRatioH").val());
        }else{
            formData.append("dimensaoWidth", $("#aspectRatioW2").val());
            formData.append("dimensaoHeight", $("#aspectRatioH2").val());
        }

        $.ajax({
            url: "planos_script.php",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend:function(){
                Loader.show();
            },
            success:function(data){
                console.log(data);
                if(data.status == true){
                    if(dataImageType == 'imagem'){
                        $('#imagem-value').val(data.imagem);
                        $('#select-image-1 .img-planos-form').attr('src', 'files/planos/'+data.imagem);
                        $('#select-image-1 .img_pricipal .box_ip').show();
                        $('.excluir-imagem[data-tipo='+dataImageType+']').attr('data-img',data.imagem);
                        $('.excluir-imagem[data-tipo='+dataImageType+']').show();
                    }else{
                        $('#imagem_2-value').val(data.imagem);
                        $('#select-image-2 .img-planos-form').attr('src', 'files/planos/'+data.imagem);
                        $('#select-image-2 .img_pricipal .box_ip').show();
                        $('.excluir-imagem[data-tipo='+dataImageType+']').attr('data-img',data.imagem);
                        $('.excluir-imagem[data-tipo='+dataImageType+']').show();
                    }
                    $('#img-container').hide();
                    $('.save-cropped-image').hide();
                }
                Loader.hide();
            }
        });
    });

    $(".cancelar").click(function(event){
        event.preventDefault();
        if($("#imagem-value").val() == '' && $("#imagem_2-value").val() == ''){
            location.href='index.php?mod=planos&acao=listarPlanos';
        }
        else{
            if($("#idplanos").val() == 0){
                if($("#imagem-value").val() != ''){
                    cancelarImagem('planos', $("#imagem-value").val());
                }
                if($("#imagem_2-value").val() != ''){
                    cancelarImagem('planos', $("#imagem-value").val());
                }
            }
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
        excluirImagem('planos', tipo, img, thisElem);
        $('.ui-dialog-titlebar-close').click();
    });

    $(".cancel").click(function(){
        $('.ui-dialog-titlebar-close').click();
    });
});

function cancelarImagem(pasta, imagem){
    $.ajax({
        url: 'planos_script.php',
        dataType: 'json',
        type: 'post',
        data: {opx: 'cancelarImagem', pasta: pasta, imagem: imagem},
        success: function(){
            location.href='index.php?mod=planos&acao=listarPlanos';
        }
    });
}

// functions usadas na galeria
function verificaExt(input){
  //passar o input.files[i] 

  //verifica o tipo do arquivo

  switch(input.type){

    //jpg permitido

    case 'image/jpeg':

      return true;

    break;

    //jpg permitido

    case 'image/png':

      return true;

    break;

    //jpg permitido

    case 'image/gif':

      return true;

    break;

    default:

      return false;

    break;  
  }
}

//VERIFICA A IMAGEM A SER ENVIADA

function enviaImagens(input){ 
  //variável com a posição da imagem;
  quantidadeimagem = $("#sortable").find('li').length; 
  //quantas imagens estão sendo enviadas;
  var totalimagens = input.files.length;
  //tamanho máximo da imagem permitida pelo servidor;
  var tamanhoMaximo;
  tamanhoMaximo = ($("#fileMax").val())*1000000;
  var erros = "";

  numImagem = totalimagens;
  //trata cada dado de arquivo enviado pelo input
  for(var i =0; i<totalimagens; i++ ){        
    Loader.show(); 
    if (input.files && input.files[i]){//verifica se tem dados no input  
        if(verificaExt(input.files[i])){//se valida a extensao do arquivo  
            if(input.files[i].size > tamanhoMaximo){  
                erros += 'A imagem "'+input.files[i].name+'"'+' não foi enviada, pois, seu tamanho excede '+$("#fileMax").val()+'MB <br />';         
            }else{ 
              Loader.show();
              quantidadeimagem++; 
              enviaImagensAjax(input.files[i], quantidadeimagem, totalimagens);
             } 
        }else{//se não valida a extensao do arquivo  
            erros += 'A imagem "'+input.files[i].name+'"'+' não foi enviada, pois, sua extensão não é válida <br />'; 
           }

    }else{ 
      erros += 'Erro: O arquivo: "'+input.files[i].name+'" não foi enviado <br />'; 
   }  
  }  

  if(erros != ""){

    msgErro(erros);

  }
}

//sobe a imagem

function enviaImagensAjax(input, posicao, limite){ 
 
  var formData = new FormData();   
  formData.append('opx', 'salvarGaleria');   
  formData.append('imagem', input); 
  formData.append('idplanos', $("#idplanos").val()); 
  formData.append('posicao', posicao); 
 
  $.ajax({ 
    url: "planos_script.php", 
    type: "POST", 
    dataType: "json", 
    data: formData, 
    processData: false,  // tell jQuery not to process the data 
    contentType: false,   // tell jQuery not to set contentType  
    //SE DER TUDO CERTO NO AJAX TEMOS QUE MUDAR ALGUMAS COISAS NOS "appends" ANTERIORES
    beforeSend:function(){ 
      Loader.show();  
      $(".ui-sortable").css('opacity',0.3);   
    }, 
    success:function(data){  
        if(data.status == true){ 
            $li = '<li class="ui-state-default'+posicao+' move" id="'+posicao+'" idimagem="'+data.idplanos_imagem+'">';
            $li += '<img id="img'+posicao+'" class="imagem-gallery" src="'+data.caminho+'">';
            $li += '<a class="editImagemDescricao" idimagem="'+data.idplanos_imagem+'" href="#"><button class="edit"></button></a>';
            $li += '<a class="excluirImagem" idimagemdelete="'+data.idplanos_imagem+'" href="#"><button class="delete"></button></a>';
            // $li += '<a class="postImagem" idimagempost="'+data.idplanos_imagem+'" href="#"><button class="post_imagem"></button></a>'; 
            // $li += '<a class="postImagem" idimagempost="'+data.idplanos_imagem+'" href="#"><button class="post_imagem"></button></a>';
            $li += '<input type="hidden" name="idplanos_imagem[]" value="'+data.idplanos_imagem+'">'; 
            $li += '<input type="hidden" name="descricao_imagem[]" value="">'; 
            $li += '<input type="hidden" name="imagem_planos[]" value="'+data.nome_arquivo+'">';
            $li += '<input type="hidden" name="posicao_imagem[]" value="'+posicao+'">';
            $li += '</li>'; 
            $("#sortable").append($li); 
            $("#idplanos").val(data.idplanos); 
            if(numImagem > 1){
              numImagem = numImagem -1;
            }else{ 
              Loader.hide();
              $("#sortable").removeAttr("style");  
            } 
        }//fim if
        else{
            msgErro('Erro ao enviar imagem, por favor tente novamente!'); 
        }  
    } 
  });  
  //fim AJAX  
} 

//ORDENA A POSICAO DAS IMAGENS SE UMA IMAGEM É APAGADA

function resetOrdemImagens(){
 

  $lis = $("#sortable").find("li"); 

  $.each($lis, function(index, value){ 
      pos = parseInt(index) + parseInt(1); 
      $(this).removeClass();  
      $(this).addClass("ui-state-default"+ pos + " move");  
      $(this).attr("id", pos);  
      $(this).find("input[name='posicao_imagem[]']").val(pos); 
  }); 



  if($("#mod").val() == "editar"){  
    //editar a ordem das imagens  
    form = $("#formPlanos").serialize(); 

    $.ajax({ 
        url: "planos_script.php", 
        type: "POST", 
        dataType: "json",  
        data: "opx=alterarPosicaoImagem&"+form, 
        beforeSend:function(){ 
            Loader.show();   
        }, 
        success:function(data){  
            if(data.status == true){  
                Loader.hide();   
            }  
            else{ 
                msgErro('Erro ao alterar posição da imagem. Tente novamente'); 
             }  
        }, 
        complete:function(data){ 
            Loader.hide();  
        } 
      });  
  }
}