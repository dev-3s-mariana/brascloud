// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableIdiomas(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableIdiomas();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableIdiomas();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableIdiomas();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableIdiomas();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
                $('#pagina').val(1);
	            pesquisar = "&idioma="+$("#buscarapida").val();
	            dataTableIdiomas();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
            $('#pagina').val(1);
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableIdiomas();
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
	         dataTableIdiomas();
	    });

}
	var myColumnDefs = [
		{key:"ididiomas", sortable:true, label:"ID", print:true, data:true},
		{key:"idioma", sortable:true, label:"Idioma", print:true, data:true},
		{key:"_bandeira", sortable:true, label:"Bandeira", print:false, data:true},
		{key:"urlamigavel", sortable:true, label:"Url Amigável", print:true, data:true},
		{key:"status_nome", sortable:true, label:"Status", print:true, data:false},
    {key:"status_icone", sortable:true, label:"Status", print:false, data:true}   
	]
function columnIdiomas(){
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
function dataTableIdiomas(){
  flag = $(".abas_list .action").find(".listaFlag").attr("aba_status"); 
  limit = $("#limit").val();
  pagina = $("#pagina").val();
  pagina = parseInt(pagina) - 1;
  colunas = myColumnDefs;
  colunas = JSON.stringify(colunas);
  queryDataTable = requestInicio+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas+"&status="+flag;
  $.ajax({
         url: "base_proxy.php",
         dataType: "json",
         type: "post",
         data: requestInicio+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir+"&status="+flag,
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
                      tr += '<a href="index.php?mod=idiomas_traducao&acao=formIdiomas_traducao&idg='+value.ididiomas+'"><img src="images/modulos/idiomas_traducao_cinza.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Gerenciar Tradução</span><span class="two"></span></div></a>';
                      tr += '<a href="index.php?mod=idiomas&acao=formIdiomas&met=editIdiomas&idu='+value.ididiomas+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                      if(value.ididiomas != 1){
                         // tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idioma+' ?\',\'php\', \'idiomas_script.php?opx=deletaIdiomas&idu='+value.ididiomas+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
                      } 
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
        location.href='index.php?mod=idiomas&acao=listarIdiomas';
    });

    ////////////////////////////////////////////////////  
    $(".listaFlag").click(function(e){ 
        e.preventDefault();    
        flag = $(this).attr("aba_status");  
        $(".abas_list li").removeClass("action");
        $(this).closest("li").addClass("action");
        dataTableIdiomas();
        //location.href = "index.php?mod=idiomas&acao=listarIdiomas&ids="+flag;
    }); 

	  //FAZ O UPLOAD DA IMAGEM 
    $(".foto").change(function(){  

        var filename = $(this).val();
        var extension = filename.replace(/^.*\./, '');
        if (extension == filename) {
            extension = '';
        }else{ 
            extension = extension.toLowerCase();
        }

        if(extension!='jpg' && extension!='png' && extension!='gif' && extension!='jpeg'){
            msgErro('A extensão deste arquivo não é permitida!');
            return false;
        }

        var tamanhoMaximo ;
        tamanhoMaximo = ($("#maxFileSize").val())*1000000; 
        if($(this)[0].files[0].size >  tamanhoMaximo){
            msgErro('Arquivo muito grande!');
            return false;
        }
        //início AJAX
        var formData = new FormData();
        formData.append('imagem', this.files[0]);
        formData.append('opx', 'salvaImagem'); 

        formData.append('ididiomas', $("#ididiomas").val());

        $.ajax({
            url: "idiomas_script.php",
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,   // tell jQuery not to set contentType
            beforeSend:function(){
              Loader.show();
            },
            success:function(data){  
                console.log(data);
                if(data.status==true){ 
                    msgSucesso('Imagem enviada com sucesso.');
                  	$('.bandeira').attr('src', data.caminho).show(); 
                  	$('#bandeira').val(data.nome_arquivo);
                    $("#ididiomas").val(data.ididiomas);
                }else{
                  msgErro(data.msg);
                }
                Loader.hide();
            },
            complete:function(){
                Loader.hide();
            }
        }); 
    });

    // $(".bt_save").click(function(event){

    //     event.preventDefault(); 
    //     var valida = true;
    //     msg = ""; 
    //     $('#formIdiomas').find('.required').each(function(){
    //           $(this).css("border","1px solid #d9d9d9");

    //           if($(this).attr('id') == 'bandeira' && $(this).val() == ""){ 
    //               $("#iconebandeira").css("border", "solid 1px  red");
    //               valida = false; 
    //           } 
    //           else{
    //               if($.trim($(this).val())==''){
    //                   $(this).css("border", "solid 1px  red");
    //                   valida = false;
    //               }
    //           }
    //     });

    //     if(valida){
    //         Loader.show(); 
    //         $('#formIdiomas').submit();
    //     }else{
    //        msgErro('Preencha o(s) campo(s) obrigatórios!');
    //     }
    // });

    /////////////////URLREWRITE///////////////////////////////////

    $("#principal #urlamigavel").blur(function(event){
        event.preventDefault();  
        if($.trim($(this).val()) != "" || $.trim($("#nome").val()) != ""){
          url = $(this).val();
          if(url == ""){
            url = $("#nome").val();
            $("#urlamigavel").val($("#nome").val()).closest(".box_ip").addClass("focus");
          }  
          verificaUrlrewrite(url); 
        }  
    }); 

    $("#principal #nome").blur(function(event){
        url = $.trim($("#urlamigavel").val());
        if(url == ""){ 
          nome = $("#nome").val();  
          verificaUrlrewrite(nome);
        }   
    });
  ////////////////////////////////////////////////////

})

function verificaUrlrewrite(url){

  id = 0; 
  if($("#mod").val()=='editar'){ 
      id = $("#ididiomas").val(); 
  }  
  $.ajax({
      url:'idiomas_script.php',
      dataType:'json',
      data: "opx=verificarUrlRewrite&ididiomas="+id+"&urlamigavel="+url,
      type:'post',
      beforeSend:function(){
        Loader.show();
      },
      success:function(data){  
          if(!data.status){
              msgErro("Url já cadastrado para outro idioma");
              $("#urlamigavel").val($("#urlamigavelantigo").val());
          }else{   
              $("#urlamigavel").val(data.url);
          }
      },
      complete:function(){
          Loader.hide();
      }
  });
} 