
var retorno_image = {
    acao : function(data){
        $('#modal-base').modal('hide');
        var id = $('.id').val();
        var url = 'http://pow.com.br/admin/trazer_loguinhos.php?acesso_restrito=1&id_empresa=' + id + '&tipo=' + data.classe;
        $.get(url, function(data){
            console.log(data);
        });
        setTimeout(function(){
            var caminho = 'http://www.pow.com.br/powsites/'+ id + '/' + data.arquivo;
            var html = '<center><img src="' + caminho + '" class="image " data-item="' + data.classe + '" ></center>';
            html += '<button type="button" class="btn btn-danger form-control deleta-image" data-item="' + data.classe + '">Deletar</button>';
            $('.form-group.' + data.classe + ' .espaco-image').html(html);
        },1000);
    },
};
var classe_default = 'form';
$(function(){
    contador.por_classe('#empresa_descricao', '.contador_descricao');
    
    $('.estatistica-dia').on('click', function () {
        $('#modal-base .modal-body').html('');
        var item = $(this).attr('data-item');
        $('#modal-base').modal('show');
        var url_lista = URL_HTTP + 'estatisticas/listar/empresas/' + item;
        var html = '<iframe src="' + url_lista + '" style="width:100%; margin: 10px auto; min-height:600px; border:none;" border="0"></iframe>';
        $('#modal-base .modal-body').html(html);
    });
    $('.estatistica-local').on('click', function () {
        $('#modal-base .modal-body').html('');
        var item = $(this).attr('data-item');
        $('#modal-base').modal('show');
        var url_lista = URL_HTTP + 'estatisticas/consolidado/empresas/' + item;
        var html = '<iframe src="' + url_lista + '" style="width:100%; margin: 10px auto; min-height:600px; border:none;" border="0"></iframe>';
        $('#modal-base .modal-body').html(html);
    })
    
    
    $('#id_logradouro').on('change', 'select', function(){
        var campo = 'id_logradouro';
        var valor = $(this).val();
        var sequencia = $(this).attr('data-sequencia');
        autosave.salva(campo, valor, sequencia, 'empresas');
        $(this).focus();
    });
    
    
    var erro = {
        ver_senha: 0,
    };
    
    $(document).on('click','.image',function(){
        var classe = $(this).attr('data-item');
        var tabela = 'empresas';
        var titulo = $(this).attr('data-titulo');
        var id = $('.id').val();
        $('#modal-base').modal('show');
        $('#modal-base .modal-body').html('<iframe style="width:100%; height:100%;" scrolling="yes" frameborder="0" src="' + URI + 'anexos/upload_via_modal/' + tabela + '/' + classe + '/' + id + '/campo" style="color:#FFFFFF;"></iframe>');
        $('#modal-base .modal-title').html('Administraçao de Imagens - ' + titulo + '.');
    });
    
    $(document).on('click','.deleta-image',function(){
        var editavel = $('.editavel').val();
        if ( editavel == 1 )
        {
            var campo = $(this).attr('data-item');
            var valor = '';
            var sequencia = 0;
            var titulo = $('.form-group.' + campo + ' label').html();
            var conf = confirm('Confirma a exclusão do item ' + titulo );
            if ( conf )
            {
                autosave.salva(campo, valor, sequencia, 'empresas');
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
        }
    });
    
    $('.banners').on('click',function(){
        var item = $(this).attr('data-item');
        var url = URI + 'publicidade_campanhas/editar/' + item + '/inc';
    });
    
    
    
    
    $('#cep').on({
        keyup : function(){
            var v = $(this).val().replace('_','');
            v = v.replace('-','');
            console.log(v);
            if (v.length == 8)
            {
                $.getJSON( URI + 'empresas/get_cep/' + v ).done( function(data){
                    if ( data.length > 1 )
                    {
                        retorno = '<ul>';
                        for ( var i = 0; i < data.length; i++)
                        {
                            retorno += monta.lista(data[i]);
                        }
                        retorno += '</ul>';
                    }
                    else if( data.length == 1 )
                    {
                        console.log(data[0]);
                        $('#id_logradouro').val(data[0].id);
                        $('#logradouro').val(data[0].logradouro);
                        $('#bairro').val(data[0].bairro);
                        $('#cidade').val(data[0].cidade);
                        $('#estado').val(data[0].estado);
                        $('#empresa_numero').focus();
                        retorno = '<p>Endereço atualizado automaticamente</p>';
                        var confirma_logradouro = confirm("confirma o salvamento do Logradouro: " + data[0].logradouro);
                        if ( confirma_logradouro )
                        {
                            var campo = 'id_logradouro';
                            var valor = data[0].id;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_endereco';
                            var valor = data[0].logradouro;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_bairro';
                            var valor = data[0].bairro;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_cep';
                            var valor = $(this).val();
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                        }
                        setTimeout(function(){ $('.resposta-endereco').html(''); },5000);
                    }
                    else
                    {
                        retorno = '<a href="' + URL_HTTP + 'logradouros/adicionar" class="btn btn-warning" target="_blank">Adicione um novo logradouro</a>';
                    }
                    $('.resposta-endereco').html( retorno );
                }).fail(function(){ 
                    $('.resposta-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
                });
            }
        },
    });
    $('.resposta-endereco').on('click','.resposta',function(){
        var cidade = $(this).attr('data-cidade');
        var cep = $(this).attr('data-cep');
        var bairro = $(this).attr('data-bairro');
        var logradouro = $(this).attr('data-logradouro');
        var id = $(this).attr('data-id');
        
        $('#id_logradouro').val(id);
        $('#logradouro').val(logradouro);
        $('#bairro').val(bairro);
        $('#cidade').val(cidade);
        $('#empresa_numero').focus();
        retorno = '<p>Endereço atualizado automaticamente</p>';
        var confirma_logradouro = confirm("confirma o salvamento do Logradouro: " + logradouro);
                        if ( confirma_logradouro )
                        {
                            var campo = 'id_logradouro';
                            var valor = id;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_endereco';
                            var valor = logradouro;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_bairro';
                            var valor = bairro;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                            var campo = 'empresa_cep';
                            var valor = cep;
                            var sequencia = 19;
                            autosave.salva(campo, valor, sequencia, 'empresas');
                        }
                        setTimeout(function(){ $('.resposta-endereco').html(''); },5000);
        
        
    });
    $('.endereco').on({
        change : function(){
            var v = $(this).val().replace(' ','_');
            
            $.getJSON( URI + 'empresas/get_endereco/' + v ).done( function(data){
                if ( data.length > 0 )
                {
                    retorno = '<ul>';
                    for ( var i = 0; i < data.length; i++)
                    {
                        retorno += monta.lista(data[i]);
                    }
                    retorno += '</ul>';
                }
                else
                {
                    retorno = '<p class="alert">Sem registro para este endereço, preencha manualmente<p>';
                    $('.id_logradouro').val(0);
                }
                $('.resposta-endereco').html( retorno );
                
            }).fail(function(){ 
                $('.id_logradouro').val(0);
                $('.resposta-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
            });
        },
    });
    
    
    
    $('.categorias').on('change',function(){
        var conteudo_anterior = $('.select_subcategorias').html();
        $('.select_subcategorias').html('carregando...');
        var categoria = $(this).val();
        var url = URI + 'subcategorias/get_select/' + categoria + '/json';
        
        $.post(url,function(data){
            if ( data.status )
            {
                $('.select_subcategorias').html(data.valores);
            }
            else
            {
                alert('Problemas na captação, tente selecionar outra categoria.');
                $('.select_subcategorias').html(conteudo_anterior);
            }
        },'json');
    });
    $('.ver-senha').on('click',function(){
        if ( erro.ver_senha < 2 )
        {
            var confirma = prompt('Senha Verbal "escrita"? ');
            if ( confirma == 'ribossomo' )
            {
                alert('A senha é: ' + $('#senha').val() );
                return false;
            }
            else
            {
                erro.ver_senha++;
                alert('Errado, seu acesso será bloqueado ao sistema se tentar mais de 3 vezes. Você tentou: ' + erro.ver_senha);
                return false;

            }
        }
    });
    $('#empresa_descricao').on({
        keyup: function(){
            contador.por_classe('#empresa_descricao', '.contador_descricao');
            
        },
    });
    $('.contavel').on({
        keyup: function(){
            var classe = $(this).attr('name');
            contador.por_classe('#' + classe, '.contador_' + classe);
        }
    });
    $('.editar_autorizador').on({
        click: function(){
            $('.autorizador').removeAttr('disabled');
            $('#autorizador_cpf').html('').focus();
            $(this).removeClass('btn-primary').addClass('btn-success').html('Salvar Autorizador').removeClass('editar_autorizador').addClass('salvar_autorizador');
        },
    });
    $('#contatos').on('click','.salvar_autorizador',function(){
        console.log('salvar');
    });
    $('#autorizador_cpf').on('blur',function(){
        var dados = {};
        dados.cpf = $(this).val();
        var url = URI + 'autorizadores/get_autorizador_por_cpf';
        $.post(url,dados,function(data){
            if ( data )
            {
                var qtde = data.lenght;
                if ( qtde > 1 )
                {
                    var retorno = '';
                    data.each(function(k,v){
                        retorno += '<div class="btn btn-info autorizadores-clicavel autorizador-' + v.id + '" data-id="' + v.id + '" data-nome="' + v.nome + '" data-cpf="' + v.cpf + '" data-nascimento="' + v.nascimento + '">' + v.nome + '</div>';
                    });
                    retorno += '<br><p class="alert alert-primary">Clique em algum dos usuários acima para selecionar</p>';
                    $('.autorizador-message').html(retorno);
                }
                else
                {
                    $('#id_autorizador').val(data[0].id);
                    var campo = 'id_autorizador';
                    var valor = data[0].id;
                    var sequencia = 28;
                    autosave.salva(campo, valor, sequencia, 'empresas');
                    $('#autorizador_nome').val(data[0].nome).attr('disabled','disabled');
                    $('#autorizador_nascimento').val(data[0].nascimento).attr('disabled','disabled');
                    $('#autorizador_cpf').attr('disabled','disabled');
                    $('.autorizador-message').html('<div class="alert alert-success">Autorizador cadastrado e salvo com sucesso.</div>');
                    $('.salvar_autorizador').html('Editar autorizador').addClass('editar_autorizador').addClass('btn-primary').removeClass('btn-success')
                    $('#autorizador_cargo').focus();
                    setTimeout(function(){
                        $('.autorizador-message').html('');
                    },5000);
                }
            }
            else
            {
                $('.autorizador-message').html('<div class="alert alert-danger">Nenhum Autorizador encontrado para este CPF, continue o cadastro.</div>');
                $('#id_autorizador').val('');
                setTimeout(function(){
                    $('.autorizador-message').html('');
                },5000);
                
            }
        },'json');
    });
    $('.autorizador-message').on('click','.autorizadores-clicavel',function(){
        $('#id_autorizador').val(data[0].id);
        var campo = 'id_autorizador';
        var valor = data[0].id;
        var sequencia = 28;
        autosave.salva(campo, valor, sequencia, 'empresas');
        $('#autorizador_nome').val(data[0].nome).attr('disabled','disabled');
        $('#autorizador_nascimento').val(data[0].nascimento).attr('disabled','disabled');
        $('#autorizador_cpf').attr('disabled','disabled');
        
        $('.autorizador-message').html('<div class="alert alert-success">Autorizador salvo com sucesso.</div>');
        $('.salvar_autorizador').html('Editar autorizador').addClass('editar_autorizador').addClass('btn-primary').removeClass('btn-success')
        $('#autorizador_cargo').focus();
        setTimeout(function(){
            $('.autorizador-message').html('');
        },5000);
    });
    
    
    $('#autorizador_nascimento').on('blur',function(){
        var dados = {};
        dados.nome = $('#autorizador_nome').val();
        dados.nascimento = $('#autorizador_nascimento').val();
        dados.cpf = $('#autorizador_cpf').val();
        if ( dados.nome != '' && dados.nascimento != '' && dados.cpf != '' )
        {
            var confirma = confirm('Confirma o Cadastro de ' + dados.nome + ' como autorizador?');
            if ( confirma )
            {
                var url = URI + 'autorizadores/salva_autorizador';
                $.post(url,dados,function(data){
                    console.log(data);
                    if ( data )
                    {
                        $('#id_autorizador').val(data);
                        var campo = 'id_autorizador';
                        var valor = data;
                        var sequencia = 28;
                        autosave.salva(campo, valor, sequencia, 'empresas');
                        $('#autorizador_nome').attr('disabled','disabled');
                        $('#autorizador_nascimento').attr('disabled','disabled');
                        $('#autorizador_cpf').attr('disabled','disabled');
                        $('.autorizador-message').html('<div class="alert alert-success">Autorizador salvo com sucesso.</div>');
                        $('.salvar_autorizador').html('Editar autorizador').addClass('editar_autorizador').addClass('btn-primary').removeClass('btn-success')
                        $('#autorizador_cargo').focus();
                        setTimeout(function(){
                            $('.autorizador-message').html('');
                        },5000);
                    }
                    else
                    {
                        $('.autorizador-message').html('<div class="alert alert-danger">Problemas para salvar autorizador, tente novamente.</div>');
                        $('#autorizador_nascimento').focus();
                        setTimeout(function(){
                            $('.autorizador-message').html('');
                        },5000);
                    }
                },'json');
            }
            else
            {
                $('.autorizador-message').html('<div class="alert alert-danger">Preencha corretamente os dados do autorizador e confirme para prosseguir.</div>');
                $('#autorizador_nome').focus();
                setTimeout(function(){
                    $('.autorizador-message').html('');
                },5000);
            }
        }
        else
        {
            $('.autorizador-message').html('<div class="alert alert-danger">Preencha corretamente os dados do autorizador.</div>');
            $('#autorizador_nome').focus();
            setTimeout(function(){
                $('.autorizador-message').html('');
            },5000);
        }
    });
});
    