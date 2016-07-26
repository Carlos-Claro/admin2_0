
$(function(){
    
    var historico = {};
    
    $('.historico').on('click',function(){
        var campo = $(this).attr('data-campo');
        var valor = historico[campo];
        $('#' + campo).val(valor);
        var sequencia = $('#' + campo).attr('data-sequencia');
        var nao_salva = $('#' + campo).attr('data-nao-salva');
        var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'Imoveis_destaque_bairro';
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, controller);
            $('.historico.' + campo).removeClass('hide');
            $('#' + campo).focus();
        }
        $('.historico.' + campo).addClass('hide');
        
    });

    $('.cidades').on('change',function(){
        var conteudo_anterior = $('.bairros').html();
        $('.bairros').html('carregando...');
        var cidade = $(this).val();
        var url = URI + 'bairros/get_select/' + cidade + '/json';
        
        $.post(url,function(data){
            if ( data.status )
            {
                $('.bairros').html(data.valores);
            }
            else
            {
                alert('Problemas na captação, tente selecionar outra Cidade.');
                $('#bairros').html(conteudo_anterior);
            }
        },'json');
    });
    
    $('.editar').on({
        click : function(){
            var url = URI+"imoveis_destaque_bairro/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"imoveis_destaque_bairro/remover/";
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
    