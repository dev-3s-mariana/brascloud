const Loader = {
   show(){
       // Inicia o Loader
       document.querySelector('.loader').classList.add('active');
   },
   hide(){
       // Inicia o Loader
       document.querySelector('.loader').classList.remove('active');
   }
}

function retirarAcentos(string) {
	var retorno = (string).replace(/[áàâã]/g,'a').replace(/[éèê]/g,'e').replace(/[óòôõ]/g,'o').replace(/[úùû]/g,'u');
	return retorno;
}

function getLocation(){
	if(navigator.geolocation){
		navigator.geolocation.getCurrentPosition(showPosition, showPositionError);
	} else {
		console.log('geolocation IS NOT available');
	}
}

function showPosition(position){
   var cidade = "";
   $.ajax({
     url: "https://geolocation-db.com/jsonp",
     jsonpCallback: "callback",
     dataType: "jsonp",
     success: function(position) {
       cidade = position.city;
       $.ajax({
          url: 'https://www.agencia.red/dev/previsaotempo_ws/previsao.php?cidade='+cidade,
          dataType: 'json',
          type: 'post',
          data: null,
          success: function(data) {
             if(data.status==true) {
                $.ajax({
                   url: 'adiciona_localizacao.php?tempo='+data.tempo+'&cidade='+data.cidade+'&min='+data.min+'&max='+data.max,
                   dataType: 'json',
                   type: 'post',
                   data: null,
                   success: function(data) {
                      if(data.status==true) {
                         console.log('Setou a localizacao!');
                      } else {
                         console.log('Não setou a localizacao!');
                      }
                   }
                });
                $('.hello_weather_ico').attr('src',data.tempo);
                $('.hello_weather_city').html(data.cidade);
                $('#tmin').html(data.min+'º <small>min</small>');
                $('#tmax').html(data.max+'º <small>max</small>');
             }
          }
       });
     }
   });
}

function showPositionGoogle(position){
	var geocoder = new google.maps.Geocoder();
	var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
	geocoder.geocode({
		'latLng': latlng
	}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			if (results[1]) {
				var local = {
					cidade:'',
					estado:''
				};
				for(var i in results[1].address_components){
					var endereco = results[1].address_components[i];
					for(var n in endereco.types){
						if(endereco.types[n] == 'locality'){
							localizacao = retirarAcentos(endereco.short_name);
							$.ajax({
								url: 'https://www.agencia.red/dev/previsaotempo_ws/previsao.php?cidade='+localizacao,
								dataType: 'json',
								type: 'post',
								data: null,
								success: function(data) {
									if(data.status==true) {
										$.ajax({
											url: 'adiciona_localizacao.php?tempo='+data.tempo+'&cidade='+data.cidade+'&min='+data.min+'&max='+data.max,
											dataType: 'json',
											type: 'post',
											data: null,
											success: function(data) {
												if(data.status==true) {
													console.log('Setou a localizacao!');
												} else {
													console.log('Não setou a localizacao!');
												}
											}
										});
										$('.hello_weather_ico').attr('src',data.tempo);
										$('.hello_weather_city').html(data.cidade);
										$('#tmin').html(data.min+'º <small>min</small>');
										$('#tmax').html(data.max+'º <small>max</small>');
									}
								}
							});
						}
					}
				}
			}
		} else {
			if ($('.hello_weather_city').length) {
				localizacao = retirarAcentos($('.hello_weather_city').html());
				$.ajax({
					url: 'https://www.agencia.red/dev/previsaotempo_ws/previsao.php?cidade='+localizacao,
					dataType: 'json',
					type: 'post',
					data: null,
					success: function(data) {
						if(data.status==true) {
							$.ajax({
								url: 'adiciona_localizacao.php?tempo='+data.tempo+'&cidade='+data.cidade+'&min='+data.min+'&max='+data.max,
								dataType: 'json',
								type: 'post',
								data: null,
								success: function(data) {
									if(data.status==true) {
										console.log('Setou a localizacao!');
									} else {
										console.log('Não setou a localizacao!');
									}
								}
							});
							$('.hello_weather_ico').attr('src',data.tempo);
							$('.hello_weather_city').html(data.cidade);
							$('#tmin').html(data.min+'º <small>min</small>');
							$('#tmax').html(data.max+'º <small>max</small>');
						}
					}
				});
			}
		}
	});
}

