
//Mascara de Valores
function formataDinheiro(fld, e) {
	milSep = '.';
	decSep = ',';
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if ((whichCode == 13)||(whichCode == 8)|| (whichCode == 9)||(whichCode == 46)||(whichCode == 37)||(whichCode == 39)||(whichCode == 0)) return true;  // Enter
	key = String.fromCharCode(whichCode);  // Get key value from key code
	if (strCheck.indexOf(key) == -1) return false;  // Not a valid key
	len = fld.value.length;
	for(i = 0; i < len; i++)
	if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);
	aux += key;
	len = aux.length;
	if (len == 0) fld.value = '';
	if (len == 1) fld.value = '0'+ decSep + '0' + aux;
	if (len == 2) fld.value = '0'+ decSep + aux;
	if (len > 2) {
	aux2 = '';
	for (j = 0, i = len - 3; i >= 0; i--) {
	if (j == 3) {
	aux2 += milSep;
	j = 0;
	}
	aux2 += aux.charAt(i);
	j++;
	}
	fld.value = '';
	len2 = aux2.length;
	for (i = len2 - 1; i >= 0; i--)
	fld.value += aux2.charAt(i);
	fld.value += decSep + aux.substr(len - 2, len);
	}
	return false;
}


function formataData(obj, evento){
	var key = evento.keyCode  ? evento.keyCode  :
                       evento.charCode ? evento.charCode :
                       evento.which    ? evento.which    : void 0;


	//alert(key);
	if((key >= 48 && key <= 57) || (key >= 96 && key <= 105)){
		if (obj.value.length == 2 || obj.value.length == 5){
			obj.value = obj.value + "/";
		}else if(obj.value.length < 10){
			return true;
		}else{
			return false;
		}

		//return true;
	}else if(key == 191 || key == 8 || key == 9){
		return true;
	}else{
		return false;
	}
}

function formataTelefone(obj, evento){
	var key = evento.keyCode  ? evento.keyCode  :
            evento.charCode ? evento.charCode :
            evento.which    ? evento.which    : void 0;


	if((key >= 48 && key <= 57) || (key >= 96 && key <= 105)){
		if(obj.value.length == 0){
			obj.value = obj.value + "(";
		}else if(obj.value.length == 3){
			obj.value = obj.value + ")";
		}else if(obj.value.length == 8){
			obj.value = obj.value + "-";
		}else if(obj.value.length < 13){
			return true;
		}else{
			return false;
		}

		//return true;
	}else if(key == 191 || key == 8 || key == 9){
		return true;
	}else{
		return false;
	}


}

// deixa digitar somente numeros
function somenteNumero(evento){
	var key = evento.keyCode  ? evento.keyCode  :
            evento.charCode ? evento.charCode :
            evento.which    ? evento.which    : void 0;


	//alert(key);
	if((key >= 48 && key <= 57) || (key >= 96 && key <= 105)){
		return true;
	}else if(key == 191 || key == 8 || key == 9 || key == 37 || key == 39){
			return true;
	}else{
		return false;
	}
}

