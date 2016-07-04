$(function(){
    
    $('.editar').on({
        click : function(){
            
            var url;
            
            if($('.valor_pai').length)
            {
                var pai = ($('.valor_pai').length) ? $('.valor_pai').val() : '';
                url = URI+"setor/editar_nivel_2/"+$(this).attr('data-item')+"/"+pai;
            }
            else
            {
                url = URI+"setor/editar/"+$(this).attr('data-item')+"/";
            }
            window.location.href = url;
           
            
            /*
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var url = "";
                    for ($i=0; $i < $selecionados.length; $i++)
                    {
                            url = URI+"setor/editar/"+$selecionados[$i];
                            window.open(url);
                    }
            }
            else
            {
                    alert('nenhum item selecionado');
            }*/
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"setor/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                    pop_up(data, setTimeout(function(){location.reload()}, 100));
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


