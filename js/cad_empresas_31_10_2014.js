/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$(function(){
    
    $('#empresa_cnpj').mask('99.999.999/9999-99');
    
    //CKEDITOR.replace( 'empresa_descricao' );
    
    $('.descricao').on({
        focus : function(){
            descricao.testa();
        },
        blur : function(){
            descricao.testa();
        },
        keypress : function(){
            descricao.testa();
        },
        
    });
    
    $('.cep').on({
        keypress : function(){
            var v = $(this).val().replace('_','');
            if (v.length == 9)
            {
                $.getJSON( URI + 'empresas/get_cep/' + v ).done( function(data){
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
                    }
                    $('.help-endereco').html( retorno );
                }).fail(function(){ 
                    $('.help-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
                });
            }
        },
    });
    $('.endereco').on({
        keypress : function(){
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
                $('.help-endereco').html( retorno );
                
            }).fail(function(){ 
                $('.id_logradouro').val(0);
                $('.help-endereco').html( '<p>Problemas para se comunicar com o servidor.</p>'  );  
            });
        },
    });
    $('.help-endereco').on('click', '.resposta', function(){
        var logradouro = $(this).attr('data-logradouro');
        var id = $(this).attr('data-id');
        var bairro = $(this).attr('data-bairro');
        var cidade = $(this).attr('data-cidade');
        var cep = $(this).attr('data-cep');
        $('.id_logradouro').val(id);
        $('.endereco').val(logradouro);
        $('.bairro').val(bairro);
        $('.cidade').val(cidade);
        $('.cep').val(cep);
        $('.help-endereco').html('');
        $('.numero').focus();
        
    });
    $('.enviar-mail').on({
        click: function(){
            var id = $('#id_empresa').val();
            var email = $('.contato_email').val();
            var email_empresa = $('.empresa_email').val();
            console.log(email_empresa);
            if ( email.length === 0 && email_empresa.length === 0 )
            {
                alert('O campo "Empresa e-mail" ou "Contato e-mail" devem estar preenchidos obrigatoriamente.');
                
            }
            else
            {
                //console.log(email, email_empresa);
                if ( email.length > 0 )
                {
                    var email_ = email;
                    console.log(email);
                }
                else
                {
                    console.log(email_empresa);
                    var email_ = email_empresa;
                }
                console.log(email_);
                $.getJSON( URI + 'empresas/envia_email/' + id + '/' + email_ + '/' ).done( function(data){
                    if ( data != undefined && data == 1 )
                    {
                        $('.resultado-email').html('E-mail enviado com sucesso').addClass('alert alert-success').removeClass('alert alert-danger');
                    }
                    else
                    {
                        $('.resultado-email').html('Problemas no envio de E-mails').addClass('alert alert-danger').removeClass('alert alert-success');
                    }
                }).fail(function(){
                    $('.resultado-email').html('Problemas no envio de E-mails, tente novamente').addClass('alert alert-danger').removeClass('alert alert-success');
                });
            }
            //console.log(id);
        }
    });
    
     //variaveis globais
     var id_empresa = $('#interacaoModal').attr('value');
     var url_contato = URI+'empresas/get_contato';
     var url_ocorrencia = URI+'empresas/get_ocorrencia';
     var url_interacao = URI+"empresas/get_formulario_interacao";
     var url_add_ocorrencia = URI+'empresas/add_ocorrencia';
     var url_add_interacao = URI+'empresas/add_interacao';
     
     //botão escolha um contato
     $('.escolha_contato_ocorrencia').on('click', function()
     {
        $('.seleciona_contato_agendamento').css('display', 'block');
        $('.btn_novo_contato').css('display','block');
        $('.novo_contato_agendamento').css('display', 'none');
        $('.btn_salvar_contato').text('Selecionar');
        $('.btn_novo_contato').text('Novo Contato');
        $('input[type=hidden][name=operacao]').val('0');
        
        $.post(url_contato, { 'id_empresa': id_empresa}, function(data)
        {
            if(data != '')
            {
                $('#contatos_selecionaveis').html(data);
            }
            else
            {
                monta_select($('#contatos_selecionaveis'), 'Contatos');
            }
            $('.btn_salvar_contato').text('Selecionar');
            $('#contatos_selecionaveis').css('display','block');
        });
        
     });
     
     //validar email
     $(document).on('blur', '.email_contato', function()
     {
         var e = ( $('#contatoModal .email_contato').val() != '') ? $('#contatoModal .email_contato').val() : $('#interacaoModal .email_contato').val() ;
         valida.email(encodeURI(e));
         setTimeout(function(){ 
             console.log(valida.retorno)
             if(!valida.retorno)
             {
                $('.email_contato').addClass('alert-danger');
             }
             else
             {
                 $('.email_contato').removeClass('alert-danger');
             }
             
         },500);
      });
      
      //validar telefone
     $(document).on('blur', '.telefone_contato', function()
     {
         var t = ( $('#contatoModal .telefone_contato').val() != '') ? $('#contatoModal .telefone_contato') : $('#interacaoModal .telefone_contato');
         valida.telefone(t);
         valida.retorno;
      });
      
     //fechar modais
     $(document).on('hide.bs.modal', function ()
     {
         $('.nome_contato').val('');
         $('.email_contato').val('');
         $('.funcao_contato').val('');
         $('.telefone_contato').val('');
         $('.email_contato').val('');
         $('.email_contato').removeClass('alert-danger');
         $('.obs_contato').val('');
         
         $.post(url_contato, { 'id_empresa': id_empresa}, function(data)
         {
                 if(data != '')
                 {
                     $('#contatos_interacao').html(data);
                 }
                 else
                 {
                     monta_select($('#contatos_interacao'), 'Contatos');
                 }
          });
         
     });
     
     //salva o contato no banco de dados Modal contato
     $('#contatoModal').on('click', '.btn_salvar_contato' , function ()
     {
         //selecionar contato
         var nome_contato = $('#contatoModal #contato option:selected').text();
         var id_contato = $('#contatoModal #contato option:selected').val();
         
         
         var form_novo_contato = $('#contatoModal .novo_contato_agendamento');
         var form_seleciona_contato = $('#contatoModal .seleciona_contato_agendamento');
         var operacao = $('#contatoModal input[type=hidden][name=operacao]').val();
        
         //salvar contato
         var nome = $('#contatoModal #nome_contato').val();
         var funcao = $('#contatoModal #funcao_contato').val();
         var telefone = $('#contatoModal #telefone_contato').val();
         var email = $('#contatoModal #email_contato').val();
         var mail = $('#contatoModal #email_contato');
         var obs = $('#contatoModal #obs_contato').val();
         
         if(operacao == '0')
         {
             console.log(id_contato);
             if(id_contato != undefined && id_contato != '')
             {
                $('#contatoModal').modal('hide');
                $('.escolha_contato_ocorrencia').text(nome_contato);
                $('#nome_contato_agendamento').val(id_contato);
             }
             else
             {
                 alert('Selecione um contato ou cadastre um novo');
             }
         }
         else if( operacao == '1')
         {
             if(nome != '' && !(mail.hasClass('alert-danger')))
             {
                 var url = URI+'empresas/add_contato_empresa';
                 $.post(url, {'nome': nome, 'funcao': funcao, 'obs': obs, 'telefone': telefone, 'email': email, 'id_empresa': id_empresa}, function(data)
                 {
                    if(data != '0')
                    {
                        alert('Contato inserido com sucesso.');
                        $('#contatoModal #nome_contato').val('');
                        $('#contatoModal #funcao_contato').val('');
                        $('#contatoModal #telefone_contato').val('');
                        $('#contatoModal #email_contato').val('');
                        $('#contatoModal #obs_contato').val('');
                        if(! confirm('Deseja cadastrar novo contato?'))
                        {
                           form_novo_contato.fadeOut( "slow", function()
                           {
                               $('#contatoModal input[type=hidden][name=operacao]').val('0');
                               $('#contatoModal .btn_novo_contato').text('Novo Contato');
                               form_seleciona_contato.css('display','block');
                               var url_contato = URI+'empresas/get_contato'
                               $.post(url_contato, { 'id_empresa': id_empresa}, function(data)
                               {
                                    if(data != '')
                                    {
                                        $('#contatos_selecionaveis').css('display','block');
                                        $('#contatos_selecionaveis').html(data);
                                        $('.btn_salvar_contato').text('Selecionar');
                                    }
                                    else
                                    {
                                        monta_select($('#contatos_selecionaveis'), 'Contatos');
                                    }
                                });
                            });
                         }
                      }
                      else
                      {
                          alert('Não foi possivel realizar inserção de contato');
                      }
                  });
               }
               else
               {
                  alert('O campo Nome é obrigatorio e o Email deve ser válido.');
               }
          }
     });
     
     
    //transição de modais entre os contatos existentes e adicionar novo contato nomodal de adicionar novo contato
    $('#contatoModal').on('click', '.btn_novo_contato', function()
    {
        var novo = $('.novo_contato_agendamento');
        var selecionaveis = $('#contatos_selecionaveis');
        
        if(novo.css('display') == 'none')
        {
            selecionaveis.fadeOut( "slow", function()
            {
                  novo.css('display', 'block');
            });
            $('#contatoModal .btn_novo_contato').text('Voltar');
            $('#contatoModal .btn_salvar_contato').text('Salvar');
            $('#contatoModal input[type=hidden][name=operacao]').val('1');
        }
        else
        { 
            $.post( url_contato, { 'id_empresa' : id_empresa }, function(data)
            {
                if(data != '')
                {
                    $('#contatos_selecionaveis').html(data);
                }
                else
                {
                     monta_select($('#contatos_selecionaveis'),'Contatos');
                }
            });
            
            novo.fadeOut( "slow", function()
            {
                  selecionaveis.css('display', 'block');
            });
            $('#contatoModal .btn_novo_contato').text('Novo Contato');
            $('#contatoModal .btn_salvar_contato').text('Selecionar');
            $('#contatoModal input[type=hidden][name=operacao]').val('0');
         }
    });
    
    
    //salva o contato no banco de dados Modal interacao
    $('#interacaoModal').on('click', '.btn_salvar_contato', function()
    {
        var operacao = $('#interacaoModal input[type=hidden][name=operacao]').val();
        
        //salvar contato
        var nome = $('#interacaoModal #nome_contato').val();
        var funcao = $('#interacaoModal #funcao_contato').val();
        var telefone = $('#interacaoModal #telefone_contato').val();
        var email = $('#interacaoModal #email_contato').val();
        var mail = $('#interacaoModal #email_contato');
        var obs = $('#interacaoModal #obs_contato').val();
        
        if(operacao == '1')
        {
             if(nome != '' && !(mail.hasClass('alert-danger')))
             {
                var url = URI+'empresas/add_contato_empresa';
                $.post(url, {'nome': nome, 'funcao': funcao, 'obs': obs, 'telefone': telefone, 'email': email, 'id_empresa': id_empresa}, function(data)
                {
                    if(data != '0')
                    {
                        alert('Contato inserido com sucesso.');
                        $('#interacaoModal #nome_contato').val('');
                        $('#interacaoModal #funcao_contato').val('');
                        $('#interacaoModal #telefone_contato').val('');
                        $('#interacaoModal #email_contato').val('');
                        $('#interacaoModal #obs_contato').val('');
                        if(! confirm('Deseja cadastrar novo contato?'))
                        {
                           $('#interacaoModal').modal('hide');
                           var url_contato = URI+'empresas/get_contato'
                           $.post(url_contato, { 'id_empresa': id_empresa}, function(data)
                           {
                                    if(data != '')
                                    {
                                        $('#contatos_interacao').html(data);
                                    }
                                    else
                                    {
                                        monta_select( $('#contatos_interacao'), 'Contatos');
                                    }
                             });
                        }
                    }
                    else
                    {
                        alert('Não foi possivel realizar inserção de contato');
                    }
                });
            }
            else
            {
                alert('campo Nome é obrigatorio e o Email deve ser válido.');
            }
        }
    });
     
    //monta select de contato e status no modal de interação ao clicar no botão de adicionar    
    $('.empresa_ocorrencias').on('click', '.abrir-ocorrencias', function()
    {
        if($('.ocorrencias').css('display') == 'none')
        {
            $(this).removeClass('glyphicon-plus');
            $(this).addClass('glyphicon-minus');
        }
        if($('.ocorrencias').css('display') == 'block')
        {
            $(this).removeClass('glyphicon-minus');
            $(this).addClass('glyphicon-plus');
        }
        $('.adicionar_interacao').text('Adicionar');
        var id_ocorrencia = $(this).attr('data-id-ocorrencia');
        
        $.post(url_ocorrencia, { 'id_ocorrencia': id_ocorrencia, 'id_empresa': id_empresa}, function(data)
        {
                $('.alterar'+id_ocorrencia+' div:first-child').empty().append( data );
                if($(".alterar"+id_ocorrencia).css('display') === 'none')
                {
                    $(this).attr('class', 'glyphicon glyphicon-minus pull-right abir-ocorrencias');
                    $(".alterar"+id_ocorrencia).css('display', 'block');
                }
                else
                {
                    $(this).attr('class', 'glyphicon glyphicon-plus pull-right abir-ocorrencias');
                    $(".alterar"+id_ocorrencia).css('display', 'none');
                }
         });
        
        $.post(url_contato, { 'id_empresa': id_empresa}, function(data)
        {
            if(data != '')
            {
                $('#contatos_selecionaveis').html(data);
            }
            else
            {
                monta_select( $('#contatos_selecionaveis'), 'Contatos');
            }
        });
        
     });   
     
    $(".ocorrencias").on('click', '.adicionar_interacao', function()
    {
            var id_ocorrencia = $(this).attr('data-id-ocorrencia');
            var id_form = $('#formulario_interacao'+id_ocorrencia);
            var select = $('#contato_empresa'+id_ocorrencia);
            var selectStatus = $('#status_ocorrencia'+id_ocorrencia);
            $('.data_hora').mask('9999-99-99 99:99:99');
            
            if(id_form.css('display') == 'none')
            {
                id_form.css('display', 'block');
                $(this).text('Fechar');
                select.val($(this).attr('data-usuario'));
                selectStatus.val($(this).attr('data-status'));
                
                var url_usuarios = URI+'empresas/get_usuario';
                $.post(url_usuarios, { 'multi' : '0'}, function(data){

                    setTimeout(function(){
                        $('#user_retorno').html(data);
                    },300);

                });
            }
            else
            {
                id_form.css('display', 'none');
                $(this).text('Adicionar');
            }
      });
    

    //botão dentro do form de interação
    $('.empresa_ocorrencias').on('click', '.nome_contato_ocorrencia', function()
    {
       $('.seleciona_contato_agendamento').css('display', 'none');
       $('.novo_contato_agendamento').css('display', 'block');
       $('.btn_novo_contato').css('display','none');
       $('.btn_salvar_contato').text('Salvar');
       $('input[type=hidden][name=operacao]').val('1');
    });
    
    
    //monta select com interações das ocorrencias
    $('#setor_ocorrencia').on('change', function(){
        var setor = $( this ).val();
        if(setor != '')
        {
            $.post(url_interacao, { 'id_empresa': id_empresa}, function(data){
                $('#formulario_interacao_setor').empty().append( data );
            });
            
            var url_setor = URI+'empresas/get_usuario_por_setor';
            $.post(url_setor, { 'setor': setor, 'multi' : '0'}, function(data){
                
                setTimeout(function(){
                    $('#user_retorno').html(data);
                },300);
                
            });
        }
        else
        {
            $('#formulario_interacao_setor').html('');
        }
    });
    
    //adicionar ocorrencia
    $('#formulario_interacao_setor').on('submit', '#formulario_cadastro' , function()
    {
        var contato = $('#nome_contato_agendamento').val();
        var contato_form = $('#contato option:selected').val();
        var setor = $('#setor_ocorrencia').val();
        var assunto = $('#assunto_ocorrencia').val();
        if(contato == '')
        {
            $('#id_contato_hidden').val(contato_form);
        }
        else
        {
            $('#id_contato_hidden').val(contato);
        }
        $('#id_setor_hidden').val(setor);
        $('#assunto_hidden').val(assunto);
        
        $(this).attr('action', url_add_ocorrencia);
        $(this).attr('method', 'post'); 
    });
    
    //adicionar interação na ocorrencia
    $('.empresa_ocorrencias').on('submit', '#formulario_cadastro' , function()
    {
        var id_contato = $("#contato option:selected").val();
                
        $('#id_contato_hidden').val(id_contato);
        $('#id_empresa_original').val(id_empresa);

        $(this).attr('action', url_add_interacao);
        $(this).attr('method', 'post'); 
    });
    
});


