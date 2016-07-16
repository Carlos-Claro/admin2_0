$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"bairros_equivalentes/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"bairros_equivalentes/remover/";
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
    $('#busca_cidade').on('keyup', function()
    {
        
        $('.resposta_cidade').html('');
       
        var busca = '';
        busca += $(this).val();
        
        setTimeout(function(){
            
            $.getJSON(URI+'bairros_equivalentes/get_cidade/'+busca, function(data)
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

                $('.resposta_cidade').html(lista);

            });
            
        },'500');
        
    });
    
    $('.resposta_cidade').on('click', '#list-item', function(e){
        
            e.preventDefault();
            var id = $(this).attr('data-item');
            var descricao = $(this).text();
            $('#id_cidade').val(id);
            $('#busca_cidade').val(descricao);
            $('.resposta_cidade').html('');
    });
    $('#busca_bairro').on('keyup', function()
    {
        
        $('.resposta_bairro').html('');
       
        var busca = '';
        busca += $(this).val();
        
        setTimeout(function(){
            
            $.getJSON(URI+'bairros_equivalentes/get_bairro/'+busca, function(data)
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

                $('.resposta_bairro').html(lista);

            });
            
        },'500');
        
    });
    
    $('.resposta_bairro').on('click', '#list-item', function(e){
        
            e.preventDefault();
            var id = $(this).attr('data-item');
            var descricao = $(this).text();
            $('#id_bairro').val(id);
            $('#busca_bairro').val(descricao);
            $('.resposta_bairro').html('');
    });
});