// verifica se todos os campos foram completos
function verificarCampos(campos){

	var verifica = true;
	var msg = "";
	for(var i = 0; i < campos.length; i++){
        tinyMCE.triggerSave();
        if(document.getElementById(campos[i]).value == '') {
			verifica = false;
			msg = "Preencha o(s) campo(s) Obrigatório(s)!";
		}
    }

    if (campos.length > 0) {
        var form = document.getElementById(campos[0]).form;
        var campo = "";
        var verifica = true;
        var elemento_border;

         if($("#imagem_icone").val() == '' && $("#imagem_value").val() == ''){
             $("#icone-titulo").css("border", "solid 1px red");
             $("#inputArquivoBotao").css("border", "solid 1px red");
             msg = "Preencha o(s) campo(s) Obrigatório(s)!";
             verifica = false;
         }
         else{
           $("#icone-titulo").css("border", "solid 0px red");
           $("#inputArquivoBotao").css("border", "solid 0px red");
         }

        $(form).find('.required').each(function(){
            elemento_border = $(this);

            if ((this.type == "select-one") || ((this.type == "textarea") && ($(this).hasClass('mceAdvanced')))) {
                elemento_border = $(this).parent();
            }
            if ($(this).attr('name') == "thumbs" || $(this).attr('name') == "banner_topo") {
               elemento_border = $(this).parent();
            }
            // else {
            //     elemento_border = $(this);
            // }  

            placeholder = $(this).attr('placeholder');
            elemento_border.css("border","1px solid #E2E4E7");

            if((!$.trim($(this).val())) || placeholder == $(this).val()){
                elemento_border.css("border", "solid 1px red");
                verifica = false; 
                if(campo == ""){
                    campo = $(this);
                }
                msg = "Preencha o(s) campo(s) Obrigatório(s)!";
            }

            if(verifica != false && $(this).attr('name') == "email"){ 
				var reEmail = new RegExp(/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/gi);
				var email = $(this).val();
		        if(!email.match(reEmail)) { 
		            $(this).css("border", "1px solid red");  
		            if(msg == ""){ 
				      msg = "Informe um email válido";
				    }
				    verifica = false;
				}   
            }
        });
    }

	if(!verifica)
		msgErro(msg);

	return verifica;
}

function validaEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function popUp(endereco){
	window.open(endereco, 'Exportar', 'width=900, height=400, status=no, location=no, toolbar=no, menubar=no, scrollbars=yes, resizable=no');
}

function campoBuscaEscreve(obj){
	obj.value = 'Buscar';
}

function campoBuscaLimpa(obj){
	obj.value = '';
}

function validaCPF (cpf)
    {if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" || cpf == "99999999999")
            return false;
	add = 0;
	for (i=0; i < 9; i ++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
	rev = 11 - (add % 11);
	if (rev == 10 || rev == 11)
            rev = 0;
	if (rev != parseInt(cpf.charAt(9)))
            return false;
	add = 0;
	for (i = 0; i < 10; i ++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
	rev = 11 - (add % 11);
	if (rev == 10 || rev == 11)
            rev = 0;
	if (rev != parseInt(cpf.charAt(10)))
            return false;
   	return true;
}

function excluirImagemOld(pastas, nomeImagem){
    var postData = 'deleta_imagem_ajax.php?opx=deletaImagem&pastas='+pastas+'&nomeimagem='+nomeImagem;
    $.post( postData, function() { });
}

function excluirImagem(modulo = '', tipo = '', img = '', thisElem = ''){
    $.ajax({
        url: modulo+'_script.php',
        dataType: 'json',
        type: 'post',
        data: {opx: 'excluirImagem', tipo: tipo, img: img, id: $('input[name=id'+modulo+']').val()},
        beforeSend: function(){
            Loader.show();
        },
        success: function(data){
            Loader.hide();
            $(thisElem).parent().hide();
            $(thisElem).siblings('.img-banner-form').attr('src','');
            console.log(tipo);
            $('input[name='+tipo+']').val('');
        }
    });
}

function retirarAcento(objResp) {
	var varString = new String(objResp.value);
	var stringAcentos = new String('àâêôûãõáéíóúçüÀÂÊÔÛÃÕÁÉÍÓÚÇÜ');
	var stringSemAcento = new String('aaeouaoaeioucuAAEOUAOAEIOUCU');

	var i = new Number();
	var j = new Number();
	var cString = new String();
	var varRes = '';

	for (i = 0; i < varString.length; i++) {
		cString = varString.substring(i, i + 1);
		for (j = 0; j < stringAcentos.length; j++) {
			if (stringAcentos.substring(j, j + 1) == cString){
				cString = stringSemAcento.substring(j, j + 1);
			}
		}
		varRes += cString;
	}
	objResp.value = varRes;
}