var valida = {
    retorno : '',
    
    email : function(email)
    {
        $.getJSON(URI+'empresas/validar_email', { 'email' : email }, function(data)
        {
            valida.retorno = data;
        });
    },
    
    telefone : function(telefone)
    {
        switch(telefone.val().length)
        {
            case 11:
                 valida.retorno = telefone.mask("99-9999-9999")
                 break;
            case 12:
                 valida.retorno = telefone.mask("999-9999-9999");
                 break;
            case 13:
                 valida.retorno = telefone.mask("999-99999-9999");
                 break;
             default:
                 valida.retorno = telefone.val().length;
        }
    }
}

var monta = {
    
    lista : function( item ){
        retorno = '<li class="resposta btn btn-info" data-logradouro="'+item.logradouro+'" data-id="'+item.id+'" data-bairro="'+item.bairro+'" data-cidade="'+item.cidade+'" data-cep="'+item.cepi+'" >';
        retorno += item.logradouro + ' - n: ' + item.n_inicio + ' até ' + item.n_final + ' - baiiro: ' + item.bairro + ' - cidade: ' + item.cidade + ' - CEP: ' + item.cepi;
        retorno += '</li>';
        return retorno;
    },
    
};


var descricao = {
    testa : function(){
        var d = $('.descricao').val().length;
            $('.qtde-caracteres').html(d);
            if ( d > 60 )
            {
                $('.qtde-caracteres').removeClass('text-success').addClass('text-danger');
                $('.mensagem-erro').html('Qtde de caracteres maior que o suportado, favor resuma o texto para aparecer inteiro.').removeClass('text-success').addClass('text-danger');
            }
            else
            {
                $('.qtde-caracteres').addClass('text-success').removeClass('text-danger');
                $('.mensagem-erro').html('Qtde válida').addClass('text-success').removeClass('text-danger');
            }
    }
};

function monta_select(seletor, titulo)
{
    var retorno = '<label>'+titulo+'</label>';
    retorno    += '<select class="form-control">';
    retorno    += '     <option value="">Selecione...</option>';
    retorno    += '</select>';
    $(seletor).html(retorno);
}