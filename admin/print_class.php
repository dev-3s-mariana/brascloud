<?php session_start();
function exportaTela($dados){
	if(!empty($dados)){

?>


<style type="text/css">
	.banner{
		background:#fff;
		font-family:Arial, Helvetica, sans-serif;
		font-size:14px;
    font-weight: bold;
	}

	.rodape{
		background:#dddddd;
		font-size:10px;
		color:#666;
	}
	table{
		font-family:Arial, Helvetica, sans-serif;
    font-size:10px;
	}

</style>
<table border="0" width="100%">
  <tr>
    <td><div class="banner">
    <table width="100%" border="0" >
          <tr>
            <td style="width: 100%; text-align: center;"><span class="banner">Relat&oacute;rio <?= (isset($_SESSION['titulo_relatorio']))?$_SESSION['titulo_relatorio']:ucwords($dados['modulo']); ?></span></td>
          </tr>
    </table>
    </div>
</td>
  </tr>
  <tr>
    <td>
    	<?php
          print '<table width="100%" cellpadding="2" cellspacing="0">';
          $cor = '#eeeeee';
          $dados = $dados['valores'];
          foreach($dados as $k=>$v){
              if($cor == '#ffffff')
                      $cor = '#eeeeee';
              else
                      $cor = '#ffffff';

              if($k == 0){
                      print '<tr>';
                      foreach($v as $titulos=>$vv){
                              print '<td align="left" bgcolor="#cccccc"><b>'.ucfirst($titulos).'</b></td>';
                      }
                      print '</tr>';
              }

              print '<tr>';
              foreach($v as $kk=>$campos){
                      print '<td bgcolor="'.$cor.'">'.$campos.'</td>';
              }
              print '</tr>'; 
          }
          print '</table>';
      ?>
    </td>
  </tr>
  <tr>
    <td class="rodape" align="left"> Emitido em: 
        <?php 
            $date = date('d/m/Y H:i');
            echo $date;
        ?>
    </td>
  </tr>
</table>
<?php
	}
}

?>
<script type="text/javascript">
	//window.print();
</script>
