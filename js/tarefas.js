/*
 * Classe destinada a gerenciar as tarefas, inserir, deletar, editar.
 * Tarefas é destinada a uso interno e gerenciamento de tempo 
 */

$(function(){
    
    /**
     * inicia datepicker
     * gera erro se não estiver carregado o arquivo fonte, pode trazer problemas em varias funções, verificar. 
     */
    $('#data-previsao-inicio').datetimepicker();
    $('#data-previsao-fim').datetimepicker();
    /*
    $('.ver').on({
        click : function(){
            var url = URI+"tarefas/editar/"+$(this).attr('data-item');
            window.location.href = url;
        }
    });
    */
   
    $(document).on('click','.add-usuario',function(){
        $('.usuarios .help-block').html('');
        console.log('w');
       var id_tarefa = $('.id-tarefa').attr('data-item');
       var id_usuario = $(this).attr('data-item');
        var url = URI + 'tarefas/add_usuario/';
        var array = {};
        array['nome'] = $(this).html();
        array['id_usuarios'] = id_usuario;
        array['id_tarefas'] = id_tarefa;
        $.post(url,array,function(data){
            $('.usuarios .help-block').html(data.erro.mensagem);
            if ( data.erro.status )
            {
                item = '<div class="usuario btn btn-default usuario-' + data.erro.id + '" data-item="' + data.erro.id + '"><span class="nome-usuario">' + data.erro.nome + '</span>&nbsp;&nbsp;&nbsp;X</div>';
                $('.usuarios').append(item);
            }
            setTimeout(function(){
                $('.usuarios .help-block').html('');
            },5000);
        },'json');
        
    });
    
   $('.usuarios').on('click','.usuario',function(){
        $('.usuarios .help-block').html('');
       var id_tarefa = $('.id-tarefa').attr('data-item');
       var id_usuario_sessao = $('.id-usuario-sessao').attr('data-item');
       var id_usuario = $(this).attr('data-item');
       var qtde = $('.usuario').length;
       if ( qtde > 1 )
       {
           var mensagem = 'Tem certeza que deseja sair desta tarefa?';
           if ( id_usuario !== id_usuario_sessao )
           {
               var quem = $('.usuario-' + id_usuario + ' .nome-usuario').html();
               mensagem = 'Tem certeza que deseja deletar  ' + quem + ' da tarefa';
           }

            var confirma = confirm(mensagem);
            if ( confirma )
            {
                var url = URI + 'tarefas/deleta_usuario/';
                var array = {};
                array['id_usuario'] = id_usuario;
                array['id_tarefa'] = id_tarefa;
                $.post(url,array,function(data){
                    $('.usuarios .help-block').html(data.erro.mensagem);
                    if ( data.erro.status )
                    {
                        $('.usuario-' + id_usuario).remove();
                    }
                    setTimeout(function(){
                        $('.usuarios .help-block').html('');
                    },2000);
                },'json');

            }
           
       }
       else
       {
           alert('Não é possivel deletar o ultimo usuario da tarefa.');
       }
           
   });
    
    $('.novo-usuario').on('click',function(){
        $('.espaco-novo-usuario').html('<center><img src="http://www.guiasjp.com/admin2_0/images/loader_azul.gif"></center>');
        var url = URI + 'usuario/set_select';
        $.getJSON(url,function(data){
            var item = '';
            var x;
            for ( x in data )
            {
                item += '<div class="add-usuario btn btn-success usuario-' + data[x].id + '" data-item="' + data[x].id + '">' + data[x].descricao + '</div>';
            }
            $('.espaco-novo-usuario').html(item);
            
        });
        
    });
    
    
    $('.fechar-tarefa').on('click',function(){
        var id = $(this).attr('data-id');
        var confirma = window.confirm('Tem certeza que deseja finalizar esta tarefa e todas as suas atividades?')
        if ( confirma == true )
        {
            url = URI + 'tarefas/fechar_tarefa/' + id;
            $.get(url,function(data){
                if ( data.erro.status )
                {
                    alert(data.erro.mensagem);
                    location.reload();
                }
                else
                {
                    alert(data.erro.mensagem);
                }
            },'json');
        }
        
    });
    
    $('.reabrir-tarefa').on('click',function(){
        var id = $(this).attr('data-id');
        var confirma = window.confirm('Tem certeza que deseja Reabrir esta tarefa?')
        if ( confirma == true )
        {
            url = URI + 'tarefas/reabrir_tarefa/' + id;
            $.get(url,function(data){
                if ( data.erro.status )
                {
                    alert(data.erro.mensagem);
                    location.reload();
                }
                else
                {
                    alert(data.erro.mensagem);
                }
            },'json');
        }
        
    });
    
    
    /**
     * Busca empresas
     */        
    $('#empresas').on({
        /**
         * Ao clicar em qualquer tecla, busca as empresas que tenham chaves ligadas a esta.
         * @param {type} e
         * @returns {void}
         */
        keyup: function(e){
            var item = $(this).val();
            if ( item.length > 0 )
            {
                    $('.auto-resposta').html('<center><img src="' + URI + 'images/loader_azul.gif" alt="carregando"></center>');
                        setTimeout(function(){
                            if ( item === $('#empresas').val() )
                            {
                                //item.trim();
                                if ( item.length > 1 ) 
                                {
                                    var url = URI + 'empresas/pesquisa_empresa/' + encodeURIComponent( item ) + '/json/';
                                    $.get(url,function(data){
                                        var  d = $('.auto-resposta').html();
                                        var a = helper.botao(data);
                                        $('.auto-resposta').html( a );

                                    },'json').fail(function(f){
                                        $('.auto-resposta').html('<div class="btn btn-alert"><a>Problemas na geração de resultado. Tente novamente! </a></div>');
                                    }); 
                                }
                                else
                                {
                                    $('.auto-resposta').html('<div class="btn btn-alert"><a>Continue Digitando para prosseguir!</a></div>');
                                }
                            }
                        }, 500);
            }
            else
            {
                $('.auto-resposta').html('');
            }
        }
    });
    
    $('.resposta-auto-complete').on('click','.fechar',function(){
        $('.resposta-auto-complete').css({
            position: 'relative',
            display: 'none',
        }).html('');
        $('.busca-empresa').html();
    });
    
    $('.auto-resposta').on('click','.empresa',function(){
        var titulo = $(this).attr("data-titulo");
        var id = $(this).attr("data-item");
        var item = '<div class="btn btn-success empresa_'+id+'" id="'+id+'">';
        item += titulo;
        item += '<input type="hidden" value="' + id + '" name="empresas[]">';
        item += '<button type="button" class="close deleta pull-right" data-id="' + id + '" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        item += '</div>';
        var existe = $('.respostas .empresa_' + id ).html();
        if ( existe == undefined )
        {
            var html = $('.respostas').html();
            $('.respostas').html( html + item );
            $(this).remove();
        }
        
    });
    $('.respostas').on('click','.close',function(){
        var id = $(this).attr('data-id');
        $( '#' + id ).remove();
    });
    
    $('#form-tarefa').on('submit',function(){
        var envia = tarefa.verifica_campos();
        return envia;
    });
    
    $('.add-atividades').on('click',function(){
        var tem = $('.espaco-atividade').html();
        if ( tem.length == 0 )
        {
            $('.erro-atividade').html('');
            var id = $(this).attr('data-item');
            tarefa.carrega_campos_atividade(id);
            setTimeout(function(){
                $('.espaco-atividade').html(tarefa.campos_atividade);
                $('.espaco-atividade #descricao').focus();
                $(".data_hora").setMask("9999-99-99 99:99");
            },500);
        }
        else
        {
            $('.erro-atividade').html('<p class="alert alert-danger">*** Insira uma atividade por vez.</p>');
        }
        
    });
    $('.espaco-atividade').on('click','.deleta-espaco',function(){
        $('.espaco-atividade').html('');
    }),
    $('.espaco-atividade').on('click','.salva-atividade',function(){
        $('.espaco-atividade .salva-atividade').html('Aguarde, salvando dados.');
        var campo = ['descricao', 'usuarios_designados','previsao_tempo','data_fim'];
        var itens = verificacao.pega_campos(campo,'.espaco-atividade');
        itens.id_tarefa = $(this).attr('data-id');
        //var descricao = $('.espaco-atividade #descricao').val();
        //var previsao_tempo = $('.espaco-atividade #previsao_tempo').val();
        
        if ( itens.descricao.length > 0 )
        {
            var dados = { 'descricao':itens.descricao, 'previsao_tempo':itens.previsao_tempo, 'id_tarefas':itens.id_tarefa,'usuarios':itens.usuarios_designados, 'data_fim':itens.data_fim };
            var url = URI + 'tarefas/adicionar_atividade/';
            $.post(url,dados,function(data){
                $('.erro-atividade').html('<p class="alert '+data.erro.class+'">'+data.erro.mensagem+'</p>');
                if ( ! data.erro.status )
                {
                    tarefa.set_campo_salvo(data);
                }
                
            },'json').fail(function(){
                
            });
        }
        else
        {
            $('.erro-atividade').html('<p class="alert alert-danger">** Preencha o campo descricao.</p>');
            $('.espaco-atividade .salva-atividade').html('<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>');
        }
    });
    $('.atividades').on('click','.deleta-atividade',function(){
        var id = $(this).attr('data-id');
        var ok = confirm('Tem certeza que deseja deletar esta atividade?');
        if ( ok == true )
        {
            url = URI + 'tarefas/deleta_atividade/' + id;
            $.get(url,function(data){
                if ( data.erro.status )
                {
                    alert(data.erro.mensagem);
                    $('.elemento-' + data.erro.id ).remove();
                }
                else
                {
                    alert(data.erro.mensagem);
                }
                tarefa.atualiza_qtde();
            },'json');
        }
        
    });
    $('.atividades').on('click','.fechar-atividade',function(){
        var id_atividade = $(this).attr('data-id-atividade');
        var ok = confirm('Tem certeza que deseja finalizar esta atividade?');
        if ( ok == true )
        {
            url = URI + 'tarefas/fechar_atividade/' + id_atividade;
            $.get(url,function(data){
                if ( data.erro.status )
                {
                    alert(data.erro.mensagem);
                    $('.atividades .elemento-' + data.erro.id ).removeClass('alert-info').addClass('alert-danger');
                    $('.atividades .elemento-' + data.erro.id + ' .deleta-atividade' ).remove();
                    $('.atividades .elemento-' + data.erro.id + ' .controles' ).html('');
                    
                }
                else
                {
                    alert(data.erro.mensagem);
                }
                tarefa.atualiza_qtde();
            },'json');
        }
        
    });
    $('.atividades').on('click','.adicionar-interacoes',function(){
        var id_atividade = $(this).attr('data-id-atividade');
        var id_tarefa = $(this).attr('data-id');
        var tem = $('.elemento-' + id_atividade + ' .espaco-interacoes').html();
        if ( tem.length == 0 )
        {
            $('.elemento-' + id_atividade + ' .erro-interacoes').html('');
            $('.elemento-' + id_atividade + ' .espaco-interacoes').html(tarefa.carrega_campos_interacoes(id_tarefa,id_atividade));
            $('.elemento-' + id_atividade + ' .espaco-interacoes #descricao').focus()
        }
        else
        {
            $('.elemento-' + id_atividade + ' .erro-interacoes').html('<p class="alert alert-danger">*** Insira uma atividade por vez.</p>');
    
        }
    });
    $('.atividades').on('click','.salva-interacao',function(e){
        $('.espaco-interacoes .salva-interacao').html('Aguarde, salvando dados.');
        var id_tarefa = $(this).attr('data-id');
        var id_atividade = $(this).attr('data-id-atividade');
        var descricao = $('.espaco-interacoes #descricao').val();
        if ( descricao.length > 0 )
        {
            var dados = { 'descricao':descricao, 'id_tarefas':id_tarefa, 'id_tarefas_atividades':id_atividade };
            var url = URI + 'tarefas/adicionar_interacoes/';
                console.log(url);    
            $.post(url,dados,function(data){
                $('.elemento-' + data.erro.id_tarefas_atividades + ' .erro-interacoes').html('<p class="alert '+data.erro.class+'">'+data.erro.mensagem+'</p>');
                if ( ! data.erro.status )
                {
                    tarefa.set_campo_interacoes_salvo(data.item);
                    $('.elemento-' + data.erro.id_tarefas_atividades + ' .espaco-interacoes').html('');
                    
                }
                
            },'json').fail(function(erro){
                console.log(erro);
            });
        }
        else
        {
            $('.elemento-'+ id_atividade +  ' .erro-interacoes').html('<p class="alert alert-danger">** Preencha o campo descricao.</p>');
            $('.elemento-' + id_atividade + '.espaco-interacoes .salva-interacao').html('<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>');
        }
    });
    $('.atividades').on('click','.deleta-espaco-interacoes',function(){
        $('.espaco-interacoes').html('');
    }),
    $('.atividades').on('click','.ver-interacoes',function(){
        var id = $(this).attr('data-id-atividade');
        $('.interacoes-' + id).removeClass('hide').addClass('show');
        $(this).html('<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>');
        $(this).addClass('ocultar-interacoes').removeClass('ver-interacoes');
    });
    $('.atividades').on('click','.ocultar-interacoes',function(){
        var id = $(this).attr('data-id-atividade');
        $('.interacoes-' + id).addClass('hide').removeClass('show');
        $(this).html('<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>');
        $(this).addClass('ver-interacoes').removeClass('ocultar-interacoes');
    });
    $('.atividades').on('click','.trabalhar-atividade',function(){
        tarefa.trabalhar($(this));
    });
    
    $('.atividades').on('click','.pausar-atividade',function(){
        tarefa.pausar($(this));
    });
    $('.trabalhando').on('click','.pausar-atividade',function(){
        tarefa.pausar($(this));
    });
    
    $('.alert').on('click','.editavel', function(){
            tarefa.editar( $(this) );
    });
    $('.alert').on('click','.salva-editavel',function(){
        tarefa.salva_edicao( $(this) );
    });
    $('.alert').on('click','.deleta-editavel',function(){
        tarefa.deleta_edicao( $(this) );
    });
});

