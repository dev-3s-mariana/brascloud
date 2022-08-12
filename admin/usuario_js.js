var queryDataTable = ''; 

var preventCache = Math.random();
var requestInicioUsuario = "tipoMod=usuario&p="+preventCache+"&";
var ordem = "idusuario";
var dir = "desc";
var pesquisar = "";
var limit = 10;
var pagina = 0;
var totalPaginasGrid = 1;
var buscar = "";  


$(document).ready(function(){
	$('.permissoes').change(function(){
      checaPermissoes($('.permissoes').val());
	}); 
});


function checaPermissoes(tags){
	var checks = document.querySelectorAll('.contentPermissao  input');
	for(var n=0; n < checks.length; n++){
		// Nao deve alterar checkboxes do modulo de configuracoes
		if (checks[n].id.search(/configuracoes_/) !== 0) {
			checks[n].checked = '';
		}
	}
	var permissoes = tags.split(" ");
	for(var i=0; i < permissoes.length; i++){
		if(document.getElementById(permissoes[i]))
			document.getElementById(permissoes[i]).checked = 'checked';

	}
}


/*function validaSenha(nomesenha1, nomesenha2){
	var senha1 = document.getElementById(nomesenha1);
	var senha2 = document.getElementById(nomesenha2);

	if ((id !== '') && (senha1.value.length === 0) && (senha2.value.length === 0)) {
		return "";
	} else {
		if (senha1.value.length < 6){
			senha1.focus();
			return 'Sua senha deve conter no minimo 6 caracteres!';			
		}else if(senha1.value == senha2.value){
			return "";
		}else{ 
			senha1.value = ''; 
			senha2.value = '';
			senha1.focus();
			return 'Sua senha e a senha de confirmacao nao estao iguais!';
		}
	}
}*/


/*function validaEmail(email){
		var teste = true; 
		if ((email.value.indexOf('@', 0) == -1)&&(teste)){
			email.focus();
			msgErro('Informe um email válido');
			teste = false;
		}
	return teste;
}*/

 

function buscaFiltroYUI(query){
	  dataTable(query);
	  queryDataTable = query;
} 


function buscaFiltroYUIRelatorioAcesso(idusuario, query){
	  dataTableRelatorioAcesso(idusuario, query)
	  queryDataTable = query;
}


function buscaRapida(form){ 
  	var query = '';
  	if(form.buscarapida.value != ''){
  		query = "nome=" + form.buscarapida.value;
  	} 
  	buscaFiltroYUI(query); 
  	return false;
}



function buscaRapidaRelatorioAcesso(idusuario, form){  
  	var query = '';
  	if(form.buscarapida.value != ''){
  		query = "usuario=" + form.buscarapida.value;
  	} 
  	buscaFiltroYUIRelatorioAcesso(idusuario, query); 
  	return false;
}

//;
//function buscaAvancada(form){
//	var query = "nome=" + form.nome.value;
//	query += "&sobrenome=" + form.sobrenome.value;
//	query += "&email=" + form.email.value;
//	query += "&logado=" + form.logado.value;
//
//	buscaFiltroYUI(query);
//	return false;
//}

function validaUsuario(){
  	if($('#vUser').val() == 'valido'){
  		return true;
  	}
  	else{
  		msgErro('Usuário já cadastrado com o mesmo nome e sobrenome!');
  		return false;
  	}
};
 

$(document).ready(function(){

   $(".selecionar-todos-cb").click(function(){
      var moduloName = $(this).attr('data-modulo');
      if($(this).is(":checked")){
         $(".selecionar-todos-cb-md[data-modulo="+moduloName+"]").each(function(){
            $(this).prop('checked',true);
         });
      }else{
         $(".selecionar-todos-cb-md[data-modulo="+moduloName+"]").each(function(){
            $(this).prop('checked',false);
         });
      }
   });

   $("input[name=selecionar-todos]").change(function(){
      if($('input[name=selecionar-todos]:checked').val() == 1){
         $("input[value=permitido]").each(function(){
            // if($(this).is('checked') == false){
               $(this).prop('checked',true)
            // }
         });
      }else{
         $("input[value=permitido]").each(function(){
            $(this).prop('checked',false)
         });
      }
      
   });

    ///ACAO DA PAGINACAO
    $("#limit").change(function(){
         $("#pagina").val(1);
         table();
    });

    $("#pagina").keyup(function(e){
         if(e.keyCode == 13){
             if(totalPaginasGrid >= $(this).val() && $(this).val() > 0){
                  table();
             }else{
                 msgErro("Número da página deve ser entre 1 e "+totalPaginasGrid);
             }
         }
    });

    $(".next").click(function(e){
          e.preventDefault();
          $("#pagina").val($(this).attr('proximo'));
           table();
    });

    $(".prev").click(function(e){
         e.preventDefault();
         $("#pagina").val($(this).attr('anterior'));
         table();
    });


    //LISTAGEM BUSCA
    $("#buscarapida").keyup(function(event){
        event.preventDefault();
        if(event.keyCode == '13') {
            pesquisar = "&nome="+$("#buscarapida").val();
            table();
        }
        return true;
    });

    $("#filtrar").click(function(e){
        e.preventDefault();
        pesquisar = "&"+$("#formAvancado").serialize();
        table();
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
          table();
    });

});