function showPositionError() {
	if ($('.hello_weather_city').length) {
		localizacao = retirarAcentos($('.hello_weather_city').html());
		$.ajax({
			url: 'https://www.agencia.red/dev/previsaotempo_ws/previsao.php?cidade='+localizacao,
			dataType: 'json',
			type: 'post',
			data: null,
			success: function(data) {
				if(data.status==true) {
					$.ajax({
						url: 'adiciona_localizacao.php?tempo='+data.tempo+'&cidade='+data.cidade+'&min='+data.min+'&max='+data.max,
						dataType: 'json',
						type: 'post',
						data: null,
						success: function(data) {
							if(data.status==true) {
								// console.log('Setou a localizacao!');
							} else {
								// console.log('Não setou a localizacao!');
							}
						}
					});
					$('.hello_weather_ico').attr('src',data.tempo);
					$('.hello_weather_city').html(data.cidade);
					$('#tmin').html(data.min+'º <small>min</small>');
					$('#tmax').html(data.max+'º <small>max</small>');
				}
			}
		});
	}
}

$('input[placeholder], textarea[placeholder]').each(function(){
	var ph = $(this).attr('placeholder')
	$(this).val(ph).focus(function(){
		if($(this).val() == ph) $(this).val('')
	}).blur(function(){
		if(!$(this).val()) $(this).val(ph)
	})
})

function startTime() {
	var today=new Date();
	var h=today.getHours();
	var m=today.getMinutes();
	var s=today.getSeconds();
	// add a zero in front of numbers<10
	m=checkTime(m);
	s=checkTime(s);
	if(document.getElementById('txt')!=null){
		document.getElementById('txt').innerHTML=h+":"+m+":"+s;
		t=setTimeout('startTime()',500);
	}
}

function checkTime(i){
	if (i<10) {
		i="0" + i;
	}
	return i;
}

$(document).ready(function(){
	if($('#localizacao').val()=='N') {
		getLocation();
	}

	$(".alteraImagem").click(function(e){
		 e.stopPropagation();
		 $.ajax({
				data: { 'opx': 'formAlterarImagem'},
				success: function(form){
				   wConfirm('Alterar Foto',
							form,
							'ajax',
							alterarImagem);
				 },
				type: 'post',
				dataType: 'html',
				url: 'usuario_script.php'
		});

	})

	function alterarImagem(){
		window.location = "usuario_script.php?opx=updateImagem&foto="+$("#fotoUsuario").val();
	}

	// function teste(){
	//	 alert('aeeasase');
	// }
	$('#logoffBtn').click(function(event){
		event.preventDefault();
		wConfirm('Sair',
				'Tem certeza que deseja sair?',
				'php',
				'usuario_script.php?opx=logout');
	});


	$('.menu_close').click(function(){
		$.ajax({
			data: { 'opx': 'gravarMenuLateral'},
			success: function(telaLogistica){
				$('.menu, .bg_menu').toggleClass('close');
				$('.menu').toggleClass('open');
				$('footer').toggleClass('footer_small');
			},
			type: 'post',
			url: 'usuario_script.php'
		});
		return false;
	});

	$('.open .menu_ul_li').on('click', function(){
		$(this).toggleClass('hover');
	});

	$('.open .menu_user_inner').click(function(){
		$(this).find('.huser_menu').slideToggle('slow');
	});

	// $('.close .menu_bts').live('click', function(){
	// 	$('.menu_bts_inner').slideToggle('slow');
	// });

	$('.ftop').click(function(){
		$('html, body').animate({scrollTop: 0}, 500);
		return false;
	});

	$('.box_ip input, .box_ip textarea').each(function(){
		if($(this).val()){
			$(this).parent().addClass('focus');
		}
	});

	$('.box_ip input, .box_ip textarea').focusin(function(){
		$(this).parent().addClass('focus');
	});

	$('.box_ip input, .box_ip textarea').focusout(function(){
		if(!$(this).val()){
			$(this).parent().removeClass('focus');
		}
	});

	$('.box_sel_d select').change(function(){
		$(this).parent().parent().addClass('focus');
		$(this).find('option').click(function(){
			if($(this).text() === ""){
			$(this).parent().parent().parent().removeClass('focus');
			}
		});
	});

	$('.box_sel_d select').focusout(function(){
	  $(this).find('option:selected').each(function(){
		if($(this).text() === ""){
		  $(this).parent().parent().parent().removeClass('focus');
		}
	  });
	});

	$('.box_sel_d select option:selected').each(function(){
	  if($(this).text() != ""){
		$(this).parent().parent().parent().addClass('focus');
	  }
	});

	$('.menu').css('min-height',$(this).height());

	$('.search_bt, .advanced_close').click(function(){
		$('.advanced').slideToggle('slow');
		return false;
	});
	if (typeof $.datepicker !== 'undefined') {
		$.datepicker.regional['pt'] = {
			closeText: 'Fechar',
			prevText: '<Voltar',
			nextText: 'Avançar>',
			currentText: 'Brasil',
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
			'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun',
			'Jul','Ago','Set','Out','Nov','Dez'],
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
			dayNamesShort: ['dom','seg','ter','qua','qui','sex','sáb'],
			dayNamesMin: ['dom','seg','ter','qua','qui','sex','sáb'],
			weekHeader: 'Sem',
			dateFormat: 'dd/mm/yy',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''
		};
		$.datepicker.setDefaults($.datepicker.regional['pt']);

		$('.wDate').datepicker();
		$('.wDatetime').datetimepicker({timeText: 'Hora', hourText: 'Horas', minuteText: 'Minutos', currentText: 'Agora', closeText: 'Ok'});
		$('.wTime').timepicker({timeOnlyTitle: 'Selecione horário', timeText: 'Hora', hourText: 'Horas', minuteText: 'Minutos', currentText: 'Agora', closeText: 'Ok'});
	}

	   //Busca avançada de menu //NEM RELA
	$(".menu_sec_ip").keyup(function(){
		 var pesquisa = $(this).val().toLowerCase();
		 var objAnterior='';
		 var isBlock = false;
		 if(pesquisa == ''){
		 	$(".menu_ul").find("li").show().removeClass("hover");
		 	$(".menu_ul").find(".none").show();
		 	return false;
		 }
		 $(".menu_ul").find(".none").hide();
		 $(".menu_ul").find(".menu_ul_li > ul > li > a").each(function(){
			  	 var subMenu = new String();
			  	 subMenu = $(this).html().replace(/•/g,'').toLowerCase();
			  	 if(typeof(subMenu) != 'undefined'){
				   	if(subMenu.indexOf(pesquisa) >= 0){
				 		$(this).parent().show();//LI
				 		$(this).parent().parent().parent().show().addClass("hover");
				 	}
				 	 else{
				 		 $(this).parent().hide();//LI

				 		 if($("ul > li", $(this).parent().parent().parent()).not(":visible").length == $(this).parent().parent().parent().find("ul > li").length){
				 		 	$(this).parent().parent().parent().hide().removeClass("hover");
				 		 }
				 	 }
				}

	 	 });
	});
});

