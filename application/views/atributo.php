<div class="alert">
    <div class="alert alert-info">
        <h3 class="help-block">
            <?php
            echo $item->observacao;
            ?>
        </h3>
    </div>
    <div class="alert alert-success">
        <ul class="list-group marcados list-inline">
            <?php
            foreach( $ativos as $item ) :
                ?>
                <li class="list-group-item btn">
                    <?php echo $item->chave;?>
                </li>
                <?php    
            endforeach;
            ?>
        </ul>
    </div>
    <div class="alert alert-warning">
        <ul class="list-group desmarcados list-inline">
            <?php
            foreach( $itens['itens'] as $item ) :
                ?>
                <li class="list-group-item btn">
                    <?php echo $item->chave;?>
                </li>
                <?php    
            endforeach;
            ?>
        </ul>
    </div>
</div>