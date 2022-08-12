// Versao do modulo: 3.00.010416

var preventCache = Math.random();
var requestInicio = "";
var ordem = "";
var dir = "";
var pesquisar = "";
var limit = 20;
var pagina = 0;
var totalPaginasGrid = 1;

function preTableBlog_post(buscar){
		$("#limit").change(function(){
	         $("#pagina").val(1);
	         dataTableBlog_post(buscar);
	    });

	    $("#pagina").keyup(function(e){
	         if(e.keyCode == 13){
	             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
	                 dataTableBlog_post(buscar);
	             }else{
	                 msgErro("numero de pagina deve ser entre 1 e "+totalPaginasGrid);
	             }
	         }
	    });

	    $(".next").click(function(e){
	          e.preventDefault();
	          $("#pagina").val($(this).attr('proximo'));
	          dataTableBlog_post(buscar);
	    });

	    $(".prev").click(function(e){
	         e.preventDefault();
	         $("#pagina").val($(this).attr('anterior'));
	         dataTableBlog_post(buscar);
	    });


	    //LISTAGEM BUSCA
	    $("#buscarapida").keyup(function(event){
	        event.preventDefault();
	        if(event.keyCode == '13') {
	            pesquisar = "&nome="+$("#buscarapida").val();
	            dataTableBlog_post(buscar);
	        }
	        return true;
	    });

	    $("#filtrar").click(function(e){
	        e.preventDefault(); 
	        pesquisar = "&"+$("#formAvancado").serialize();
	        dataTableBlog_post(buscar);
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
	         dataTableBlog_post(buscar);
	    });

      $('.table').on("click", ".inverteStatus", function (e) {
        var params = {
          idblog_post: $(this).attr("codigo")
        }

        $.post(
          'blog_post_script.php?opx=inverteStatus',
          params,
          function (data) {
            var resultado = new String(data.status);

            if (resultado.toString() == 'sucesso') {
              dataTableBlog_post();
            }
            else if (resultado == 'falha') {
              alert('Não foi possível atender a sua solicitação.')
            }

          }, 'json'
        );
      });

      $('.table').on("click", ".inverteStatus", function (e) {
        var params = {
          idblog_post: $(this).attr("codigo")
        }

        $.post(
          'blog_post_script.php?opx=inverteStatusPost',
          params,
          function (data) {
            var resultado = new String(data.status);

            if (resultado.toString() == 'sucesso') {
              dataTableBlog_post();
            }
            else if (resultado == 'falha') {
              alert('Não foi possível atender a sua solicitação.')
            }

          }, 'json'
        );
      });

}

var myColumnDefs = [
	{key:"idblog_post", sortable:true, label:"ID", print:true, data:true},
	{key:"nome", sortable:true, label:"Nome", print:true, data:true},
  {key:"nome_categoria", sortable:true, label:"Categoria", print:true, data:true},
	{key:"resumo", sortable:true, label:"Resumo", print:true, data:false},
	{key:"descricao", sortable:true, label:"Descrição", print:true, data:false},
	{key:"imagem", sortable:true, label:"Imagem", print:false, data:false},
	{key:"contador", sortable:true, label:"Contador", print:true, data:true},
	{key:"data_hora_formatado", sortable:true, label:"Início", print:true, data:true}, 
	{key:"status_nome", sortable:false, label:"Status", print:true, data:false},
  {key:"status_icone", sortable:false, label:"Status", print:false, data:true},
	{key:"urlrewrite", sortable:true, label:"Urlrewrite", print:true, data:false}
] 

