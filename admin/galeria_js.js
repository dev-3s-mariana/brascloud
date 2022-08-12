// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;
var totalI = 0;

function preTableGaleria(){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableGaleria();
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableGaleria();
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableGaleria();
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableGaleria();
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
	            pesquisar = "&nome="+$("#buscarapida").val();
	            dataTableGaleria();
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault();
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableGaleria();
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
	         dataTableGaleria();
	    });

      $('.table').on("click", ".inverteStatus", function (e) {
          var params = {
            idgaleria: $(this).attr("codigo")
          }

          $.post(
            'galeria_script.php?opx=inverteStatus',
            params,
            function (data) {
              var resultado = new String(data.status);

              if (resultado.toString() == 'sucesso') {
                dataTableGaleria();
              }
              else if (resultado == 'falha') {
                alert('Não foi possível atender a sua solicitação.')
              }

            }, 'json'
          );
      });
}

var myColumnDefs = [
	{key:"idgaleria", sortable:true, label:"ID", print:true, data:true},
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
	{key:"status", sortable:true, label:"Status", print:false, data:false},  
  {key:"status_nome", sortable:false, label:"Status",  print:true, data:false}, 
	{key:"status_icone", sortable:false, label:"Status",  print:false, data:true} 
]

function columnGaleria(){
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

function dataTableGaleria(){
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
                    tr += '<a href="index.php?mod=galeria&acao=formGaleria&met=editGaleria&idu='+value.idgaleria+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
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

  //SE CANCELAR O CADASTRO EXCLUI AS IMAGENS, SE HOUVER
  $(".bt_cancel").click(function(e){
      e.preventDefault();
      
      if($("#mod").val() == 'cadastro' && $('#idgaleria').val() != 0){
          $.ajax({
              url:'galeria_script.php',
              data: { opx:'excluiImagensTemporarias', idgaleria:$('#idgaleria').val()},
              dataType:'json',
              type:'post',
              success:function(data){
                if(data.status == true){
                    location.href = 'index.php?mod=galeria&acao=listarGaleria';
                } 
              }
          });
      }else{
        location.href = 'index.php?mod=galeria&acao=listarGaleria';
      }
  });


////////////////////////////////////////////
///////// GALERIA DE IMAGENS //////////////
//////////////////////////////////////////

  $("#image").change(function(){              
      enviaImagens(this);  
  }); 


  // ABRIR O BOX DE DESCRIÇÃO - da imagem
  $("#content-image").on("click",".cropImagem", function(e){
      e.preventDefault();  
      idimagem = $(this).closest("li").attr("id");
      pasta = "galeria"; 
      idrelacao = $("#idgaleria").val();
      width = 940;
      height = 282; 
      width2 = 155;
      height2 = 90;
      nome_imagem = $(this).closest("li").find("input[name='imagem_galeria[]']").val();
      $.ajax({
          data: { 'acao': 'crop_galeria', 'idimagem':idimagem, "nome_imagem":nome_imagem, "pasta":pasta, "tam2":"thumb", "width":width, "height":height, "width2":width2, "height2":height2},          
          success: function(telaStatus){
            if(telaStatus != 'false'){ 
              $.facebox(telaStatus);  
              $("#img-containerGaleria").css('display','block'); 
              $("#inputImageGaleria").trigger("click");
            }
          },
          type: 'post',
          url: 'cropImagem_galeria.php'
      }); 
  }); 

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
              url:'galeria_script.php',
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
        idgaleria = $("#formGaleria").find("#idgaleria").val();
        idgaleria_imagem = $("#"+idPosicao).find("input[name='idgaleria_imagem[]']").val();
        imagem = $("#"+idPosicao).find("input[name='imagem_galeria[]']").val();

        ref = $("#"+idPosicao);
   
        $.ajax({
            url:'galeria_script.php',
            type:'post',
            dataType:'json', 
            data:
            {
              opx:'excluirImagemGaleria',
              idgaleria:idgaleria,            
              imagem:imagem,
              idgaleria_imagem:idgaleria_imagem
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

     

}); 



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
  totalI = totalimagens;  
  //trata cada dado de arquivo enviado pelo input
   
  for(var i =0; i<totalimagens; i++ ){  
    Loader.show(); 
    if (input.files && input.files[i]){//verifica se tem dados no input       
        if(verificaExt(input.files[i])){//se valida a extensao do arquivo         
            if(input.files[i].size > tamanhoMaximo){                            
                erros += 'A imagem "'+input.files[i].name+'"'+' não foi enviada, pois, seu tamanho excede '+$("#fileMax").val()+'MB <br />';         
            }else{ 
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
  formData.append('idgaleria', $("#idgaleria").val());
  formData.append('tipogaleria', $("#idgaleria").attr("tipogaleria"));
  formData.append('posicao', posicao); 

  $.ajax({
    url: "galeria_script.php",
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
            $li = '<li class="ui-state-default'+posicao+' move" id="'+posicao+'" idimagem="'+data.idgaleria_imagem+'">';
            $li += '<img id="img'+posicao+'" class="imagem-gallery" style="opacity:1;" src="'+data.caminho+'">';
            $li += '<a class="editImagemDescricao" idimagem="'+data.idgaleria_imagem+'" href="#"><button class="edit"></button></a>';
            $li += '<a class="excluirImagem" idimagemdelete="'+data.idgaleria_imagem+'" href="#"><button class="delete"></button></a>';
            $li += '<input type="hidden" name="idgaleria_imagem[]" value="'+data.idgaleria_imagem+'">'; 
            $li += '<input type="hidden" name="descricao_imagem[]" value="">'; 
            $li += '<input type="hidden" name="imagem_galeria[]" value="'+data.nome_arquivo+'">';
            $li += '<input type="hidden" name="posicao_imagem[]" value="'+posicao+'">';
            $li += '</li>'; 

            $("#sortable").append($li); 
            $("#idgaleria").val(data.idgaleria); 

            totalI--;
            if(totalI == 0){
                Loader.hide();
                $("#sortable").removeAttr("style");  
            } 
        }//fim if
        else{
            msgErro('Erro ao enviar imagem, por favor tente novamente!'); 
             Loader.hide();
            $("#sortable").removeAttr("style");
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
     form = $("#formGaleria").serialize();
      
      $.ajax({
          url: "galeria_script.php",
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

 

 


