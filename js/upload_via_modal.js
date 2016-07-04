/* 
 * Orientar modificações que necessitem de javascript 
 */


$(document).ready(function(){
    arquivo.carrega_form();
});

$(function(){
    
   $('.espaco-thumbs').on('click','.deletar',function(){
        var item = $(this).attr('data-item');
        var arquivo = $(this).attr('data-arquivo');
        var local = $(this).attr('data-local');
        var elemento = '.elemento-' + item;
        var confirma = confirm('Tem certeza que deseja deletar este item?');
        if ( confirma )
        {
            var request = {};
            request.id = item;
            request.id_arquivo = arquivo;
            request.local = local;
            var url_form = URL_HTTP + 'ope/administra_arquivos.php?class=arquivos&function=deleta&retorno=json';
            $.post(url_form,request, function( data ){
                if ( data.status )
                {
                    $(elemento).remove();
                    alert('Item deletado com sucesso.');
                }
                else
                {
                    alert(data.mensagem);
                }


            },'json');
        }
        
        
    });
    
    $('.espaco-thumbs').on('click','.salvar',function(){
        var item = $(this).attr('data-item');
        var elemento = '.elemento-' + item;
        var request = {};
        request.id = item;
        request.titulo = $(elemento + ' .titulo').val();
        request.descricao = $(elemento + ' .descricao').val();
        var url_form = URL_HTTP + 'ope/administra_arquivos.php?class=arquivos&function=editar_pai&retorno=json';
        $.post(url_form,request, function( data ){
            if ( data.status )
            {
                alert('Item salvo com sucesso.');
            }
            else
            {
                alert(data.mensagem);
            }
            
            
        },'json');
        
        
        
    });


});

var arquivo = {
    acao: function( data ){
        console.log(data);
        if ( ! data.erro )
        {
            window.parent.retorno_image.acao(data);
            
        }
        else
        {
            alert(data.message);
            $('.espaco-carregando').html('Um erro ocorreu, tente novamente.');
            setTimeout(function(){
                $('.status').html('');

            },5000);
        }
    },
    carrega_form : function(){
        /**
        * @version 1.1
        * @since 06/04/2015
        * processo de upload
        * @type Array
        */
        var data = new Array();
            data.funcao_acao = 'arquivo';
            data.classe = '.upload';
            data.acao = URL_HTTP + 'anexos/upload_via_modal';
            data.input = 'upload';
            data.multiple = false;
            data.resposta_classe = '.status';
            data.resposta_type = 'json';
            data.pasta = URL_HTTP + 'js/upload2/';
            data.extra = '<input type="hidden" name="id" value="' + $('.espaco-arquivos').attr('data-id') + '">';
            data.extra += '<input type="hidden" name="tabela" value="' + $('.espaco-arquivos').attr('data-tabela') + '">';
            data.extra += '<input type="hidden" name="classe" value="' + $('.espaco-arquivos').attr('data-classe') + '">';
            data.extra += '<input type="hidden" name="tipo" value="' + $('.espaco-arquivos').attr('data-tipo') + '">';
            data.limite_kb = '1886080';
            data.type = $('.espaco-arquivos').attr('data-formatos');
            upload.inicia(data);

    },
};

