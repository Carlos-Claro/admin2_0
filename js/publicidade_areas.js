$(function(){
    $('.seleciona-id_guia').on('change',function(){
        var valor = $(this).val();
        var url = URI + 'publicidade_areas/get_setores/' + valor;
        $.post(url,function(){
            
        },'json')
    });
    
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"publicidade_areas/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"publicidade_areas/remover/";
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
    $('#meta_descricao').on({
        keyup: function(){
            console.log('teste');
            contador.por_classe('#meta_descricao', '.contador_descricao');
            
        },
    });
    $('.titulo').on('blur',function(){
        
        var valor = $(this).val();
	var id = $('.id').val();
        
        if(valor != '')
        {
            $.post(URI +'publicidade_areas/gera_link/',{ 'valor' : valor, 'id' : id}, function(data)
            {
                    $('.link').val(data);
            });
        }
        
    });

});
