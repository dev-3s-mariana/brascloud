<div class="paginacao d-flex">
    <?php
        if($total > 1) {

            if($pag > 0){
                // echo "<div class='pag d-flex'><a class='d-flex' href='".$urlpag.(($pag > 1)? "/".$pag:"")."'>❮</a></div>";
            }

            $total_paginas = $total;

            $aux = 0;
            $pagAux = 0;

            if($total_paginas > 5)
                $limitPaginacao = 4;
            else
                $limitPaginacao = $total_paginas - 1;

            if($pag >= 3) {
                $pagAux = $pag - 2; 
                if(($pag + 1) == $total_paginas)
                    $limitPaginacao = $limitPaginacao - 1;
            } 

            while($aux <= $limitPaginacao) {
                $num = $pagAux + 1;

                if($pagAux == $pag){
                    echo  "<div class='pag d-flex active-pag'><a class='d-flex' href='javascript:;'>".$num."</a></div>";
                }else if($num<= $total_paginas){
                    echo  "<div class='pag d-flex'><a class='d-flex' href='".$urlpag.(($num > 1)? "/".$num:"")."'>".$num."</a></div>";
                }   

                $pagAux = $pagAux + 1;
                $aux = $aux + 1;
            }

            if($pag < $total - 1){
                // echo '<div class="pag d-flex"><a class="d-flex" href="'.$urlpag."/".($pag + 2).'">❯</a></div>';
            }
        } 
    ?>
</div> 