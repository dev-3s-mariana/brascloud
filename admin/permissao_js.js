
var queryDataTable = '';


var preventCache = Math.random();
var requestInicioPermissao= "tipoMod=permissao&p="+preventCache+"&";
var ordem = "idpermissao";
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
         dataTablePermissao();
    });

    $("#pagina").keyup(function(e){
         if(e.keyCode == 13){
             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
                  dataTablePermissao();
             }else{
                 msgErro("Número da página deve ser entre 1 e "+totalPaginasGrid+"!");
             }
         }
    });

    $(".next").click(function(e){
          e.preventDefault();
          $("#pagina").val($(this).attr('proximo'));
           dataTablePermissao();
    });

    $(".prev").click(function(e){
         e.preventDefault();
         $("#pagina").val($(this).attr('anterior'));
         dataTablePermissao();
    });


    //LISTAGEM BUSCA
    $("#buscarapida").keyup(function(event){
        event.preventDefault();
        if(event.keyCode == '13') {
            pesquisar = "&apelido="+$("#buscarapida").val();
            dataTablePermissao();
        }
        return true;
    });

    $("#filtrar").click(function(e){
        e.preventDefault();
        pesquisar = "&"+$("#formAvancado").serialize();
        dataTablePermissao();
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
         dataTablePermissao();
    }); 

});


var myColumnDefs = [
    {key:"idpermissao", sortable:true, label:"ID", resizeable:false, print:true, data:true},
    {key:"apelido", sortable:true, label:"Apelido", resizeable:false, print:true, data:true},
    {key:"tags", sortable:true, label:"Tags", resizeable:false, print:true, data:true}
];

function columnPermissao(){
    tr = "";
    $.each(myColumnDefs, function(col, ColumnDefs){
          if(ColumnDefs['data']){
            ordena = "";
            orderAction = "";
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
            tr += '<th><a href="#" '+ ordena +' >'+ColumnDefs['label']+'</a></th>';
          }
    });
    tr += "<th></th>";
    $('#listagem').find("thead").append(tr);
}


function dataTablePermissao(){

        limit = $("#limit").val();
        pagina = $("#pagina").val();
        pagina = parseInt(pagina) - 1;
        colunas = myColumnDefs;
        colunas = JSON.stringify(colunas);
        queryDataTable = requestInicioPermissao+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas;
        $.ajax({
               url: "base_proxy.php",
               dataType: "json",
               type: "post",
               data: requestInicioPermissao+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir,
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
                                tr += '<a href="index.php?mod=permissao&acao=formPermissao&met=editPermissao&idu='+value.idpermissao+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Permissão\',\'Tem certeza que deseja excluir a permissão '+value.apelido+' ?\',\'php\', \'permissao_script.php?opx=deletaPermissao&idu='+value.idpermissao+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
                                tr += '</div></td>';
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


$(document).ready(function(){
    $(".cancelar").click(function(event){
        location.href='index.php?mod=permissao&acao=listarPermissao';
    });

   $(".bt_save").click(function(e){
        e.preventDefault();
        $("#apelido").css("border","1px solid #e2e4e7");  
        valida = true;
        msg = "";
        if($("#apelido").val() == ""){
            $("#apelido").css("border","1px solid red");
            valida = false;
            msg = "Preencha o(s) campo(s) obrigatório(s)!";
            $(".content_tit").css("color","#C40318");
        }

        marcou = false;
        permissao = $("#informacaoUsuario").find("input[type='checkbox']");
        $.each(permissao, function(index, value){ 
            if($(this).is(":checked")){
                marcou = true;
            }
        });

        if(!marcou && valida){
            valida = false;
            msg = "Selecione pelo menos uma Permissão!";
            $(".content_tit").css("color","#C40318");
        }

        if(valida){
            $("#form2").submit();
        }else{
          msgErro(msg);
        }

    })

});

