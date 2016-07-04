/*
 * Classe destinada a gerenciar as tarefas, inserir, deletar, editar.
 * Tarefas é destinada a uso interno e gerenciamento de tempo 
 */

/*  */


var tarefas = {
    listar : function(){
        var id = $('.id').val();
        var url = URI + 'tarefas/listar_retorno/' + id;
        $.post(url,function(data){
            if ( data.listagem )
            {
                $('.espaco-tarefas').html('').html(data.listagem);
            }
            else
            {
                
            }
        },'json').fail(function(e,r){
            alert('Problemas ao adquirir tarefas, tente novamente. ' + e + r);
        });
        
    },
};
var classe_default = '.principal';
$(function(){
    
    $('.pdf-projeto').on({
        click: function(e){
            e.preventDefault();
            var item = $('.status').attr('data-item');
            var url = URI + 'tarefas_projetos/pdf/' + item;
            abre_janela(url, '_blank');
        },
    });
    
    $('.doc-equipe').on({
        click: function(e){
            e.preventDefault();
            var item = $('.status').attr('data-item');
            var url = URI + 'tarefas_projetos/avisa_equipe/' + item;
            $.post(url, function(data){
                if ( data.status.erro )
                {
                    alert('Enviado com sucesso');
                    
                }
                else
                {
                    alert('Não foi possivel enviar, Tente novamente.');
                    
                }
            },'json');
            
            //abre_janela(url, '_blank');
        },
    });
    
    if ( $('#descricao').val() != undefined )
    {
        contador.por_classe('#descricao', '.contador_descricao');
        contador.por_classe('#premissas', '.contador_premissas');
        contador.por_classe('#requisitos', '.contador_requisitos');
        contador.por_classe('#exclusao_escopo', '.contador_exclusao_escopo');
        contador.por_classe('#restricoes', '.contador_restricoes');
        contador.por_classe('#riscos_iniciais', '.contador_riscos_iniciais');
        $('textarea').on({
            keyup: function(){
                var id = $(this).attr('id');
                contador.por_classe('#' + id, '.contador_' + id);

            },
        });
        
    }
    $('.abre-instrucoes ').on('click', function(){
        var classes_aparece = $('.espaco-instrucoes').attr('class');
        var aparece = classes_aparece.indexOf('show');
        console.log(aparece);
        if ( aparece > 0 )
        {
            $('.espaco-instrucoes').removeClass('show').addClass('hide');
            $('.abre-instrucoes ').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        }
        else
        {
            $('.espaco-instrucoes').removeClass('hide').addClass('show');
            $('.abre-instrucoes ').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });
    
    $(document).on ('click','.data_db', function(){
        mascara.inicia();
    });
    
    $('.cronograma_requisita').on('click', function(){
        var id = $('.id').val();
        var url = URI + 'tarefas_projetos/monta_cronograma/' + id;
        $.post(url,function(data){
            if ( data.status )
            {
                $('.espaco-cronograma').html('').html(data.cronograma);
            }
            else
            {
                alert(data.mensagem);
            }
        },'json').fail(function(e,r){
            alert('Problemas ao adquirir tarefas, tente novamente. ' + e + r);
        });
    });
    
    
    $('.tarefas_requisita').on('click', function(){
        tarefas.listar();
    });
    
    $('.adicionar-novo-por-tipo').on('click',function(){
        var tipo = $(this).attr('data-tipo');
        var classe = '.espaco-' + tipo.replace('_', '-');
        var qtde_elementos = $(classe + ' .elementos').length;
        var url = URI + 'tarefas_projetos/set_has/' + tipo + '/echo/' +  ( qtde_elementos++ ) ;
        $.post( url, function( data ) {
            $(classe).prepend( data );
        });
        mascara.inicia();
    });
    
    $('.add_tarefas').on('click', function(){
        var url = URI+"tarefas/editar/" + $('.id').val();
        abre_janela(url, 1000);
    });
    
    $(document).on('click', '.salva-elemento', function(){
        var tipo = $(this).attr('data-tipo');
        var elementos = autosave.matriz(tipo);
        var elemento = $(this).attr('data-elemento');
        var salvo = $(this).attr('data-salvo');
        var classe = '.espaco-' +  tipo.replace('_', '-');
        var data = {};
        var retorna = true;
        data['id_tarefas_projetos'] = $('.id').val();
        $.each(elementos,function(k,v){
            data[k] = $(classe + ' .elemento-' + elemento + ' #' + k).val();
            if ( v == 'required' &&  ( data[k] == 'undefined' || data[k] == '' ) )
            {
                retorna = false;
            }
        });
        if ( retorna )
        {
            var url = URI + 'tarefas_projetos/set_salva/' + tipo + '/' +  ( ( salvo == 1 ) ? elemento : 0 );
            $.post( url, data, function( data ) {
                if ( data.status )
                {
                    $( classe + ' .elemento-' + elemento + ' input').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' textarea').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' select').attr('disabled','disabled');
                    $( classe + ' .elemento-' + elemento + ' .salva-elemento').addClass('edita-elemento').attr('data-salvo',1).attr('data-elemento',data.id).removeClass('salva-elemento').html('<span class="glyphicon glyphicon-plus-sign"></span>');
                    $( classe + ' .elemento-' + elemento + ' .tarefa-elemento').removeClass('hide').attr('data-salvo',1).attr('data-elemento',data.id);
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
    
    $(document).on('click', '.salva-elemento-iteracao', function(){
        var tipo = $(this).attr('data-tipo');
        var elementos = autosave.matriz(tipo);
        var elemento = $(this).attr('data-elemento');
        var classe = '.espaco-' + ( tipo == 'marcos' ? 'principais-' : '' ) + tipo.replace('_', '-');
        var data = {};
        var retorna = false;
        data['id_tarefas_projetos'] = $('.id').val();
        data['id_usuario'] = $('.id_usuario_sessao').val();
        var id_pai = $(classe + ' .elemento-' + elemento + ' .id_pai').val();
        if ( id_pai != undefined )
        {
            data['id_pai'] = id_pai;
            
        }
        data['message'] = $(classe + ' .elemento-' + elemento + ' #message').val();
        var z = {};
        $( classe + ' #usuarios:checked').each(function( a, b ){
            z[a] = $(this).val();
        });
        data['avisados'] = z;
        if ( z[0] != undefined && data['message'] != "" )
        {
            retorna = true;
        }
        if ( retorna )
        {
            var url = URI + 'tarefas_projetos/set_salva_iteracao/';
            $.post( url, data, function( dataa ) {
                console.log(dataa.data);
                if ( dataa.status )
                {
                    var url_ = URI + 'tarefas_projetos/requisita_iteracao/' + dataa.data.id_tarefas_projetos;
                    $.post(url_,function(li){
                        $('.espaco-iteracao .elementos').remove();
                        $('.interacoes').html(li);
                    });
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
    
    $('.espaco-iteracao').on('click','.responder',function(){
        var item = $(this).attr('data-item');
        console.log(item);
        var classe = '.elemento-' + item + ' .espaco-resposta';
        var qtde_elementos = 0;
        var url = URI + 'tarefas_projetos/requisita_iteracao/0/' + item;
        $.post( url, function( data ) {
            $(classe).prepend( data );
        });
        mascara.inicia();
    });
    
    $(document).on('click', '.tarefa-elemento', function(){
        var tipo = $(this).attr('data-tipo');
        var elemento = $(this).attr('data-elemento');
        var id = $('.id').val();
        var salvo = $(this).attr('data-salvo');
        if ( salvo == 1 )
        {
            var url = URI+"tarefas/editar/" + id + "/0/0/" + elemento;
            abre_janela(url,1001);
        }
        else
        {
            alert("Não é possivel criar tarefas com elementos não salvos.");
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
            var url = URI + 'tarefas_projetos/set_deleta/' + tipo + '/' +  elemento;
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
    
    $('.ver').on({
        click : function(){
            var url = URI+"tarefas/editar/"+$(this).attr('data-item');
            window.location.href = url;
        }
    });  
    $('.editar').on({
        click : function(){
            var url = URI+"tarefas_projetos/editar/" + $(this).attr('data-portfolio') + "/" + $(this).attr('data-item');
            window.location.href = url;
        }
    });
    
    
    
    
});