/**
 * Classe que gerencia o cadastro e suas necessidades
 * @since 2014-07-15
 */
var tarefa = {
    item: [],
    tarefas_hoje: function(){
        url = URI + 'tarefas/set_qtde_hoje/';
        $.post(url,[],function(data){
            $('.qtde_tarefas_hoje').html(data);
        });
    },
    verificar_trabalhando: function(){
        url = URI + 'tarefas/verificar_trabalhando/';
        $.post(url,[],function(data){
            //console.log(data);
            if( data.erro.status == true )
            {
                var id_tarefas_atividades = data.item.id_tarefas_atividades;
                item = $('.elemento-' + id_tarefas_atividades);
                if ( item.length > 0 )
                {
                    $('.elemento-' + id_tarefas_atividades + ' .trabalhar-atividade').html('<span class="glyphicon glyphicon-pause" aria-hidden="true"></span>').addClass('pausar-atividade').removeClass('trabalhar-atividade').attr('data-id-tempo', data.item.id).attr('title','Pausar esta tarefa.');
                }
                var elemento = '<button class="tempo-atividade-' + data.item.id_tarefas_atividades + ' tempo-tarefas-' + data.item.id_tarefas + ' btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 pausar-atividade" data-id="' + data.item.id_tarefas + '" data-id-atividade="' + data.item.id_tarefas_atividades + '" data-id-tempo="' + data.item.id + '" title="trabalhando na atividade - ' + data.atividade.descricao + '"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span></button><br><br><a href="' + URI + 'tarefas/editar/' + data.atividade.id_tarefas_projeto + '/' + data.item.id_tarefas + '">Ir para a tarefa n°: ' + data.item.id_tarefas + ' - ' + data.atividade.descricao + ' </a>';
                $('.trabalhando').html(elemento);
                document.title = '@> ' + data.atividade.descricao + ' - Trabalhando na atividade ';
            }
        },'json');
        
    },
    trabalhar: function(item){
        var dados = [];
        tarefa.item = item;
        var id_tarefa = item.attr('data-id');
        var id_tarefas_atividades = item.attr('data-id-atividade');
        var dados = { 'id_tarefas':id_tarefa, 'id_tarefas_atividades':id_tarefas_atividades };
        url = URI + 'tarefas/trabalhar/';
        $.post(url,dados,function(data){
            if( data.erro.status == true )
            {
                tarefa.item.html('<span class="glyphicon glyphicon-pause" aria-hidden="true"></span>').addClass('pausar-atividade').removeClass('trabalhar-atividade').attr('data-id-tempo', data.item.id).attr('title','Pausar esta tarefa.');
                var elemento = '<button class="tempo-atividade-' + data.item.id_tarefas_atividades + ' tempo-tarefas-' + data.item.id_tarefas + ' btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 pausar-atividade" data-id="' + data.item.id_tarefas + '" data-id-atividade="' + data.item.id_tarefas_atividades + '" data-id-tempo="' + data.item.id + '" title="trabalhando na atividade - ' + data.atividade.descricao + '"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span></button><br><br><a href="' + URI + 'tarefas/editar/' + data.atividade.id_tarefas_projeto + '/' + data.item.id_tarefas + '">Ir para a tarefa n°: ' + data.item.id_tarefas + ' - ' + data.atividade.descricao + ' </a>';
                $('.trabalhando').html(elemento);
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades).removeClass('hide').addClass('show');
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades + ' .erro-interacoes').html(data.erro.mensagem);
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades + ' .interacoes').append('<li>' + data.interacao.descricao + ' - ' + data.interacao.data + ' - ' + data.interacao.usuario + '</li>');
                document.title = '@> ' + data.atividade.descricao + ' - Trabalhando na atividade ';
            }
            else
            {
                alert(data.erro.mensagem);
            }
        },'json');
        
        
    },
    pausar: function(item){
        var dados = [];
        tarefa.item = item;
        var id_tarefa = item.attr('data-id');
        var id_tempo = item.attr('data-id-tempo');
        var id_tarefas_atividades = item.attr('data-id-atividade');
        var dados = { 'id_tarefas':id_tarefa, 'id_tarefas_atividades':id_tarefas_atividades, 'id_tempo':id_tempo };
        url = URI + 'tarefas/pausar/';
        $.post(url,dados,function(data){
            if( data.erro.status == true )
            {
                $('.elemento-' + id_tarefas_atividades + ' .pausar-atividade').html('<span class="glyphicon glyphicon-play" aria-hidden="true"></span>').addClass('trabalhar-atividade').removeAttr('data-id-tempo').attr('title','Trabalhar nesta atividade.').removeClass('pausar-atividade');
                var elemento = '';
                $('.trabalhando').html(elemento);
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades).removeClass('hide').addClass('show');
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades + ' .erro-interacoes').html(data.erro.mensagem);
                $('.elemento-' + id_tarefas_atividades + ' .interacoes-' + id_tarefas_atividades + ' .interacoes').append('<li>' + data.interacao.descricao + ' - ' + data.interacao.data + ' - ' + data.interacao.usuario + '</li>');
                document.title = 'Admin - Pow Internet.';
            }
            else
            {
                alert(data.erro.mensagem);
            }
        },'json');
        
        
    },
    salva_edicao: function(item){
        var campo = item.attr('data-item');
        var valor = $('#' + campo).val();
        var data = { 'campo': campo, 'valor': valor, 'id': item.attr('data-id') };
        var url = URI + 'tarefas/edita_campo/';
        $.post(url,data,function(retorno){
            if ( retorno.erro.status == true )
            {
                alert(retorno.erro.mensagem);
                tarefa.valor_alterado(retorno.erro);
            }
            else
            {
                alert(retorno.erro.mensagem);
            }
        },'json');
    },
    deleta_edicao: function(item){
        var data = [];
        data.campo = item.attr('data-item');
        data.valor = $('#' + data.campo).val();
        tarefa.valor_alterado(data);
    },
    valor_alterado: function(data){
        $('.elemento-' + data.campo ).html('<span class="editavel elemento-'+data.campo+'" data-item="'+data.campo+'" data-valor="'+data.valor+'">'+data.valor+'</span>');
    },
    editar: function(item){
        var data = [];
        data.id = $('.id-tarefa').attr('data-item');
        data.item = item.attr('data-item');
        data.valor = item.attr('data-valor');
        var usuario = $('.id-usuario').attr('data-item');
        var usuario_sessao = $('.id-usuario-sessao').attr('data-item');
        if ( usuario == usuario_sessao )
        {
            tarefa.set_campo(data,item);
        }
        else
        {
            alert('Você não tem autorização para editar esta tarefa. Solicite ao administrador da tarefa.')
        }
    },
    set_campo: function(data, item){
        var tipo = '';
        switch( data.item )
        {
            case 'descricao':
                tipo = 'textarea';
                break;
            case 'titulo':
            case 'data_fim':
            case 'data_inicio':
            case 'previsao_horas':
                tipo = 'input';
                break;
        }
        if ( tipo != '' )
        {
            var campo = tarefa.get_campo(data.item, data.valor, tipo, data.id);
            item.html(campo).removeClass('editavel');
               
        }
        
    },
    get_campo: function(nome, valor, tipo, id){
        var retorno = '';
        retorno += '<div class="form-group '+nome+'">';
        retorno += '<div class="row">';
        retorno += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        retorno += '<button type="button" class="col-lg-1 col-sm-1 col-md-1 col-xs-1 close deleta-editavel pull-right" aria-label="Close" tiitle="deletar atividade" data-id="'+ id +'" data-item="'+nome+'"><span aria-hidden="true"><br>&times;</span></button>';
        retorno += '<button class="btn btn-info pull-right salva-editavel col-lg-1 col-sm-1 col-md-1 col-xs-1" title="Salvar Alteração" data-id="'+ id +'" data-item="'+nome+'"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span></button>';
        retorno += '</div>'
        retorno += '</div>'
        retorno += '<div class="row">';
        retorno += '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
        switch( tipo )
        {
            case 'input':
                retorno += '<input name="'+nome+'" type="text" class="form-control '+nome+'" id="'+nome+'" required value="'+valor+'">  ';
                retorno += '<p class="help-block '+nome+'"></p>';
                break;
            case 'textarea':
                retorno += '<textarea name="'+nome+'" class="form-control" id="'+nome+'" >'+valor+'</textarea>';
                retorno += '<p class="help-block '+nome+'"></p>';
                break;
        }
        retorno += '</div>'
        retorno += '</div>'
        retorno += '</div>';
        return retorno;
    },
    /**
     * Verifica os campos definidos, utilizando verifica_campo e retorna o valor boolean para tratamento do formulário ou os valores dos campos necessarios
     * @since 2014-07-01 - guiasjp
     * @param {void} completo define os campos a serem verificados em verifica_campos
     * @returns {Boolean|jQuery|object} se != false retorna a array com os valores
     */
    verifica_campos: function(){
        var verifica = true;
        verifica = verificacao.verifica_campo('titulo', {'empty':'O titulo é obrigatório.','qtde_minima':'O titulo deve ser válido.'}, verifica, 3,'#form-tarefa');
        verifica = verificacao.verifica_campo('data_inicio', {'empty':'O data previsão inicio é obrigatório.'}, verifica, null,'#form-tarefa');
        verifica = verificacao.verifica_campo('data_fim', {'empty':'O data previsão fim é obrigatório.'}, verifica, null,'#form-tarefa');
        //verifica = verificacao.verifica_campo('status', {'selected':'Selecione um Status Válido.'}, verifica, null,'#form-tarefa');
        var retorno;
        if ( verifica )
        {
            var array = ['titulo'];
            retorno = verificacao.pega_campos( array, '#form-tarefa' );
        }
        else
        {
            retorno = verifica;
        }
        $('.enviar').html('Enviar');
        return retorno;
    },
            /**
             * Carrega campo de atividade com base no id da tarefa
             * @param {int} id - id da tarefa
             * @returns {String} - com os campos formatados
             */
    carrega_campos_atividade: function( id ){
        //var dados = { 'id':id };
        var url = URI + 'tarefas/carrega_campos_atividade/' + id;
        $.get(url,function(e){
            
        }).done(function(data){
            tarefa.campos_atividade = data;
        }).fail(function(e,h){
            alert('não foi possivel carregar os campo, tente novamente.');
        });
        
    },
    campos_atividade: '',
    carrega_campos_interacoes: function( id, id_atividade ){
        var campos = '';
        campos += '<div class="row campos-interacao bg-danger">';
        campos += ' <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">';
        campos += '<h3 class="col-lg-11 col-sm-11 col-md-11 col-xs-11 pull-left">Nova interação:</h3>';
        campos += '     <button type="button" class="col-lg-1 col-sm-1 cooll-md-1 col-xs-1 close deleta-espaco-interacao pull-right" aria-label="Close" tiitle="deletar interacao" data-id="'+ id +'" data-id-atividade="'+ id_atividade +'"><span aria-hidden="true"><br>&times;</span></button>';
        campos += '</div>';
        campos += ' <div class="form-group descricao col-lg-6 col-sm-6 col-md-6 col-xs-6">';
        campos += '     <label for="descricao">Descrição</label>';
        campos += '     <textarea name="descricao" class="form-control" id="descricao" placeholder="Descrição"></textarea>';
        campos += '     <p class="help-block descricao"></p>';
        campos += ' </div>'
        campos += ' <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">';
        campos += '     <button class="btn btn-info pull-right salva-interacao col-lg-4 col-sm-4 col-md-4 col-xs-4" title="Salvar interacao" data-id="'+ id +'" data-id-atividade="'+ id_atividade +'"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span></button>'
        campos += ' </div>';
        campos += '</div>';
        return campos;
    },
            /**
             * Após salvar o campo, utiliza para criar a visualização do campo
             * @param {type} data - é o retorno da url de inserção - data.item, data.erro
             * @returns {undefined}
             */
    set_campo_salvo: function(data){
        var url = URI + 'tarefas/set_campo_salvo';
        $.post(url,data,function(data){
            tarefa.campo_salvo = data;
        }).fail(function(e,h){
            alert('nao foi possivel, tente novamente.');
        });
       
        setTimeout(function(){
            var ul_conteudo = $('ul.atividades').html();
            $('ul.atividades').html(tarefa.campo_salvo + ul_conteudo);
            tarefa.atualiza_qtde();
        },800);
    },
    campo_salvo: '',
    set_campo_interacoes_salvo: function(data, tipo) {
        var campo = '';
        campo += '<li class="">' + data.descricao + ' - ' + data.data + ' - ' + data.usuario + '</li>';
        if ( tipo == true )
        {
            return campo;
        }
        else
        {
            $('.elemento-' + data.id_tarefas_atividades + ' ul.interacoes').append(campo);
        }
    },
            /**
             * Atualiza a qtde de atividades na tarefa com base no UL desta visualização.
             * @returns {void} - qtde dentro do ul
             */
    atualiza_qtde: function(){
        var qtde = $('ul.atividades li.atividade').length;
        $('.qtde-atividades').html(qtde);
        $('.espaco-atividade').html('');
    },
    verifica_novas: function(){
        url = URI + 'tarefas/verificar_novas/';
        $.post(url,[],function(data){
            //console.log(data);
            if( data.erro.status == true )
            {
                var id_tarefas_atividades = data.item.id_tarefas_atividades;
                item = $('.elemento-' + id_tarefas_atividades);
                if ( item.length > 0 )
                {
                    $('.elemento-' + id_tarefas_atividades + ' .trabalhar-atividade').html('<span class="glyphicon glyphicon-pause" aria-hidden="true"></span>').addClass('pausar-atividade').removeClass('trabalhar-atividade').attr('data-id-tempo', data.item.id).attr('title','Pausar esta tarefa.');
                }
                var elemento = '<button class="tempo-atividade-' + data.item.id_tarefas_atividades + ' tempo-tarefas-' + data.item.id_tarefas + ' btn btn-default col-lg-12 col-sm-12 col-md-12 col-xs-12 pausar-atividade" data-id="' + data.item.id_tarefas + '" data-id-atividade="' + data.item.id_tarefas_atividades + '" data-id-tempo="' + data.item.id + '" title="trabalhando na atividade - ' + data.atividade.descricao + '"><span class="glyphicon glyphicon-pause" aria-hidden="true"></span></button><br><br><a href="' + URI + 'tarefas/editar/' + data.item.id_tarefas + '">Ir para a tarefa n°: ' + data.item.id_tarefas + ' - ' + data.atividade.descricao + ' </a>';
                $('.tarefas_novas').html(elemento);
                document.title = '@> ' + data.atividade.descricao + ' - Trabalhando na atividade ';
            }
        },'json');
    },
};