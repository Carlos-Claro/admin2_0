

<li class="alert alert-info atividade elemento-<?php echo $item['id'];?>">
    <div class="row">
        <p class="col-lg-11 col-sm-11 col-md-11 col-xs-11 pull-left font-maior"><?php echo nl2br($item['descricao']);?></p>
        <button type="button" class="col-lg-1 col-sm-1 col-md-1 col-xs-1 close deleta-atividade pull-right" data-id="<?php echo $item['id'];?>" aria-label="Close" tiitle="deletar atividade"><span aria-hidden="true">&times;</span></button>
        <p class="col-lg-6 col-sm-6 col-md-6 col-xs-6 pull-left" >Previsão: <?php echo $item['previsao_tempo'];?>hs. - Data Limite: <?php echo $item['data_fim'];?></p>
        <p class="col-lg-5 col-sm-5 col-md-5 col-xs-5 pull-left" >Usuarios da tarefa: 
                        <?php 
                        foreach( $item['usuarios'] as $usuarios ) :
                            echo $usuarios['descricao'].', ';
                        endforeach;
                        ?></p>
        
        <div class="pull-right col-lg-1 col-sm-1 col-md-1 col-xs-1 controles">
            <button class="tempo-atividade-<?php echo $item['id'];?> tempo-tarefas-<?php echo $item['id_tarefas'];?> btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 trabalhar-atividade" data-id="<?php echo $item['id_tarefas'];?>" data-id-atividade="<?php echo $item['id'];?>"><span class="glyphicon glyphicon-play" aria-hidden="true" title="trabalhar nesta atividade"></span></button>
            <button class="btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 fechar-atividade" data-id="<?php echo $item['id_tarefas'];?>" data-id-atividade="<?php echo $item['id'];?>"><span class="glyphicon glyphicon-off" aria-hidden="true" title="finalizar esta atividade"></span></button>
        </div>
    </div>
    <!-- usuarios designados -->
        
    <!-- 
    interacoes
    -->
    <div class="row">
        <button class="pull-left col-lg-1 col-sm-1 col-md-1 col-xs-1 btn btn-default pull-left ver-interacoes" data-id="<?php echo $item['id_tarefas'];?>" data-id-atividade="<?php echo $item['id'];?>" title="ver Interação"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> 
        <h3 class="pull-right col-lg-11 col-sm-11 col-md-11 col-xs-11">Interacoes</h3>
        <hr>
        <div class="hide interacoes-<?php echo $item['id'];?> col-lg-12 col-sm-12 col-md-12 col-xs-12">
            <div class="erro-interacoes"></div>
            <ul class="interacoes">
                <?php 
                foreach( $item['interacoes'] as $interacao ) :
                    ?>
                <li class=""><?php echo $interacao['descricao'].' - '.$interacao['data'].' - '.$interacao['usuario'];;?></li>
                    <?php 
                endforeach;
                    ?>
            </ul>
            <button class="btn btn-default adicionar-interacoes" data-id="<?php echo $item['id_tarefas'];?>" data-id-atividade="<?php echo $item['id'];?>" title="Adicionar Interação">Adicionar interação</button>
            <div class="espaco-interacoes"></div>
        </div>
    </div>
</li>