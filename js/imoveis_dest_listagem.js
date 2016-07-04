
$(function(){
    
    var historico = {};
    
    /**
     * Procedimento de autosalvamento
     */
    //autosave.verifica_hide();
    $(document).on('focus', 'input,textarea', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        if ( historico[campo] === undefined )
        {
             historico[campo] = valor;
        }
    });
    $(document).on('change', 'input,textarea', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        var sequencia = $(this).attr('data-sequencia');
        var nao_salva = $(this).attr('data-nao-salva');
        var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'imoveis_dest_listagem';
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, controller);
            $('.historico.' + campo).removeClass('hide');
            $(this).focus();
        }
    });
    $('.historico').on('click',function(){
        var campo = $(this).attr('data-campo');
        var valor = historico[campo];
        $('#' + campo).val(valor);
        var sequencia = $('#' + campo).attr('data-sequencia');
        var nao_salva = $('#' + campo).attr('data-nao-salva');
        var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'imoveis_dest_listagem';
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, controller);
            $('.historico.' + campo).removeClass('hide');
            $('#' + campo).focus();
        }
        $('.historico.' + campo).addClass('hide');
        
    });
    
    $(document).on('change', 'select', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        var sequencia = $(this).attr('data-sequencia');
        var nao_salva = $(this).attr('data-nao-salva');
        var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'imoveis_dest_listagem';
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, controller);
            $(this).focus();
        }
    });
    
    /**
     * ação de bloqueio ou liberação de cadastro.
     */
    $('.btn-acao').on('click',function(){
            var item = $(this).attr('data-item');
            var campo = $(this).attr('data-campo');
            var campo_chave = 'id';
            var valor = ( item == 0 ) ? "1" : "0";
            var sequencia = 0;
            var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'imoveis_dest_listagem';
            var classe = $(this).attr('class');
            var texto_marcado = $(this).attr('data-marcado');
            var texto_desmarcado = $(this).attr('data-desmarcado');
            var expande = $(this).attr('data-expande');
            var reverse = $(this).attr('data-reverse');
            var on = 'success';
            var off = 'danger';
            if ( reverse === '1' )
            {
                on = 'danger';
                off = 'success';
            }
            console.log(item);
            autosave.salva(campo, valor, sequencia, controller, campo_chave);
            setTimeout(function(){
                if ( autosave.retorno )
                {
                    if ( item == 0 )
                    {
                        $('button.'+campo).attr('data-item',1);
                        $('button.'+campo).html(texto_marcado);
                        if ( expande == 1 )
                        {
                            console.log('.expansivo-' + campo + ' .expansivo', expande);
                            $('.expansivo-' + campo + ' .expansivo').removeClass('hide').addClass('show');
                        }
                        $('button.'+campo).removeClass('btn-' + off ).addClass('btn-' + on );
                    }
                    else
                    {
                        $('button.'+campo).attr('data-item',0);
                        $('button.'+campo).html(texto_desmarcado);
                        if ( expande == 1 )
                        {
                            $('.expansivo-' + campo + ' .expansivo').removeClass('show').addClass('hide');
                        }
                        $('button.'+campo).removeClass('btn-' + on ).addClass('btn-' + off);
                    }
                }
                else
                {
                    $(this).html('Clique e tente novamente.');
                }
            },1000);
    });

    $('.editar').on({
        click : function(){
            
            var url = URI+"imoveis_dest_listagem/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"imoveis_dest_listagem/remover/";
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
    