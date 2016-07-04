$(function(){
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"logradouros/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"logradouros/remover/";
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