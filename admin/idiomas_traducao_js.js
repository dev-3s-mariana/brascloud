$(document).ready(function(){
	$(".salvar").click(function(){
    	cadastrarTag();
    })

    $(".listagemtags").on("blur","textarea[name='traducao_tag']",function(){
    		cadastrarTraducao($(this));
    })

    vericarTraducao();

    $(".listagemtags").on("click",".deletetag",function(){
    	tag = $(this).closest(".tags").find('input[name="tag"]').val();
    	wConfirmTag("Excluir Tag", "Deseja realmente excluir a tag "+tag, $(this).closest(".tags"))
    })

    $('#principal').on('click', '#enviarBusca', function(){
      buscaTraducao();
    });

    $('#principal').on('click', '#limparBusca', function(){
      $('#buscar').val('');
      $('#enviarBusca').click();
    });

   labels = $(".nameTag");
   tam = 0;
   $.each(labels, function(index, value){
      if(tam < $(this).width()){
         tam = $(this).width();
      }
   })

   $(".nameTag").css("width",tam+"px");   
})

function buscaTraducao() {
   var busca = $("#buscar").val();
   var div = $(".tags");

   div.each(function(){
      if(($(this).children('label').text().indexOf(busca) > -1) || ($(this).children('div').children('textarea').val().indexOf(busca) > -1)){
         $(this).show();
      }
      else{
         $(this).hide();
      }
   });
}

function cadastrarTag(){  
	tag = $("#novatag").val();
	texto = $("#novo_texto").val();
	$("#novatag").css("border","1px solid #e2e4e7");
	if(tag == ""){
		msgErro('Informe o nome da tag');
		$("#novatag").css("border","1px solid red");
	}else{
		ididioma = $("#ididiomas").val();  
		nomeidioma = $("#idioma").val();  
		$.ajax({
			url: "idiomas_traducao_script.php",
			dataType: "json",
			type: "post",
			data: "opx=cadastroIdiomas_traducao&tag="+tag+"&ididiomas="+ididioma+"&texto="+texto+"&ajax=true",
			beforeSend: function () {
			   Loader.show();
			},
			success:function(data){	
				if(data.status == 0){
					msgErro(data.msg);
				}else if(data.status){
					tag = data.tag;
					html = "<div class='tags'>";
					html += "<label class='nameTag'>"+tag+"</label>";
					//sempre criar a div portugues para todos
					html += "<div class='box_ip'>";
					html += "<label for='traducao_tag'>Português</label>";
					html += "<textarea name='traducao_tag'>"+data.texto+"</textarea>";
					html += "<input type='hidden' name='idioma_tag' value='1'>";
					html += "</div>";

					if(ididioma != 1){
						//so criar essa div se o idioma não for portugues
						html += "<div class='box_ip'>";
						html += "<label for='traducao_tag'>"+nomeidioma+"</label>";
						html += "<textarea name='traducao_tag'></textarea>";
						html += "<input type='hidden' name='idioma_tag' value='"+ididioma+"'>";
						html += "</div>";
					} 
					html += "<img src='images/delete.png' class='deletetag'>";
					html += "<input type='hidden' name='tag' value='"+tag+"'>";
					html += "</div>";

					$(".listagemtags").append(html);
					$("#novatag").val("");
					$("#novo_texto").val("");					
					$("#novo_texto").closest(".box_ip").removeClass('focus');
					$("#novatag").closest(".box_ip").removeClass('focus');
					focus();
					vericarTraducao();
				}else{
					alert('sad');
				}	
				 
			},
			complete:function(){
			   Loader.hide();
			}
			 
		});
	}
}

function cadastrarTraducao(ref){ 
	
	tag = $(ref).closest(".tags").find("input[name='tag']").val();
	ididioma = $(ref).closest(".box_ip").find("input[name='idioma_tag']").val();
	texto = $(ref).val();
	 
	$.ajax({
		url: "idiomas_traducao_script.php",
		dataType: "json",
		type: "post",
		data: "opx=cadastrarTraducao&tag="+tag+"&ididiomas="+ididioma+"&texto="+texto+"&ajax=true",
		beforeSend: function () {
		   Loader.show();
		},
		success:function(data){  
			if(typeof data.logado != "undefined" && !data.logado){
				window.location = "login.php?msg=Acesso+Negado%21";
			}
			else if(data.status == 0){
				msgErro(data.msg);
			}
			vericarTraducao(); 
		},
		complete:function(){
		   Loader.hide();
		}
	});
}


function excluirTag(ref){
	tag = $(ref).find('input[name="tag"]').val();
	$.ajax({
		url: "idiomas_traducao_script.php",
		dataType: "json",
		type: "post",
		data: "opx=excluirTag&tag="+tag,
		beforeSend: function () {
		   Loader.show();
		},
		success:function(data){ 
			 if(data.status){
			 	msgSucesso(data.msg);
			 	$(ref).remove();
			 }else{
			 	msgErro(data.msg);
			 }
		},
		complete:function(){
		   Loader.hide();
		}
	});
}


function focus(){

	$('.box_ip textarea').each(function(){
		if($(this).val()){
			$(this).parent().addClass('focus');
		}
	});

	$('.box_ip textarea').focusin(function(){
		$(this).parent().addClass('focus');
	});

	$('.box_ip textarea').focusout(function(){
		if(!$(this).val()){
			$(this).parent().removeClass('focus');
		}
	});
}

function vericarTraducao(){
	texts = $(".listagemtags textarea");
	$.each(texts, function(index, value){
		if($.trim($(this).val()) == ''){
			$(this).css("border","1px solid red");
		}else{
			$(this).css("border","1px solid #e2e4e7");
		}	
	}) 
}

//EXCLUIR  
function wConfirmTag(titulo, descricao, ref) {  
    $("#dialog-confirm").html(descricao);
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: titulo,
        height: 250,
        width: 400,
        closeOnEscape: false,
        buttons: {  
            Excluir: function () { 
                excluirTag(ref);
                $(this).dialog('close');   
            },
            Cancelar: function () { 
                $(this).dialog('close');  
            }
        }
    });  
}