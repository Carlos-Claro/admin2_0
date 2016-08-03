$(function(){
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"empresas/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
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
    
    $('.bloquear-empresa').on({
        click : function(){
            var $selecionadas = get_selecionados();
            if($selecionadas.length > 0)
            {
                    var $url = URI+"empresas/bloquear_empresa/";
                    if (window.confirm("Deseja bloquear as ("+$selecionadas.length+") empresas selecionadas? "+$url))
                    {
                            $.post($url, { 'selecionados': $selecionadas}, function(data){
//                                     pop_up(data, setTimeout(function(){location.reload()}, 500));
                                        console.log(data);
                            });
                    }
            }
            else
            {
                    pop_up('Nenhuma empresa selecionada');
                    return false;
            }
        }
    });
    
    $('.desbloquear-empresa').on({
        click : function(){
            var $selecionadas = get_selecionados();
            if($selecionadas.length > 0)
            {
                    var $url = URI+"empresas/desbloquear_empresa/";
                    if (window.confirm("Deseja desbloquear as ("+$selecionadas.length+") empresas selecionadas? "))
                    {
                            $.post($url, { 'selecionados': $selecionadas}, function(data){
                                     //pop_up(data, setTimeout(function(){location.reload()}, 500));
                                     console.log(data);
                            });
                    }
            }
            else
            {
                    pop_up('Nenhuma empresa selecionada');
                    return false;
            }
        }
    });
    
});