$(function(){
    
    $('.editar').on({
        click : function(){
            var url = URI+"empresas_contato/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"empresas_contato/remover/";
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
    
    $('#telefone').on({
        
        blur: function(){
            switch($(this).val().length)
            {
                case 10:
                    $(this).mask('(99)9999-9999');
                    break;
                case 11:
                    $(this).mask('(999)9999-9999');
                    break;
            }
        },
        
        focus : function(){
            $(this).unmask();
        }
    });
    
    $('#busca_empresa').on('keyup', function()
    {
        
        $('.resposta_empresa').html('');
       
        var busca = '';
        busca += $(this).val();
        
        setTimeout(function(){
            
            $.getJSON(URI+'dica/get_empresa/'+busca, function(data)
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