//DATATABLE USUARIOS
var myColumnDefs = [
    {key:"idusuario", sortable:true, label:"ID", print:true, data:true},
    {key:"nomecompleto", sortable:true, label:"Nome Completo", print:true, data:true},
    {key:"usuario", sortable:true, label:"Usuário", print:true, data:true},
    {key:"email", sortable:true, label:"E-mail", print:true, data:true},
    {key:"ultima_acao", sortable:true, label:"Última Ação", print:true, data:true},
    {key:"logadoimg", sortable:false, label:"Logado", print:false, data:true}
];


function columnUsuario(){
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

function dataTable(){

        limit = $("#limit").val();
        pagina = $("#pagina").val();
        pagina = parseInt(pagina) - 1;
        colunas = myColumnDefs;
        colunas = JSON.stringify(colunas);
        queryDataTable = requestInicioUsuario+"&ordem="+ordem+pesquisar+"&dir="+dir+"&colunas="+colunas;
        $.ajax({
               url: "base_proxy.php",
               dataType: "json",
               type: "post",
               data: requestInicioUsuario+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+"&dir="+dir,
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
                                tr += '<a href="index.php?mod=usuario&acao=formUsuario&met=editUsuario&idu='+value.idusuario+'"><img src="images/ico_edit.png" height="16" width="16" alt="ico" /><div class="tt"><span class="one">Editar</span><span class="two"></span></div></a>';
                                tr += '<a href="#" onclick="wConfirm(\'Excluir Usuário\',\'Tem certeza que deseja excluir o usuário '+value.usuario+' ?\',\'php\', \'usuario_script.php?opx=deletaUsuario&idu='+value.idusuario+'\');"><img src="images/ico_del.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Excluir</span><span class="two"></span></div></a>';
                                tr += '<a href="index.php?mod=usuario&acao=relatorioAcesso&idu='+value.idusuario+'"><img src="images/relatorio.png" height="17" width="17" alt="ico" /><div class="tt"><span class="one">Relatório</span><span class="two"></span></div></a>';
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

var myColumnDefsRelatorio = [
    {key:"idusuario", sortable:true, label:"ID Usuário", print:true, data:true},
    {key:"usuario", sortable:true, label:"Nome de Usuário", print:true, data:true},
    {key:"data", sortable:false, label:"Data/Hora do Acesso", print:true, data:true},
    {key:"ip", sortable:false, label:"IP", print:true, data:true}
];

function columnRelatorioAcesso(){
    tr = "";
    $.each(myColumnDefsRelatorio, function(col, ColumnDefs){
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

function dataTableRelatorioAcesso(busca){

    buscar = busca;
    limit = $("#limit").val();
    pagina = $("#pagina").val();
    pagina = parseInt(pagina) - 1;
    colunas = myColumnDefsRelatorio;
    colunas = JSON.stringify(colunas);
    requestInicioUsuario = "tipoMod=usuario&p="+preventCache+"&tipo=relatorioacesso&";
    queryDataTable = requestInicioUsuario+"&ordem="+ordem+pesquisar+"&tipo=relatorioacesso"+buscar+"&colunas="+colunas;;
    $.ajax({
           url: "base_proxy.php",
           dataType: "json",
           type: "post",
           data: requestInicioUsuario+"&limit="+limit+"&pagina="+pagina+"&ordem="+ordem+pesquisar+buscar,
           beforeSend: function () {
                  Loader.show();
                  $('#listagem').find("tbody tr").remove();
           },
           success:function(data){
                  tr = "";
                  if(data.totalRecords > 0){
                       $.each(data.records, function(index, value){
                            tr += '<tr>';
                            $.each(myColumnDefsRelatorio, function(col, ColumnDefs){
                                  if(ColumnDefs['data']){
                                        key = ColumnDefs['key'];
                                        tr += '<td><span>'+value[key]+'</span></td>';
                                  }
                            });
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

function table(){
    if($("#listagem").attr("name") == "relatorio"){
        dataTableRelatorioAcesso(buscar);
     }else{
        dataTable();
     }
} 

$(document).ready(function(){

   $('#updloadImagem').click(function(){
      var urlArquivo= "files/images/php/";
     
      $(this).fileupload({
         url: urlArquivo,
         dataType: 'json',
         disableImageResize: /Android(?!.*Chrome)|Opera/
         .test(window.navigator && navigator.userAgent),
         done: function (e, data) { 
             ref = $(this); 
             var novo = "";
             var tipo = "";
             var imageName = "";
             $.each(data.result.files, function (index, file) {
                  refImagem = $(ref).closest(".content");
                  $(refImagem).find("#img_imagem").attr("src","files/images/thumbs/"+file.name);
                  imageName = $(refImagem).find("#apagarFoto").val();
                  if(imageName != ""){
                     excluirImagem("images/thumbs,images/thumbs2,images", imageName);
                  }

                  if($(refImagem).find("#fotoUsuario").length){
                      $(refImagem).find("#fotoUsuario").val(file.name);
                  }else{
                    $(refImagem).find("#foto").val(file.name);
                  }
                  
                  $(refImagem).find("#apagarFoto").val(file.name);
                  $(refImagem).find("#deleteImagem").show();  
                   
             });

             $("#progress").css("display","none");
             Loader.hide();
         },
         progressall: function (e, data) {
             ref = $(this);
             refImagem = $(ref).closest(".content");
             $(refImagem).find("#progress").css("display","block");
             Loader.show();
             var progress = parseInt(data.loaded / data.total * 100, 10);
              $(refImagem).find('#progress .progress-bar').css(
                 'width',
                 progress + '%'
             );
         }
      }).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
  })
  
  
  //validacao do usuario
  $("#informacaoUsuario").on("blur", '#nome, #sobrenome', function(){
      if($('#nome').val() != "" && $('#sobrenome').val() != ""){ 
          var params = {
             nome:     $('#nome').val(),
             sobrenome:  $('#sobrenome').val()
          }; 

          $.post(
            'usuario_script.php?opx=validaUsuario',
            params,
            function(data){
              if(data.length){
                $('#vUser').val('invalido');
                $("#nome").val("").closest(".box_ip").removeClass("focus");
                $("#sobrenome").val("").closest(".box_ip").removeClass("focus");
                msgErro("Usuário já cadastrado com o mesmo Nome e Sobrenome!");
              }
              else{
                $('#vUser').val('valido');
              }
            },'json'
          );
      }
  });

  //validacao do email
  $("#informacaoUsuario").on("blur", '#email', function(){
      if($('#email').val() != ""){
        var params = {
          email: $('#email').val(),
          idusuario: $("#idusuario").val() 
        };  
      $.post(
          'usuario_script.php?opx=validaEmail',
          params,
          function(data){ 
            if(!data.status){ 
              $("#email").val("").closest(".box_ip").removeClass("focus");
              msgErro("Email já cadastrado no sistema");
              $("#email").val($("#emailantigo").val()).closest(".box_ip").addClass("focus");
            } 
          },'json'
      );
    }
  });

  //validacao da senha 1
  $("#informacaoUsuario").on("blur", '#senha1', function(){  
        if($(this).val() != "" && $(this).val().length < 6){
            $(this).val("").css("border","1px solid red").closest(".box_ip").removeClass("focus");
            msgErro('Sua senha deve conter no mínimo 6 caracteres!');
        }else if($("#senha2").val() != ""){
            if($(this).val() != $("#senha2").val()){
                $("#senha2").val("").css("border","1px solid red").closest(".box_ip").removeClass("focus");
                msgErro("Sua senha e a senha da confirmação não são iguais!");
            }
        }
  });

  //validacao da senha 2
  $("#informacaoUsuario").on("blur", '#senha2', function(){  
        if($(this).val() != "" && $(this).val().length < 6){ 
            $(this).val("").css("border","1px solid red").closest(".box_ip").removeClass("focus");
            msgErro('A confirmação da senha deve conter no mínimo 6 caracteres!');
        }else if($("#senha1").val() != ""){
            if($(this).val() != $("#senha1").val()){
                $(this).val("").css("border","1px solid red").closest(".box_ip").removeClass("focus");
                msgErro("Os campos Senha e Confirmação de Senha devem ser iguais!");
            }
        } 
  }); 
 

  //validacao do cadastro
  $(".bt_save").click(function(event){
        event.preventDefault(); 
        var valida = true; 
        msg = "";
        campo = "";
        Loader.show();    
        $('#form2').find('.required').each(function(){ 
            $(this).css("border","1px solid #d9d9d9");  
            if($.trim($(this).val()) == ''){ 
                $(this).css("border", "solid 1px red");
                valida = false; 
                msg = 'Preencha o(s) campo(s) obrigatórios!';
                if(campo == ""){ 
                  campo = $(this);
                }  
            } 
            else if($(this).attr('name') == "email"){  
                //valida o email
                var reEmail = new RegExp(/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/gi);
                var email = $(this).val();
                if(!email.match(reEmail)) {
                    $(this).css("border", "1px solid red");  
                    if(campo == ""){ 
                      msg = "Informe um email válido";
                      campo = $(this);
                    }
                    valida = false;
                }   
            }  
        }); 
 
        marcou = false;
        permissao = $("#form2 .contentPermissao").find("input[type='checkbox']");
        $.each(permissao, function(index, value){ 
            if($(this).is(":checked")){
                marcou = true;
                $(".contentPermissao .content_tit").css("color","#000");
            }
        });

        if(!marcou){
            valida = false;
            $(".contentPermissao .content_tit").css("color","red");
            if(msg == ""){
                msg = "Selecione pelo menos uma Permissão!";
            }
        } 

        if(valida){
            Loader.show();   
            $('#form2').submit();
        }else{
           Loader.hide();
           $(campo).focus();   
           msgErro(msg);
        }
    }); 
}) 

