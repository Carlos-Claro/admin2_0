$(function(){
    
    $('#sel_todos').on({
        change : function(){
            if($(this).is(':checked'))
            {
                $('.groups').prop('checked',true);
            }
            else
            {
                $('.groups').prop('checked',false);
            }
        }
    });
    
    $('.editar').on({
        click : function(){
            var url = URI+"encurtador/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
           
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"encurtador/remover/";
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
    
    $('#link_encaminhado').on('blur', function(){
       
        var valor = $(this);
        
        if(valor.val().indexOf('http://') === -1)
        {
            var http = 'http://'+valor.val();
            valor.val(http);
            
            //alert('Preencha o campo Link Encaminhado com "http://" ');
            //valor.focus();
        }
    });
    
    $('.gera-link').on('click',function(){
        $('.gera-link').html('Gerando...');
        var enc = $('.link_encaminhado').val();
        if ( enc == '' )
        {
            $('.help-block').html('O link encaminhado precisa estar preenchido.');
        }
        else
        {
            $('.help-block').html('');
            var inc = $.md5(enc);
            console.log(inc);
            var link = inc.substr(0,5);
            $('.link_encurtado').val(link);
        }
        $('.gera-link').html('Gerar Automático');
        
    });
    $(".salva").on({
        click: function(e){
                var retorno = false;
                $('.salva').html('Verificando...');
                var id = $('.id').val();
                var encurtado = $('.link_encurtado').val();
                if ( encurtado != '' )
                {
                    var url = URI + 'encurtador/verifica_link/' + encurtado + '/' + id
                    $.get(url, function(data){
                        if ( data == 1 )
                        {
                            retorno_variavel.conteudo = true;
                            $('.help-block').html('');
                            $('form').submit();
                        }
                        else
                        {
                            retorno_variavel.conteudo = false;
                            $('.help-block').html('O link já existe, tente novamente.');
                        }
                        
                    });
                    /*
                    setTimeout(function(){
                        return retorno_variavel.conteudo;
                    }, 500);
                    */
                    $('.salva').html('Salvar');
                }
                else
                {
                    $('.help-block').html('Link em branco, preencha para continuar.')
                    e.stopPropagation();
                }
        },
    });
var retorno_variavel = {
    conteudo: false,
};
});
