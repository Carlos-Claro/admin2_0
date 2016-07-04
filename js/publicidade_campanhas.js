
var retorno_image = {
    acao : function(data){
        $('#modal-base').modal('hide');
        var id = $('.id').val();
        setTimeout(function(){
            var caminho = URL_RAIZ + 'publicidade/' + data.arquivo;
            var html = '<center><img src="' + caminho + '" class="image " data-item="' + data.classe + '" ></center>';
            html += '<button type="button" class="btn btn-danger form-control deleta-image" data-item="' + data.classe + '">Deletar</button>';
            $('.form-group.' + data.classe + ' .espaco-image').html(html);
        },1000);
    },
};

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
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, 'publicidade_campanhas');
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
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, 'publicidade_campanhas');
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
        if ( nao_salva == undefined )
        {
            autosave.salva(campo, valor, sequencia, 'publicidade_campanhas');
            $(this).focus();
        }
    });
    
    $(document).on('click','.image',function(){
        var classe = $(this).attr('data-item');
        var tabela = 'publicidade_campanhas';
        var titulo = $(this).attr('data-titulo');
        var id = $('.id').val();
        $('#modal-base').modal('show');
        $('#modal-base .modal-body').html('<iframe style="width:100%; height:100%;" scrolling="yes" frameborder="0" src="' + URI + 'anexos/upload_via_modal/' + tabela + '/' + classe + '/' + id + '/campo" style="color:#FFFFFF;"></iframe>');
        $('#modal-base .modal-title').html('Administraçao de Imagens - ' + titulo + '.');
    });
    
    $(document).on('click','.deleta-image',function(){
        var campo = $(this).attr('data-item');
        var valor = '';
        var sequencia = 0;
        var titulo = $('.form-group.' + campo + ' label').html();
        var conf = confirm('Confirma a exclusão do item ' + titulo );
        if ( conf )
        {
            autosave.salva(campo, valor, sequencia, 'publicidade_campanhas');
            setTimeout(function(){
                if ( autosave.retorno )
                {
                    var html = '<button type="button" class="btn btn-success image form-control" data-item="' + campo + '" data-titulo="' + titulo + '">Upload ' + titulo + '</button>';
                    $('.form-group.' + campo + ' .espaco-image').html(html);
                }
                else
                {
                    alert('Não foi possivel, deletar, tente novamente em instantes.');
                }
            },600);
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
            var controller = ( $(this).attr('data-controller') !== undefined ) ? $(this).attr('data-controller') : 'publicidade_campanhas';
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
            
            var url = URI+"publicidade_campanhas/editar/"+$(this).attr('data-item')+"/";
            window.location.href = url;
        }
    });
    
    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"publicidade_campanhas/remover/";
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
    