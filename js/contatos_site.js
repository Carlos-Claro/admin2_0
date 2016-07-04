$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"contatos_site/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"contatos_site/remover/";
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
        
        var url = URI+"canais/gera_link_automatico/";
       
        $.post(url, { titulo: titulo } ,function(data){
            
            $.post(URI+'canais/verifica_link/',{ link : data }, function(valor){
                
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
    $('#busca_empresa').on('keyup', function()
    {
        
        $('.resposta_empresa').html('');
       
        var busca = '';
        busca += $(this).val();
        
        setTimeout(function(){
            
            $.getJSON(URI+'contatos_site/get_empresa/'+busca, function(data)
            {
                //console.log(data);
                var lista = '';

                lista += '<div class="list-group">';
                $(data).each(function(i)
                {
                    lista += '<a href="#" style="z-index:999" class="list-group-item" id="list-item" data-item="'+data[i].id+'">'
                    lista += '<strong>'+data[i].descricao+'</strong>';
                    lista += '</a>'
                });
                lista += '</div>';

                $('.resposta_empresa').html(lista);

            });
            
        },'500');
        
    });
    
    $('.resposta_empresa').on('click', '#list-item', function(e){
        
            e.preventDefault();
            var id = $(this).attr('data-item');
            var descricao = $(this).text();
            $('#id_empresa').val(id);
            $('#busca_empresa').val(descricao);
            $('.resposta_empresa').html('');
    });
    
   
});