function columnBlog_post(){
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

function dataTableBlog_post(buscar){

  idblog_categoria = "";
  idg = "";
  if($("#idg").length){
     idblog_categoria = "&idblog_categoria="+$("#idg").val();
     idg = "&idg="+$("#idg").val();
  }

  limit = $("#limit").val();
  pagina = $("#pagina").val();
  pagina = parseInt(pagina) - 1;
  colunas = myColumnDefs;
  colunas = JSON.stringify(colunas);
  queryDataTable = requestInicio+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas+idblog_categoria;

  $.ajax({
     url: "base_proxy.php",
     dataType: "json",
     type: "post",
     data: requestInicio+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir+idblog_categoria,
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
                  // tr += '<a href="index.php?mod=blog_comentarios&acao=listarBlog_comentarios&idg='+value.idblog_post+'"><img src="images/modulos/blog_comentarios_cinza.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Comentários</span><span class="two"></span></div></a>';
                  tr += '<a href="index.php?mod=blog_post&acao=formBlog_post&met=editBlog_post&idu='+value.idblog_post+idg+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                  tr += '<a href="#" onclick="wConfirm(\'Excluir Registro\',\'Tem certeza que deseja excluir o registro '+value.nome+' ?\',\'php\', \'blog_post_script.php?opx=deletaBlog_post&idu='+value.idblog_post+idg+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
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

    $(".cropped-image").change(function(){
        var filename = $(this).val();
        var extension = filename.replace(/^.*\./, '');
        if (extension == filename) { extension = '';
        }else{ extension = extension.toLowerCase(); }
       
        if(extension != 'png' && extension != 'jpg' && extension != 'jpeg'){
            msgErro('A extensão deste arquivo não é permitida!');
            $(this).val('');
            $(this).css('border','1px solid red');
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
        excluirImagem('blog_post', tipo, img, thisElem);
        $('.ui-dialog-titlebar-close').click();
    });

    $(".cancel").click(function(){
        $('.ui-dialog-titlebar-close').click();
    });


   /////////////////URLREWRITE///////////////////////////////////

    $("#urlrewrite").blur(function(event){
        event.preventDefault();  
        if($(this).val() != "" || $("#nome").val() != ""){
          url = $(this).val();
          if(url == ""){
            url = $("#nome").val();
            $("#urlrewrite").val($("#nome").val()).closest(".box_ip").addClass("focus");
          }  
          verificaUrlrewrite(url); 
        }  
    }); 

    $("#nome").blur(function(event){
        url = $("#urlrewrite").val();
        if(url == ""){ 
          nome = $("#nome").val();  
          verificaUrlrewrite(nome);
        }   
    });
  ////////////////////////////////////////////////////  
 
  // $("#inputImage").change(function(){
  //     $("#img-container").css('display','block');
  //     $("#imagem").val(true);
  // });


	//SE CANCELAR O CADASTRO EXCLUI AS IMAGENS, SE HOUVER
	// $(".bt_cancel").click(function(e){
	// 	e.preventDefault();

	// 	if($("#mod").val() == 'cadastro' && $('#idblog_post').val() != 0){
	// 	  $.ajax({
	// 	      url:'blog_post_script.php',
	// 	      data: { opx:'excluiImagensTemporarias', idblog_post:$('#idblog_post').val()},
	// 	      dataType:'json',
	// 	      type:'post',
	// 	      success:function(data){
	// 	        if(data.status == true){
	// 	            location.href = 'index.php?mod=blog_post&acao=listarBlog_post';
	// 	        } 
	// 	      }
	// 	  });
	// 	}else{
	// 		location.href = 'index.php?mod=blog_post&acao=listarBlog_post';
	// 	}
	// });
    $(".cancelar").click(function(event){
        location.href='index.php?mod=blog_post&acao=listarBlog_post';
    });


	$(".bt_save").click(function(event){
		event.preventDefault();
        tinyMCE.triggerSave();
        var valida = true;
        msg = "";
        alterarDataSrc();
        $("#inputImage").css("border", "1px solid #d9d9d9");
        $('#formBlog_post').find('.required').each(function(){
            $(this).css("border","1px solid #d9d9d9"); 
            if($(this).attr('name') == 'imagem' && $(this).val() == ""){
                $("#inputImage").css("border", "solid 1px  red");
                valida = false;
            } 
            else{
                if($.trim($(this).val())==''){
                    if ($(this).hasClass('mceAdvanced')) {  
                        $(this).parent().find('.mce-tinymce').css("border", "solid 1px red");
                        valida = false;   
                    }else{  
                        $(this).css("border", "solid 1px red");
                        valida = false;
                    }
                }
            }
        });
        if(valida){
            Loader.show();  
            // if($("#inputImage").val() != ''){ 
            //     coordenadas =  $(".img-container>img").cropper('getData');  
            //     coordenadas = JSON.stringify(coordenadas, null, 4); 
            //     $("#coordenadas").val(coordenadas);
            // } 
            // verificaUrlrewrite($("#urlrewrite").val(), true);
                $('#formBlog_post').submit();
        }else{
            msgErro('Preencha o(s) campo(s) obrigatórios!');
        }
	});


////////////////////////////////////////////
///////// GALERIA DE IMAGENS //////////////
//////////////////////////////////////////
 
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
              url:'blog_post_script.php',
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
        idblog_post = $("#formBlog_post").find("#idblog_post").val();
        idblog_post_imagem = $("#"+idPosicao).find("input[name='idblog_post_imagem[]']").val();
        imagem = $("#"+idPosicao).find("input[name='imagem_blog_post[]']").val();
        ref = $("#"+idPosicao); 
         
        imagemDelete = $("#"+idPosicao).find("img").attr("src"); 
        imagemDelete = $("#_endereco").val()+imagemDelete.replace('galeria/thumb/',"galeria/original/");  
        
        //excluir imagem do post
        var post = tinyMCE.get("descricao").getContent();
        imagePost =  tinyMCE.get("descricao").dom.select('img');
        $.each(imagePost, function(nodes, name) {
            img = tinyMCE.get("descricao").dom.select('img')[nodes]; 
            img = $(img)[0];  

            if(img.src == imagemDelete){ 
               img.remove();
            }
        });

        var post2 = tinyMCE.get("descricao").getContent(); 
        $.ajax({
            url:'blog_post_script.php',
            type:'post',
            dataType:'json', 
            data:
            {
              opx:'excluirImagemGaleria',
              idblog_post:idblog_post,            
              imagem:imagem,
              idblog_post_imagem:idblog_post_imagem,
              descricao: post2
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
  formData.append('idblog_post', $("#idblog_post").val()); 
  formData.append('posicao', posicao); 
 
  $.ajax({ 
    url: "blog_post_script.php", 
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
            $li = '<li class="ui-state-default'+posicao+' move" id="'+posicao+'" idimagem="'+data.idblog_post_imagem+'">';
            $li += '<img id="img'+posicao+'" class="imagem-gallery" style="opacity:1;" src="'+data.caminho+'">';
            $li += '<a class="editImagemDescricao" idimagem="'+data.idblog_post_imagem+'" href="#"><button class="edit"></button></a>';
            $li += '<a class="excluirImagem" idimagemdelete="'+data.idblog_post_imagem+'" href="#"><button class="delete"></button></a>';
            $li += '<a class="postImagem" idimagempost="'+data.idblog_post_imagem+'" href="#"><button class="post_imagem"></button></a>'; 
            $li += '<a class="postImagem" idimagempost="'+data.idblog_post_imagem+'" href="#"><button class="post_imagem"></button></a>';
            $li += '<input type="hidden" name="idblog_post_imagem[]" value="'+data.idblog_post_imagem+'">'; 
            $li += '<input type="hidden" name="descricao_imagem[]" value="">'; 
            $li += '<input type="hidden" name="imagem_blog_post[]" value="'+data.nome_arquivo+'">';
            $li += '<input type="hidden" name="posicao_imagem[]" value="'+posicao+'">';
            $li += '</li>'; 
            $("#sortable").append($li); 
            $("#idblog_post").val(data.idblog_post); 
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
    form = $("#formBlog_post").serialize(); 

    $.ajax({ 
        url: "blog_post_script.php", 
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

 

function postImagem(campo){ 
    ref = $(campo).parent();
    imagem = $(ref).find("img").attr("src");
    imagem = imagem.replace('galeria/thumb/',"galeria/original/");
    link = $("#_endereco").val()+imagem; 
    var post = tinyMCE.get("descricao").getContent();
    post += '<img src="'+link+'" alt="" data-mce-src="'+link+'"/>';
    tinyMCE.get("descricao").setContent(post);
    alterarDataSrc();
}

function alterarDataSrc(){  
  var post = tinyMCE.get("descricao").getContent();
  imagePost =  tinyMCE.get("descricao").dom.select('img');
  $.each(imagePost, function(nodes, name) {
      imgDescricao = tinyMCE.get("descricao").dom.select('img')[nodes]; 
      img = $(imgDescricao)[0];
      src = img.src; 
      $(imgDescricao).attr("data-mce-src",src);
  }); 
}

$(window).load(function(){
    alterarDataSrc();
})



function verificaUrlrewrite(url, form){
  id = 0; 
   
  if(url != ""){
    if($("#mod").val()=='editar'){ 
        id = $("#idblog_post").val(); 
    } 
    
    $.ajax({
        url:'blog_post_script.php',
        dataType:'json',
        data: "opx=verificarUrlRewrite&idblog_post="+id+"&urlrewrite="+url,
        type:'post',
        beforeSend:function(){
          Loader.show();
        },
        success:function(data){  
            if(!data.status){
                msgErro("Url já cadastrado para outro Post!");
                $("#urlrewrite").val($("#urlrewriteantigo").val());
                urlRetorno = false;
            }else{   
                $("#urlrewrite").val(data.url); 
                if(form){
                   $('#formBlog_post').submit();   
                }
            }
        },
        complete:function(){
            Loader.hide();
        }
    });
  }   
}


