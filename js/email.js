$(function(){
    
    $('.editar_administrar').on({
        click : function(){
            
            var url = URI+"email_mkt/editar_administrar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    /**
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"empresas/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                    pop_up(data, location.reload());
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    */
    
});