$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"cadastro_interesse/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"cadastro_interesse/remover/";
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
    
});


