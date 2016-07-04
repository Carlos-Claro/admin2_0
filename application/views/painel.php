<?php 
if ( isset($inacessivel) ) :
    ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="alert alert-danger">
            <h3>Inacessivel para: <?php echo $inacessivel;?>, contate o RH.</h3>
        </div>
    </div>
</div>
    <?php
endif;
?> 
<div class="row">
    
    <?php   if(isset($ocorrencias) && !empty($ocorrencias) ): ?>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <?php  echo $ocorrencias; ?>
            <div class="row">
                <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;  ?>
    
    <?php if(isset($tarefas) && !empty($tarefas) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $tarefas; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php   if(isset($campanhas) && !empty($campanhas) ): ?>
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <?php echo $campanhas; ?>
        <div class="row">
            <div class="paginacao col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php if (isset($paginacao)) : echo $paginacao; endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
</div>