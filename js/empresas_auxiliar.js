$(function(){
    
    $('.remover-auxiliar').on({
        click : function(){
            
            var url = URI+"empresas/remover_auxiliar/"+$(this).attr('data-item')+"/";
            if (window.confirm("deseja remover o item? "))
            {
                    $.post( url, function(data){
                        if ( data )    
                        {
                            location.reload();
                        }
                        else
                        {
                            alert('não foi possivel deletar')
                        }
                        //pop_up(data, location.reload());
                    }, 'json');
            }
            
            
        }
    });
    
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
    
    
});