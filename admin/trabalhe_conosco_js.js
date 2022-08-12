// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
function preTableTrabalhe_conosco(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableTrabalhe_conosco();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableTrabalhe_conosco();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableTrabalhe_conosco();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableTrabalhe_conosco();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
	            pesquisar = "&nome="+$("#buscarapida").val();
	            dataTableTrabalhe_conosco();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableTrabalhe_conosco();
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
	         dataTableTrabalhe_conosco();
	    });

}

var myColumnDefs = [
	{key:"idtrabalhe_conosco", sortable:true, label:"ID", print:true, data:true},
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
	{key:"email", sortable:true, label:"E-mail", print:true, data:true},
	{key:"nome_area", sortable:true, label:"Pretensão", print:true, data:true},
	{key:"arquivo", sortable:true, label:"Arquivo", print:false, data:false},
	{key:"data_hora", sortable:true, label:"Data/hora", print:true, data:false},
	{key:"ip", sortable:true, label:"Ip", print:true, data:false}
]

function columnTrabalhe_conosco(){
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

function dataTableTrabalhe_conosco(){
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
                    tr += '<a href="'+value.arquivo+'" target="_blank"><img src="images/modulos/ver_cinza.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Ver Curriculum</span><span class="two"></span></div></a>';
                    tr += '<a href="index.php?mod=trabalhe_conosco&acao=formTrabalhe_conosco&met=editTrabalhe_conosco&idu='+value.idtrabalhe_conosco+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                    tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.idtrabalhe_conosco+' ?\',\'php\', \'trabalhe_conosco_script.php?opx=deletaTrabalhe_conosco&idu='+value.idtrabalhe_conosco+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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
        location.href='index.php?mod=trabalhe_conosco&acao=listarTrabalhe_conosco';
    });
  
  //mask telefone
  $('.mtel').mask("(99) 9999-9999?9").keyup(function(event) {
      var target, phone, element;
      target = (event.currentTarget) ? event.currentTarget : event.srcElement;
      phone = target.value.replace(/\D/g, '');

      if(phone.length > 10) { 
          element = $(target);
          element.unmask();
          element.mask("(99) 99999-999?9");
      } else if(phone.length == 10) {
          element = $(target);
          element.unmask();
          element.mask("(99) 9999-9999?9");  
      }
  }); 
  
	/////////////TROCAR DE IDIOMA/////////////////////
    $("#ididiomas").change(function(){ 
          $.ajax({
            url:'area_pretendida_script.php',
            type:'post',
            dataType:'json', 
            data:{ opx:'buscaAreas', ididiomas:$(this).val() },
            beforeSend:function(){
                Loader.show();
            },        
            success:function(data){ 
                html = "<option></option>";
                dados = data.dados;
                $.each(dados, function(index, value){
                    html += "<option value='"+value.idarea_pretendida+"'>"+value.nome+"</option>";
                }) 
                $("#idarea_pretendida").html(html);
                $("#idarea_pretendida").closest(".box_sel").removeClass("focus");
            },
            complete:function(){
              	Loader.hide(); 
            }  
          });
    }); 
})