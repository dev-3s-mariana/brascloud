// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableTimeline(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableTimeline();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableTimeline();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableTimeline();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableTimeline();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&titulo="+$("#buscarapida").val();
	            dataTableTimeline();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
            $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableTimeline();
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
	         dataTableTimeline();
	    });


	    $('.table').on("click", ".inverteStatus", function (e) {
		var params = {
			idtimeline: $(this).attr("codigo")
		}

		$.post(
			'timeline_script.php?opx=inverteStatus',
			params,
			function (data) {
				var resultado = new String(data.status);

				if (resultado.toString() == 'sucesso') {
					dataTableTimeline();
				}
				else if (resultado == 'falha') {
					alert('Não foi possível atender a sua solicitação.')
				}

			}, 'json'
		);
	});

}
	var myColumnDefs = [
		{key:"idtimeline", sortable:true, label:"ID", print:true, data:true},
		{key:"titulo", sortable:true, label:"Título", print:true, data:true},
		{key:"ano", sortable:false, label:"Ano", print:false, data:true},
    	{key:"status_icone", sortable:false, label:"Status",  print:false, data:true},
    	{key:"status_nome", sortable:false, label:"Status",  print:true, false:false} 
	]
function columnTimeline(){
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
function dataTableTimeline(){
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
                                tr += '<a href="index.php?mod=timeline&acao=formTimeline&met=editTimeline&idu='+value.idtimeline+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.titulo_aba+' ?\',\'php\', \'timeline_script.php?opx=deletaTimeline&idu='+value.idtimeline+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
    $(".cancelar").click(function(e){
        e.preventDefault();
        if($("#idtimeline").val() == 0){
            if($("#imagem-value").val() != ''){
                cancelarImagem('timeline', $("#imagem-value").val());
            }
        }
        else{
            location.href='index.php?mod=timeline&acao=listarTimeline';
        }
    });

    $('.cropped-image').change(function(){
        if($(this).attr('id') == 'inputImage'){
            $('#img-container > img').cropper('setAspectRatio', $("#aspectRatioW").val()/$("#aspectRatioH").val());
            $('#cropper-modal').appendTo('#select-image-1');
            // $('#img-container').show();
            $('.save-cropped-image').attr('data-image-type','imagem');
            $('.save-cropped-image').show();
        }else{
            $('#img-container > img').cropper('setAspectRatio', $("#aspectRatioW2").val()/$("#aspectRatioH2").val());
            $('#cropper-modal').appendTo('#select-image-2');
            // $('#img-container').show();
            $('.save-cropped-image').attr('data-image-type','imagem_2');
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
        formData.append("idtimeline", $('input[name=idtimeline]').val());
        if(dataImageType == 'imagem'){
            formData.append("dimensaoWidth", $("#aspectRatioW").val());
            formData.append("dimensaoHeight", $("#aspectRatioH").val());
        }else{
            formData.append("dimensaoWidth", $("#aspectRatioW2").val());
            formData.append("dimensaoHeight", $("#aspectRatioH2").val());
        }

        $.ajax({
            url: "timeline_script.php",
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
                        $('#select-image-1 .img-timeline-form').attr('src', 'files/timeline/'+data.imagem);
                        $('#select-image-1 .img_pricipal .box_ip').show();
                        $('.excluir-imagem[data-tipo='+dataImageType+']').attr('data-img',data.imagem);
                        $('.excluir-imagem[data-tipo='+dataImageType+']').show();
                    }else{
                        $('#imagem_2-value').val(data.imagem);
                        $('#select-image-2 .img-timeline-form').attr('src', 'files/timeline/'+data.imagem);
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

    $("#inputImage").change(function(){
        var filename = $(this).val();
        var extension = filename.replace(/^.*\./, '');
        if (extension == filename) { extension = '';
        }else{ extension = extension.toLowerCase(); }
       
        if(extension != 'png' && extension != 'jpg' && extension != 'jpeg'){
          msgErro('A extensão deste arquivo não é permitida!');
          $(this).val('');
          return false;
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
        excluirImagem('timeline', tipo, img, thisElem);
        $('.ui-dialog-titlebar-close').click();
    });

    $(".cancel").click(function(){
        $('.ui-dialog-titlebar-close').click();
    });

    $('.bt_save').click(function(){
        Loader.show();
    });
});

function cancelarImagem(pasta, imagem){
    $.ajax({
        url: 'timeline_script.php',
        dataType: 'json',
        type: 'post',
        data: {opx: 'cancelarImagem', pasta: pasta, imagem: imagem},
        success: function(){
            location.href='index.php?mod=timeline&acao=listarBanner';
        }
    });
}