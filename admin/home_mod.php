<link type="text/css" rel="stylesheet" href="css/home.css" />
<div class="hello">			
			<div class="hello_left">
				<p class="hello_left_name">Olá, <b><?=ucwords($_SESSION['sgc_nome'])." ".ucwords($_SESSION['sgc_sobre_nome'])?></b></p>
			<?php
            $ultimo_acesso = '';
				$ultimo_acesso = buscaUltimoAcesso($_SESSION['sgc_idusuario']);
				$ultimo_acesso = $ultimo_acesso[0];
			?>
				<p class="hello_left_last">Último Acesso: <?=$ultimo_acesso['datahora']?>   |   IP de Acesso: <?=$_SERVER["REMOTE_ADDR"]?></p>
			</div>
			<div class="hello_weather">
			<?php
				if(isset($_SESSION['localizacao'])) {
					$tempo = $_SESSION['localizacao']['tempo'];
					$cidade = $_SESSION['localizacao']['cidade'];
					$min = $_SESSION['localizacao']['min'];
					$max = $_SESSION['localizacao']['max'];
				} else {
					$tempo = 'images/previsao_tempo/sem-informacao.png';
					$cidade = $info['cidade'];
					$min = '__';
					$max = '__';
				}
			?>
				<img class="hello_weather_ico" src="<?=$tempo?>" height="65" width="65" alt="ico" />
				<span class="hello_weather_city"><?=$cidade?></span>
				<div class="hello_weather_mm">
					<span id="tmin"><?=$min?>º <small>min</small></span>
					<span id="tmax"><?=$max?>º <small>max</small></span>
				</div>
			</div>
			<div class="hello_date">
				<span class="hello_date_dt"><?=date('d/m/Y')?></span>
				<span class="hello_date_hr" id="txt"></span>
			</div>
		</div>