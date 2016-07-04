
if ( classe_default == undefined )
{
    var classe_default = 'form';
}

$(function(){
    $(document).ready(function(){
        var editavel = $('.editavel').val();
        if ( editavel == 0 )
        {
            $('input,textarea,select').attr('disabled','disabled');
        }
        
    });
    autosave.verifica_hide();
    var historico = {};
    $(classe_default).on('focus', 'input,textarea', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        if ( historico[campo] === undefined )
        {
             historico[campo] = valor;
        }
    });
    $('.historico').on('click',function(){
        var campo = $(this).attr('data-campo');
        var valor = historico[campo];
        $('#' + campo).val(valor);
        var sequencia = $('#' + campo).attr('data-sequencia');
        var nao_salva = $('#' + campo).attr('data-nao-salva');
        var tabela = $('#' + campo).attr('data-tabela');
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, tabela);
            $('.historico.' + campo).removeClass('hide');
            $('#' + campo).focus();
        }
        $('.historico.' + campo).addClass('hide');
        
    });
    $(classe_default).on('change', 'input,textarea', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        var sequencia = $(this).attr('data-sequencia');
        var nao_salva = $(this).attr('data-nao-salva');
        var tabela = $('#' + campo).attr('data-tabela');
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, tabela);
            $('.historico.' + campo).removeClass('hide');
            $(this).focus();
        }
    });
    $(classe_default).on('change', 'select', function(){
        var campo = $(this).attr('name');
        var valor = $(this).val();
        var sequencia = $(this).attr('data-sequencia');
        var nao_salva = $(this).attr('data-nao-salva');
        var tabela = $(this).attr('data-tabela');
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, tabela);
            $(this).focus();
        }
    });
    
    /**
     * ação de bloqueio ou liberação de cadastro.
     */
    $('.btn-acao').on('click',function(){
        var editavel = $('.editavel').val();
        if ( editavel == 1 )
        {
            var item = $(this).attr('data-item');
            var campo = $(this).attr('data-campo');
            var campo_chave = 'id';
            var valor = ( item == 0 ) ? "1" : "0";
            var sequencia = 0;
            var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'empresas';
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
                        if ( campo == 'bloqueado' )
                        {
                            $('.identificacao').removeClass('alert-success').addClass('alert-danger');
                        }
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
                        if ( campo == 'bloqueado' )
                        {
                            $('.identificacao').removeClass('alert-danger').addClass('alert-success');
                        }
                    }
                }
                else
                {
                    $(this).html('Clique e tente novamente.');
                }
            },1000);
        }
        else
        {
            alert('não autorizado');
        }
    });
    $('.contavel').on({
        keyup: function(){
            var classe = $(this).attr('name');
            contador.por_classe('#' + classe, '.contador_' + classe);
        }
    });
    
});