/*
 * Classe destinada a gerenciar as tarefas, inserir, deletar, editar.
 * Tarefas é destinada a uso interno e gerenciamento de tempo 
 */

/*  */



var regras = {
    listar : function(){
        var id = $('.id').val();
        var url = URI + 'email_mkt/listar_regras/' + id;
        $.post(url,function(data){
            if ( data.listagem )
            {
                $('.espaco-regras').html('').html(data.listagem);
            }
            else
            {
                
            }
        },'json').fail(function(e,r){
            alert('Problemas ao adquirir regras, tente novamente. ' + e + r);
        });
        
    },
};

var tags = {
    inicia : function(){
        tags.set_abas();
        var tags_json = $('.espaco-tags').html();
        $('.espaco-tags').html('');
        console.log(tags_json);
    },
    set_abas : function(){
        var id = $('#id').val();
        if ( id !== '0' )
        {
            $('.itens').removeClass('hide');
        }
        var active = $('.itens .active');
        if ( active != undefined )
        {
            var elemento = $('.itens .elemento-1').attr('data-item');
            $('.itens .elemento-1').addClass('active');
            $('.itens #'+elemento).addClass('active');
            $('.itens #'+elemento).html('<br><br><br><center><img src="' + URI + 'images/loader_azul.gif"></center>');
            tags.set_corpo(elemento);
        }
    },
    set_corpo : function( elemento ){
        var id = $('#id').val();
        var url = URI + 'email_mkt/get_elemento/' + id + '/' + elemento + '/echo';
        $.get(url,function(data){
            $('.itens #'+elemento).html(data);
        });
    },
    
    
};



var classe_default = '.item';
$(function(){
    
    $(document).ready(function(){
        tags.inicia();
        
    });
    $(document).on('change',function(){
        tags.set_abas();
    });
    
    $('.abas').on('click',function(){
        var elemento = $(this).attr('data-item');
        $('.itens #'+elemento).html('<br><br><br><center><img src="' + URI + 'images/loader_azul.gif"></center>');
        tags.set_corpo(elemento);
    });
    
    if ( $('#descricao').val() != undefined )
    {
        contador.por_classe('#titulo', '.contador_titulo');
        contador.por_classe('#descricao', '.contador_descricao');
        $('textarea').on({
            keyup: function(){
                var id = $(this).attr('id');
                contador.por_classe('#' + id, '.contador_' + id);

            },
        });
        
    }
    
    $('.regras_requisita').on('click', function(){
        regras.listar();
    });
    
    $('.adicionar-novo-por-tipo').on('click',function(){
        var tipo = $(this).attr('data-tipo');
        var classe = '.espaco-' + tipo.replace('_', '-');
        var qtde_elementos = $(classe + ' .elementos').length;
        var url = URI + 'email_mkt/set_has/' + tipo + '/echo/' +  ( qtde_elementos++ ) ;
        $.post( url, function( data ) {
            $(classe).prepend( data );
        });
        mascara.inicia();
    });
    
    $(document).on('click', '.salva-elemento', function(){
        var tipo = $(this).attr('data-tipo');
        var elementos = autosave.matriz(tipo);
        var elemento = $(this).attr('data-elemento');
        var salvo = $(this).attr('data-salvo');
        var classe = '.espaco-' +  tipo.replace('_', '-');
        var data = {};
        var retorna = true;
        $.each(elementos,function(k,v){
            data[k] = $(classe + ' .elemento-' + elemento + ' #' + k).val();
            if ( v == 'required' &&  ( data[k] == 'undefined' || data[k] == '' ) )
            {
                retorna = false;
            }
        });
        if ( retorna )
        {
            var url = URI + 'email_mkt/set_salva/' + tipo + '/' +  ( ( salvo == 1 ) ? elemento : 0 );
            $.post( url, data, function( data ) {
                if ( data.status )
                {
                    $( classe + ' .elemento-' + elemento + ' input').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' textarea').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' select').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' .salva-elemento').addClass('edita-elemento').attr('data-salvo',1).attr('data-elemento',data.id).removeClass('salva-elemento').html('<span class="glyphicon glyphicon-plus-sign"></span>');
                    $( classe + ' .elemento-' + elemento).addClass('elemento-' + data.id).removeClass('elemento-' + elemento);
                    //<span class="glyphicon glyphicon-plus-sign"></span><span class="glyphicon glyphicon-save"></span>
                }
                else
                {
                    alert('tivemos problemas ao salvar o item, tente novamente...');
                }
                    
            }, 'json');
            
        }
        else
        {
            alert('Preencha todos os campos para salvar o item');
        }
    });
    
    $(document).on('click', '.edita-elemento', function(){
        var tipo = $(this).attr('data-tipo');
        var elemento = $(this).attr('data-elemento');
        var salvo = $(this).attr('data-salvo');
        var classe = '.espaco-' + tipo.replace('_', '-');
        $( classe + ' .elemento-' + elemento + ' input').removeAttr('disabled');
        $( classe + ' .elemento-' + elemento + ' textarea').removeAttr('disabled');
        $( classe + ' .elemento-' + elemento + ' select').removeAttr('disabled');
        $( classe + ' .elemento-' + elemento + ' .edita-elemento').addClass('salva-elemento').removeClass('edita-elemento').html('<span class="glyphicon glyphicon-save"></span>');
    });
    $('.alert').on('click', '.deleta-elemento', function(){
        var tipo = $(this).attr('data-tipo');
        var elemento = $(this).attr('data-elemento');
        var salvo = $(this).attr('data-salvo');
        var classe = '.espaco-' + tipo.replace('_', '-');
        if ( salvo == '1' )
        {
            var url = URI + 'email_mkt/set_deleta/' + tipo + '/' +  elemento;
            $.post( url, function( data ) {
                if ( data.status )
                {
                    $(classe + ' .elemento-' + elemento).html('').removeClass('alert-success');
                }
                else
                {
                    alert('tivemos problemas ao deletar o item, tente novamente...');
                }
                    
            }, 'json');
            
        }
        else
        {
            $(classe + ' .elemento-' + elemento).html('').removeClass('alert-success');
        }
    });
    
    $('.editar').on({
        click : function(){
            var url = URI+"email_mkt/editar_administrar/" + $(this).attr('data-item');
            window.location.href = url;
        }
    });
    
    
    
    
});