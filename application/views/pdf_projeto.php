<?php 
if ( isset($print) ) :
    ?>
    <script type="text/javascript">
    $(document).ready(function(){
        window.print();
    });

    </script>
    <?php 
endif;
?>
<div class="container-fluid text-left">
        <div class="alert alert-info principal">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <input type="hidden" name="id" class="id" value="<?php echo isset($item->id) ? $item->id : 0;?>">
                    <input type="hidden" name="id_tarefas_portfolio" class="id_tarefas_portfolio" value="<?php echo $item_tarefas_portfolio->id;?>">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-heading"><strong>Informações do Portfolio</strong></li>
                        <li class="list-group-item"><strong>Portfolio: </strong><?php echo $item_tarefas_portfolio->titulo;?></li>
                        <li class="list-group-item"><strong>Descrição: </strong><?php echo $item_tarefas_portfolio->descricao;?></li>
                        <li class="list-group-item"><strong>Demanda Semanal: </strong><?php echo $item_tarefas_portfolio->demanda_semanal;?> hs</li>
                        <li class="list-group-item"><strong>Responsável: </strong><?php echo $item_tarefas_portfolio->responsavel;?></li>
                    </ul>

                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">
                                Titulo
                            </h4>
                            <p>
                                <?php 
                                echo $item->titulo;
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">
                                Setor Responsável
                            </h4>
                            <p>
                                <?php 
                                foreach( $cargos as $usuario )
                                {
                                    if ( $item->id_setor_responsavel == $usuario->id )
                                    {
                                        echo $usuario->descricao;
                                    }
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-6">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">
                                Responsável
                            </h4>
                            <p>
                                <?php 
                                foreach( $usuarios as $usuario )
                                {
                                    if ( $item->id_responsavel == $usuario->id )
                                    {
                                        echo $usuario->descricao;
                                    }
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12 pull-right">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">
                                Status do Projeto
                            </h4>
                            <p>
                                <?php 
                                foreach( $status as $usuario )
                                {
                                    if ( $item->status_projeto == $usuario->id )
                                    {
                                        echo $usuario->descricao;
                                    }
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12 pull-right">
                    <div class="media">
                        <div class="media-body">
                            <h4 class="media-heading">
                                Descrição
                            </h4>
                            <p>
                                <?php 
                                echo nl2br($item->descricao);
                                ?>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        
        <div class="itens">
            <div class="alert alert-info">
                <div class="row">
                    <?php 
                    if ( isset($item->premissas) ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    Premissas
                                </h4>
                                <p>
                                    <?php 
                                    echo $item->premissas;
                                    ?>
                                </p>
                            </div>
                        </div>

                    </div>
                    <?php 
                    endif;
                    if ( isset($item->requisitos) ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    Requisitos
                                </h4>
                                <p>
                                    <?php 
                                    echo $item->requisitos;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php 
                    endif;
                    if ( isset($item->exclusao_escopo) ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    Exclusão do escopo
                                </h4>
                                <p>
                                    <?php 
                                    echo $item->exclusao_escopo;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php 
                    endif;
                    ?>
                    <?php 
                    if ( isset($item->restricoes) ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    Restrições
                                </h4>
                                <p>
                                    <?php 
                                    echo $item->restricoes;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php 
                    endif;
                    if ( isset($item->riscos_iniciais) ) :
                    ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="media">
                            <div class="media-body">
                                <h4 class="media-heading">
                                    Riscos Iniciais
                                </h4>
                                <p>
                                    <?php 
                                    echo $item->riscos_iniciais;
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php 
                    endif;
                    ?>
                </div>
            </div>
            <?php 
            if ( isset($has_usuarios) && ! empty($has_usuarios) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Equipe</h4>
                    </div>
                </div>
                <div class="espaco-has-usuarios">
                    <?php 
                    echo $has_usuarios;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            if ( isset($aceite) && ! empty($aceite) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Critérios de Aceite</h4>
                    </div>
                </div>
                <div class="espaco-aceite">
                    <?php 
                    echo $aceite;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            if ( isset($marcos) && ! empty($marcos) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Principais Marcos do Projeto</h4>
                    </div>
                </div>
                <div class="espaco-marcos">
                    <?php 
                    echo $marcos;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            if ( isset($comunicacao) && ! empty($comunicacao) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Eventos e Comunicação do Projeto.</h4>
                    </div>
                </div>
                <div class="espaco-comunicacao">
                    <?php 
                    echo $comunicacao;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            if ( isset($qualidade) && ! empty($qualidade) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Medição e critérios de qualidade.</h4>
                    </div>
                </div>
                <div class="espaco-qualidade">
                    <?php 
                    echo $qualidade;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            if ( isset($riscos) && ! empty($riscos) ) :
            ?>
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <h4>Possiveis riscos. Positivos e negativos.</h4>
                    </div>
                </div>
                <div class="espaco-riscos">
                    <?php 
                    echo $riscos;
                    ?>
                </div>
            </div>
            <?php 
            endif;
            ?>
        </div>
    </div>
      