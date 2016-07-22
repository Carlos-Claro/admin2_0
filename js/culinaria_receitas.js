$(function(){

   
    $('#telefone').mask('(99)9999-9999');
    $('#data_cadastro').mask('99-99-9999');
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"culinaria_receitas/editar/"+$(this).attr('data-item')+"/"+$('.valor_pai').val();
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"culinaria_receitas/remover/";
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
    
    $('.moderar').on('click',function(){
        var item = $(this).attr('data-item');
        var url = URI + 'culinaria_receitas/moderar_image/' + item;
        $.post(url,function( data ){
            if ( data > 0 )
            {
                alert('moderado');
                location.reload();
                //$('.elemento' + item + ' .moderar').remove();
            }
            else
            {
                alert('Não foi póssivel moderar.');
            }
        },'json');
    });
    
});