function msgErro(msg){
	jError(
		msg,
		{
			autoHide : true, // added in v2.0
			clickOverlay : true, // added in v2.0
			MinWidth : 250,
			TimeShown : 3000,
			ShowTimeEffect : 200,
			HideTimeEffect : 200,
			LongTrip :20,
			HorizontalPosition : 'center',
			VerticalPosition : 'top',
			ShowOverlay : true,
			ColorOverlay : '#000',
			OpacityOverlay : 0.3,
			onClosed : function(){ // added in v2.0

			},
			onCompleted : function(){ // added in v2.0

			}
	  });
}

function msgSucesso(msg){
	jSuccess(
		msg,
		{
			autoHide : true, // added in v2.0
			clickOverlay : true, // added in v2.0
			MinWidth : 250,
			TimeShown : 3000,
			ShowTimeEffect : 200,
			HideTimeEffect : 200,
			LongTrip :20,
			HorizontalPosition : 'center',
			VerticalPosition : 'top',
			ShowOverlay : true,
			ColorOverlay : '#000',
			OpacityOverlay : 0.3,
			onClosed : function(){ // added in v2.0

			},
			onCompleted : function(){ // added in v2.0

			}
	  });
}
function wConfirm(titulo, descricao, acao, auxAcao, ref = "") {

	$("#dialog-confirm").html(descricao);
	$("#dialog-confirm").dialog({
		resizable: false,
		modal: true,
		title: titulo,
		height: 250,
		width: 400,
		buttons: {
			Continuar: function () {
				$(this).dialog('close');
				if(acao == 'php'){
					window.location = auxAcao;
				}else{
					var funcCall = auxAcao;
					if(ref != ""){
						var funcCall = auxAcao + "('" + ref + "');";
					}
					var ret = eval(funcCall)();  
				}
				// alert('Sim...');
			},
			Cancelar: function () {
				$(this).dialog('close');
				// alert('Não...');
			}
		}
	});
}
