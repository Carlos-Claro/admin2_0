<?php


if ( isset($abas) && is_array($abas) ) :
    foreach ( $abas as $aba ) :
        $navegacao[] = '<li role="presentation" class="'.($aba['active'] ? 'active' : '').' "><a href="#'.$aba['classe'].'">'.$aba['titulo'].'</a></li>';
        $conteudo[] = '<div role="tabpanel" class="tab-pane '.($aba['active'] ? 'active' : '').'" id="'.$aba['classe'].'">'.$aba['conteudo'].'</div>';
    endforeach;
    ?>
    <ul class="nav nav-pills nav-justified" id="abas">
        <?php 
        echo implode('',$navegacao);
        ?>
    </ul>
    <div class="tab-content">
        <?php 
        echo implode('',$conteudo);
        ?>
    </div>
    <?php
endif;
