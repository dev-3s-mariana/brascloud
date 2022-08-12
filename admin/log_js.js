var queryDataTable = '';

var preventCache = Math.random();
var requestInicioLog= "tipoMod=log&p="+preventCache+"&";
var ordem = "idlog";
var dir = "desc";
var pesquisar = "";
var limit = 10;
var pagina = 0;
var totalPaginasGrid = 1;
var buscar = "";


$(document).ready(function(){

    ///ACAO DA PAGINACAO
    $("#limit").change(function(){
         $("#pagina").val(1);
         dataTableLog();
    });

    $("#pagina").keyup(function(e){
         if(e.keyCode == 13){
             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
                  dataTableLog();
             }else{
                 msgErro("Número da página deve ser entre 1 e "+totalPaginasGrid);
             }
         }
    });

    $(".next").click(function(e){
          e.preventDefault();
          $("#pagina").val($(this).attr('proximo'));
          dataTableLog();
    });

    $(".prev").click(function(e){
         e.preventDefault();
         $("#pagina").val($(this).attr('anterior'));
         dataTableLog();
    });


    //LISTAGEM BUSCA
    $("#buscarapida").keyup(function(event){
        event.preventDefault();
        if(event.keyCode == '13') {
            pesquisar = "&nomemodulo="+$("#buscarapida").val();
            dataTableLog();
        }
        return true;
    });

    $("#filtrar").click(function(e){
        e.preventDefault();
        pesquisar = "&"+$("#formAvancado").serialize();
        dataTableLog();
    });

    $(".ordem").click(function(e){
         e.preventDefault();
         ordem = $(this).attr("ordem");
         dir = $(this).attr("order");
         $(".ordem").removeClass("action");
         $(".ordem").removeClass("actionUp");
         if($(this).attr("order") == "asc"){
             $(this).attr("order","desc");
             $(this).removeClass("actionUp");
             $(this).addClass("action");
         }else{
             $(this).attr("order","asc");
             $(this).removeClass("action");
             $(this).addClass("actionUp");
         }
         dataTableLog();
    }); 


    $("#data_inicio").datepicker({
          dateFormat: 'dd/mm/yy',
          dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
          dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
          dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
          monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
          monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
          nextText: 'Próximo',
          prevText: 'Anterior', 
          onClose: function( selectedDate ) {  
              data = $(this).datepicker('getDate');
              if(data != null){
                selectedDate = new Date(data.getFullYear(), (data.getMonth()), data.getDate());
                $("#data_fim").datepicker( "option", "minDate", selectedDate );
                $("#data_inicio").closest(".box_ip").addClass("focus"); 
              }else{
                $("#data_inicio").closest(".box_ip").removeClass("focus");
              } 
          }   
    });


    $("#data_fim").datepicker({
          dateFormat: 'dd/mm/yy',
          dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
          dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
          dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
          monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
          monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
          nextText: 'Próximo',
          prevText: 'Anterior',  
          onClose: function( selectedDate ){ 
              data = $(this).datepicker('getDate');
              if(data != null){
                if($("#data_inicio").val() != ""){ 
                    if(!comparaData($("#data_inicio").val(), $(this).val())){
                        msgErro("A data fim deve ser maior ou igual que a inicial");
                        $(this).val("").closest(".box_ip").removeClass("focus");
                    }else{
                      $(this).closest(".box_ip").addClass("focus");
                    }
                }else{
                  $("#data_fim").closest(".box_ip").addClass("focus"); 
                }
              }else{
                $("#data_fim").closest(".box_ip").removeClass("focus");
              }
          } 
    });

});

function comparaData(dataInicio, dataFim){
    dataInicio = gerarData(dataInicio);
    dataFim = gerarData(dataFim); 
    if(dataInicio > dataFim){
        return false;
    }
    return true;
}

function gerarData(str) {
    var partes = str.split("/");
    return new Date(partes[2], partes[1] - 1, partes[0]);
    //return Date.parse(data);
}

var myColumnDefs = [
    {key:"idusuario", sortable:true, label:"Usuário", print:false, data:true},
    {key:"datahora", sortable:true, label:"Data", size:"150", print:true, data:true},
    {key:"modulo", sortable:true, label:"Módulo", print:true, data:true},
    {key:"descricao", sortable:true, label:"Descrição", print:true, data:true}
];

function columnLog(){
    tr = "";
    $.each(myColumnDefs, function(col, ColumnDefs){
             if(ColumnDefs['data']){
                ordena = "";
                orderAction = "";
                size = "";
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

                if(ColumnDefs['size']){
                    size = "width="+ColumnDefs['size']+"px";
                }

                tr += '<th '+size+'><a href="#" '+ ordena +' >'+ColumnDefs['label']+'</a></th>';
             }
    });
    tr += "<th></th>";
    $('#listagem').find("thead").append(tr);
}


function dataTableLog(){

        limit = $("#limit").val();
        pagina = $("#pagina").val();
        pagina = parseInt(pagina) - 1;
        colunas = myColumnDefs;
        colunas = JSON.stringify(colunas);
        queryDataTable = requestInicioLog+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas;
        $.ajax({
               url: "base_proxy.php",
               dataType: "json",
               type: "post",
               data: requestInicioLog+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir,
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
                           });
                           $('#listagem').find("tbody").append(tr);
                           atualizaPaginas(data.pageSize, (pagina + 1), data.totalRecords);
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


 