$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"bairros_proximidade/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"bairros_proximidade/remover/";
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
    
    $('#titulo').on('blur', function(){
        
        var titulo =  $(this).val();
        
        var url = URI+"bairros_proximidade/gera_link_automatico/";
       
        $.post(url, { nome: titulo } ,function(data){
            
            $.post(URI+'bairros_proximidade/verifica_link/',{ link : data }, function(valor){
                
                if(valor == 0)
                {
                    $('#link').val(data);
                }
                else
                {
                    $('#link').val(valor);
                }
                console.log(valor);
                
            });
            
        });
        
    });
    
    $('#description').on('keyup',function(){

            var alvo  = $("#char-digitado");

            var max = 140;

            var digitados = $(this).val().length;

            var restante = max - digitados;

            if(digitados > max)
            {
                var val = $(this).val();
                $(this).val(val.substr(0, max));
                restante = 0;
            }

            alvo.html(restante);
    });
    $('.buscar').on('click', function()
    {
        
        $('.resultado-busca .helper-block').html('');
       
        var busca = {};
        busca.valor = $('#digite').val();
        $.post(URI+'bairros_proximidade/get_bairros/', busca, function(data)
        {
            if ( data.erro.status )
            {
                $('.resultado-busca .helper-block').html(data.erro.message);
            }
            else
            {
                $('.resultado-busca .helper-block').html(data.html);
            }
        }, 'json');
    });
    
    $('.resposta_cidade').on('click', '#list-item', function(e){
        
            e.preventDefault();
            var id = $(this).attr('data-item');
            var descricao = $(this).text();
            $('#cidade').val(id);
            $('#busca_cidade').val(descricao);
            $('.resposta_cidade').html('');
    });
    
    
    $('.resultado-busca').on('click', 'li.item_link_bairros', function(){
        var link = $(this).attr('data-item');
        var id = $(this).attr('data-id');
        var item = $('#link_bairros').val();
        console.log(link, id);
        if ( item.length > 0 )
        {
            $('#link_bairros').val( item + '-' + link );
        }
        else
        {
            $('#link_bairros').val( link );
        }
    });
   
});


