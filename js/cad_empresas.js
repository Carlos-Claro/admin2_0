$(function(){
    
    $('#datetimepickerInicioOc').datetimepicker();
    $('#datetimepickerFimOc').datetimepicker();
    
    $('#datetimepickerInicioIn').datetimepicker();
    $('#datetimepickerFimIn').datetimepicker();
    
    $('#empresa_cnpj').setMask('99.999.999/9999-99');
    //$('#data-retorno-inicio-oc').mask('99/99/9999 99:99');
    //$('#data-retorno-fim-oc').mask('99/99/9999 99:99');
    $('#data-retorno-inicio-in').setMask('99/99/9999 99:99');
    $('#data-retorno-fim-in').setMask('99/99/9999 99:99');
    
    
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
    var id_empresa = $('#id_empresa').val();
    var url_add_contato = URI+'empresas/add_contato';
    var url_edt_contato = URI+'empresas/edt_contato';
    var url_add_ocorrencia = URI+'empresas/add_ocorrencia';
    var url_add_interacao = URI+'empresas/add_interacao';
    var url_add_opiniao = URI+'empresas/add_opiniao';
    
    $('.error-oc').hide();
    $('.dt-cl').hide();
    //$('.btn-interacao').parent().parent().hide();
            
    selects.setor('oc');
    selects.status('oc');
    selects.assunto('oc');
    selects.contatos_empresa(id_empresa, 'oc');
    selects.usuario_setor(-1, 'oc');
    
    $(document).on('change', '.setores-io', function (){
       
        var setor = $(this).val();
        
        var sufix = $(this).parent().attr('data-tipo');
        
        if(setor)
        {
            selects.usuario_setor(setor, sufix);
        }
        
    });
    $(document).on('change', '.contato-empresa-oc select',function(){
       
        var id = $(this).val();
        var texto = $(this).find('option').filter(':selected').text(); 
        if(id !== '')
        {
            $('#id-contato-oc').attr('data-item', id);
            $('#id-contato-oc').removeClass('btn-default');
            $('#id-contato-oc').addClass('btn-success');
            $('#id-contato-oc').text('');
            $('#id-contato-oc').text(texto);
            //$('#modal-oc').modal('hide');
        }
        
    });
    
    $('#telefone_contato').on({
        
        blur : function(){
            
            var tamanho = $(this).val().length;
            
            switch(tamanho)
            {
                case 11:
                     $(this).mask('(999)9999-9999');
                    break;
                case 12:
                    $(this).mask('(999)99999-9999');
                    break;
                default:
                    $(this).unmask();
                    break;
            }
        },
        
        focus : function(){
            
            $(this).unmask();
            
        }
        
    });
    
    /*
    $('#telefone_contato_oc').on('blur', function(){
        
        if($(this).val().length == 11)
        {
            $(this).unmask();
            $(this).mask('(999)9999-9999');
        }
        else if($(this).val().length == 12)
        {
            $(this).unmask();
            $(this).mask('(999)99999-9999');
        }
        else
        {
            $(this).unmask();
        }

    });
    
    $('#telefone_contato_oc').on('focus', function(){
            
        $(this).unmask();
        
    });*/
    
    $('#plus-dt-cl').on('click', function(){
    
        if($(this).hasClass('glyphicon-plus'))
        {
            $(this).removeClass('glyphicon-plus');
            $(this).addClass('glyphicon-minus');
            $('.dt-cl').show();
        }
        else
        {
            $(this).removeClass('glyphicon-minus');
            $(this).addClass('glyphicon-plus');
            $('.dt-cl').hide();
        }
        
    });
    
    $('#plus-oc').on('click', function(){
    
        if($(this).hasClass('glyphicon-plus'))
        {
            $(this).removeClass('glyphicon-plus');
            $(this).addClass('glyphicon-minus');
            $('.ocorrencia').show();
        }
        else
        {
            $(this).removeClass('glyphicon-minus');
            $(this).addClass('glyphicon-plus');
            $('.ocorrencia').hide();
        }
        
    });
    
    $('.oc-interacoes').on('click', function(){
       
        var id_oc = $(this).attr('data-item');
        
        if($(this).hasClass('glyphicon-plus'))
        {
            $(this).removeClass('glyphicon-plus');
            $(this).addClass('glyphicon-minus');
            $('.dados-interacoes-'+id_oc).show();
        }
        else
        {
            $(this).removeClass('glyphicon-minus');
            $(this).addClass('glyphicon-plus');
            $('.dados-interacoes-'+id_oc).hide();
        }
        
    });
    
    $('#btn-ocorrencia').on('click', function(){
       
        $('.error-oc .alert-danger').html('');
        $('.error-oc').hide();
       
        var data = {};
        data.id_empresa = $('#id_empresa').val();
        data.texto = $('#texto-oc').val();
        data.id_assunto = $('.assunto-oc select').val();
        data.id_contato = $('#id-contato-oc').attr('data-item');
        data.prioridade = $('#prioridade-oc').val();
        var data_retorno_inicio = $('#data-retorno-inicio-oc').val();
        var data_retorno_fim = $('#data-retorno-fim-oc').val();
        data.id_empresas_status_ocorrencia = $('.status-oc select').val();
        data.id_setor = $('#setores-oc').val();
        data.id_usuario_ativo = $('#usuario-retorno-oc').val();
        data_retorno_inicio = data_retorno_inicio.replace('AM','');
        data_retorno_inicio = data_retorno_inicio.replace('PM','');
        data.data_retorno_inicio = data_retorno_inicio;
        data_retorno_fim = data_retorno_fim.replace('AM','');
        data_retorno_fim = data_retorno_fim.replace('PM','');
        data.data_retorno_fim = data_retorno_fim;
        console.log(data);
        if( data.id_assunto !== '' && data.id_empresas_status_ocorrencia !== '' && data.id_setor !== '' && data.texto !== '')
        {
            
            $.post(url_add_ocorrencia, data , function (data){

                if(data !== 0)
                {
                    $('.error-oc').hide();
                    $('#texto-oc').val('');
                    $('#data-retorno-oc').val('');
                    //alert('Inclusão realizada com sucesso.');
                    location.reload(true); 
                }
                else
                {
                    alert('Erro ao realizar inclusão de ocorrencia.');
                }
            
            }, 'json');
        }
        else
        {
            var erro = new Array();
            
            if( data.id_assunto === '')
            {
                erro.push('Campo Assunto esta vazio.');
            }
            if( data.id_empresas_status_ocorrencia === '')
            {
                erro.push('Campo Status esta vazio.');
            }
            if( data.id_setor === '')
            {
                erro.push('Campo Setor esta vazio.');
            }
            if( data.texto === '')
            {
                erro.push('Campo Descrição esta vazio.');
            }
            
            $.each(erro, function(k,v){
            
                $('.error-oc .alert-danger').append('<h4>'+v+'</h4>');
                $('.error-oc').show();
                
            });
            
        }
        
    });
    
    $('.btn-interacao').on('click', function(){
       
        $('.error-add-contato-in').hide();
        
        var id = $(this).attr('data-item');
        
        $('.in-add').attr('id','modal-in-add-'+id);
        $('#save-interacao').attr('data-item', id);
        
        $('.nao-opiniao').addClass('show').removeClass('hide');
        $('.sim-opiniao').addClass('hide').removeClass('show');
        selects.setor('in');
        selects.status('in');
        selects.contatos_empresa(id_empresa, 'in');
        selects.usuario_setor(-1, 'in');
        selects.emails_campanha(id, 'in');
        
        $('#modal-in-add-'+id).modal('show');
            
    });
    
    $('.btn-opiniao').on('click', function(){
       
        $('.error-add-contato-in').hide();
        
        var id = $(this).attr('data-item');
        
        $('.in-add').attr('id','modal-in-add-'+id);
        $('#opiniao-interacao').attr('data-item', id);
        
        $('.nao-opiniao').addClass('hide').removeClass('show');
        $('.sim-opiniao').addClass('show').removeClass('hide');
        selects.emails_campanha(id, 'in');
        
        $('#modal-in-add-'+id).modal('show');
            
    });
    
    $('#opiniao-interacao').on('click',function(){
        
        $('.error-add-contato-in .error-contato-in').html('');
        $('.error-add-contato-in').hide();
        
        var id = $(this).attr('data-item');
        var prioridade = $('#prioridade-in option:selected').val();
        var descricao = $('#descricao_in').val();
        if( descricao !== '')
        {
            $.post(url_add_opiniao, {

                id_empresas_ocorrencia : id,
                prioridade: prioridade,
                obs : descricao

            }, function(data){

                if(data !== 0)
                {
                    console.log(data);
                    $('.error-add-contato-in').hide();
                    $('#data-retorno-in').val('');
                    $('#descricao_in').val('');
                    location.reload(true); 
                }
                else
                {
                    alert('Erro ao salvar interação.');
                }

            },'json');
        }
        else
        {
            var erro = new Array();
            
            if(descricao === '')
            {
                erro.push('Campo Descrição esta vazio.');
            }
            
            $.each(erro, function(k,v){
            
                $('.error-add-contato-in .error-contato-in').append('<h4>'+v+'</h4>');
                $('.error-add-contato-in').show();
                
            });
        }
        
    });
    
    
    $('#save-interacao').on('click',function(){
        
        $('.error-add-contato-in .error-contato-in').html('');
        $('.error-add-contato-in').hide();
        
        var id = $(this).attr('data-item');
        var status = $('#status-in option:selected').val();
        var contato = $('#id-contato-in option:selected').val();
        var usuario_retorno = $('#usuario-retorno-in option:selected').val();
        var prioridade = $('#prioridade-in option:selected').val();
        //var data_retorno = $('#data-retorno-in').val();
        //var periodo = $('#periodo-in').val();
        var data_retorno_inicio = $('#data-retorno-inicio-in').val();
        var data_retorno_fim = $('#data-retorno-fim-in').val();
        var descricao = $('#descricao_in').val();
        
        data_retorno_inicio = data_retorno_inicio.replace('AM','');
        data_retorno_inicio = data_retorno_inicio.replace('PM','');
        
        data_retorno_fim = data_retorno_fim.replace('AM','');
        data_retorno_fim = data_retorno_fim.replace('PM','');
        
        if(status !== '' && descricao !== '')
        {
            $.post(url_add_interacao, {

                id_empresas_ocorrencia : id,
                id_empresas_status_ocorrencia : status,
                id_usuario_ativo : usuario_retorno,
                id_contato : contato,
                data_retorno_inicio : data_retorno_inicio,
                data_retorno_fim : data_retorno_fim,
                prioridade: prioridade,
                obs : descricao
                //data_retorno : data_retorno,
                //periodo : periodo,

            }, function(data){

                if(data !== 0)
                {
                    console.log(data);
                    $('.error-add-contato-in').hide();
                    $('#data-retorno-in').val('');
                    $('#descricao_in').val('');
                    //alert('Inclusão realizada com sucesso.');
                    location.reload(true); 
                }
                else
                {
                    alert('Erro ao salvar interação.');
                }

            },'json');
        }
        else
        {
            var erro = new Array();
            
            if(status === '')
            {
                erro.push('Campo Status esta vazio.');
            }
            if(descricao === '')
            {
                erro.push('Campo Descrição esta vazio.');
            }
            
            $.each(erro, function(k,v){
            
                $('.error-add-contato-in .error-contato-in').append('<h4>'+v+'</h4>');
                $('.error-add-contato-in').show();
                
            });
        }
        
    });
    
    
   
    $('#add-oc').on('click',function(){
       
        $('#modal-oc').modal('hide');
        $('.error-add-contato').hide();
        
        $('#nome_contato').val('');
        $('#email_contato').val('');
        $('#telefone_contato').val('');
        $('#funcao_contato').val('');
        $('#principal_contato').prop('checked', false);
        
        setTimeout(function(){
            
            $('#modal-contato-add').modal('show');
            $('#save-edita-contato').attr('id', 'save-contato');
            $('#save-contato').attr('data-sufix', 'oc');
            
        },'400');
        
    });
    
    $('#novo-contato-in').on('click', function(){
       
        //var sufix = $(this).attr('data-sufix');
        
        $('.in-add').modal('hide');
        $('#modal-oc').modal('hide');
        $('.error-add-contato').hide();
        
        $('#nome_contato').val('');
        $('#email_contato').val('');
        $('#telefone_contato').val('');
        $('#funcao_contato').val('');
        $('#principal_contato').prop('checked', false);
        
        setTimeout(function(){
            
            $('#modal-contato-add').modal('show');
            $('#save-contato').attr('data-sufix', 'in');
            
        },'400');
        
        //$('#modal-oc-add').attr('id', 'modal-in-co-add');
        
        //$('#modal-in-co-add #save-contato-oc').attr('data-item', id);
        
        //$('.close-co').attr('data-item', id); 
        
    });
    
    $('#modal-contato-add').on('hidden.bs.modal', function () {
        
        $('#save-edita-contato').attr('id', 'save-contato');
        var sufix = $('#save-contato').attr('data-sufix');
        
        switch(sufix)
        {
            case 'oc':
                setTimeout(function(){ $('#modal-oc').modal('show'); },'400'); 
                break;
            case 'in':
                setTimeout(function(){ $('.in-add').modal('show'); },'400'); 
                break;
        }
        
        $('#novos-campos').html('');
        
        //$('.in-add').attr('id','modal-in-add-');
        selects.setor(sufix);
        selects.status(sufix);
        selects.contatos_empresa(id_empresa, sufix);
        selects.usuario_setor(-1, sufix);
        
    });
    
    /*
    $(document).on('click', '#novo-contato-in', function(){
       
        var id = $(this).attr('data-item');
        $('.error-add-contato-oc').hide();
        $('.in-add').modal('hide');
        
        setTimeout(function(){
            
            $('#modal-oc-add').modal('show');
            
        },'1000');
        
        $('#modal-oc-add').attr('id', 'modal-in-co-add');
        
        $('#modal-in-co-add #save-contato-oc').attr('data-item', id);
        
        $('.close-co').attr('data-item', id); 
        
    });
    
    $(document).on('click', '#modal-in-co-add .close-co', function(){
       
       var id = $(this).attr('data-item');
       $(this).attr('data-item', '');
       $('#modal-in-co-add').attr('id', 'modal-oc-add');
       $('#modal-oc-add').modal('hide');
       $('#modal-oc-add #save-contato-oc').attr('data-item', '');
       setTimeout(function(){
           
           $('.in-add').modal('show');
           
       },'400');
       $('.in-add').attr('id','modal-in-add-'+id);
        
    });*/
   
    /*
    $('#novo-contato').on('click', function(){
       
        var sufix = $(this).attr('data-sufix');
        
        $('#nome_contato_'+sufix).val('');
        $('#email_contato_'+sufix).val('');
        $('#telefone_contato_'+sufix).val('');
        $('#funcao_contato_'+sufix).val('');
        $('#principal_contato_'+sufix).prop('checked', false);
        
        $('#modal-'+sufix).modal('hide');
        $('.'+sufix+'-add').modal('hide');
        setTimeout(function(){
            
            $('#modal-'+sufix+'-add').modal('show');
            $('.error-add-contato-'+sufix).hide();
            
        },'500');
        
    });*/
   
    $(document).on('click', '#save-contato', function(){
       
        $('.error-add-contato .error-contato').html('');
        $('.error-add-contato').hide();
        
        var campos = [];
        var valores = [];
        
        $('.campos-contato').each(function(k, v){  
            //var sequencia = $(this).attr('data-sequence');
            campos[k] = $(this).val(); 
        });
        
        $('.valores-contato').each(function(k, v){
            //var sequencia = $(this).attr('data-sequence');
            valores[k] = $(this).val(); 
        });
        
        //console.log(campos);
        //console.log(valores);
        
        
        var sufix = $(this).attr('data-sufix');
        //var id = $(this).attr('data-item');
        var nome = $('#nome_contato').val();
        var email = $('#email_contato').val();
        var telefone = $('#telefone_contato').val();
        var funcao = $('#funcao_contato').val();
        var principal = ($('#principal_contato').is(':checked') ? 1 : 0);
        
        if(nome !== '')
        {
            $('.error-add-contato').hide();
            
            $.post(url_add_contato, {
                
                id_empresa : id_empresa,
                funcao : funcao,
                nome : nome,
                telefone: telefone,
                email : email,
                principal : principal,
                campos : campos,
                valores : valores
                
            }, function (data){
                
                if(data)
                {
                    $('#nome_contato').val('');
                    $('#email_contato').val('');
                    $('#telefone_contato').val('');
                    $('#telefone_contato').unmask();
                    $('#funcao_contato').val('');
                    $('#principal_contato').prop('checked', false);

                    $('#modal-contato-add').modal('hide');
                    selects.contatos_empresa(id_empresa, sufix);

                    setTimeout(function(){

                        switch(sufix)
                        {
                            case 'oc':
                                $('#modal-oc').modal('show');
                                break;
                            case 'in':
                                $('.in-add').modal('show');
                                break;
                        }

                    }, '500');
                    
                }
                else
                {
                    alert('Erro ao cadastrar contato.');
                }
                
            });
        }
        else
        {
            var erro = new Array();
            
            if(nome === '')
            {
                erro.push('Campo Nome esta vazio.');
            }
            
            $.each(erro, function(k,v){
            
                $('.error-add-contato .error-contato').append('<h4>'+v+'</h4>');
                $('.error-add-contato').show();
                
            });
        }
        
    });
    
    
    $(document).on('click', '#editar-contato', function(){
       
        var sufix = $(this).attr('data-sufix');
        var id_contato = $('body #id-contato-'+sufix+' option:selected').val();
        selects.edita_contatos_empresa(id_empresa, id_contato, sufix);
        
    });
    
    $(document).on('click', '#save-edita-contato', function(){
       
        var sufix = $(this).attr('data-sufix');
        var contato = $(this).attr('data-item');
        
        var campos = [];
        var valores = [];
        
        $('.campos-contato').each(function(k, v){  
            //var sequencia = $(this).attr('data-sequence');
            campos[k] = $(this).val(); 
        });
        
        $('.valores-contato').each(function(k, v){
            //var sequencia = $(this).attr('data-sequence');
            valores[k] = $(this).val(); 
        });
        
        var nome = $('#nome_contato').val();
        var email =  $('#email_contato').val();
        var telefone = $('#telefone_contato').val();
        var funcao = $('#funcao_contato').val();
        var principal = ($('#principal_contato').is(':checked') ? 1 : 0);
        
        if(nome !== '')
        {
            $('.error-add-contato').hide();
            
            $.post(url_edt_contato+'/'+contato, {
                nome : nome,
                email : email,
                telefone : telefone,
                funcao : funcao,
                principal : principal,
                campos : campos,
                valores : valores

            }, function(data){

                if(data)
                {
                    $('#nome_contato').val('');
                    $('#email_contato').val('');
                    $('#telefone_contato').val('');
                    $('#telefone_contato').unmask();
                    $('#funcao_contato').val('');
                    $('#principal_contato').prop('checked', false);

                    $('#modal-contato-add').modal('hide');
                    $('#save-edita-contato').attr('id', 'save-contato');
                    
                    selects.contatos_empresa(id_empresa, sufix);

                    setTimeout(function(){

                        switch(sufix)
                        {
                            case 'oc':
                                $('#modal-oc').modal('show');
                                break;
                            case 'in':
                                $('.in-add').modal('show');
                                break;
                        }

                    }, '500');

                }
                else
                {
                    alert('Erro ao cadastrar contato.');
                }

            });
        }
        else
        {
            var erro = new Array();
            
            if(nome === '')
            {
                erro.push('Campo Nome esta vazio.');
            }
            
            $.each(erro, function(k,v){
            
                $('.error-add-contato .error-contato').append('<h4>'+v+'</h4>');
                $('.error-add-contato').show();
                
            });
        }
        
    });
    
    //var qtd_campos = 0;
    $(document).on('click', '#mais-campos', function(){
       
        var html;
        html  = '<div class="row">';
        html += '<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Campo:';
        //html += '<input type="text" class="form-control campos-contato"  data-sequence="'+qtd_campos+'" value="" name="campos">';
        html += '<input type="text" class="form-control campos-contato" value="" name="campos">';
        html += '</label>';
        html += '<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Valor:';
        //html += '<input type="text" class="form-control valores-contato" data-sequence="'+qtd_campos+'" value="" name="valores">';
        html += '<input type="text" class="form-control valores-contato" value="" name="valores">';
        html += '</label>';
        html += '</div>';
        $('#novos-campos').append(html);
        //qtd_campos++;
    });
    
    $(document).on('click','.send-email', function(e){
       
        e.preventDefault();
        var id = $(this).attr('data-item');
        console.log(id);
        
    });
    
    /*
    $('.in-add').on('hidden.bs.modal', function () {
        
        $('.in-add').attr('id','modal-in-add-');
        selects.setor('oc');
        selects.status('oc');
        selects.contatos_empresa(id_empresa, 'oc');
        selects.usuario_setor(-1, 'oc');
        
    });*/
    
    /*
    $('.btn-email').on('click', function(){
       
        var id = $(this).attr('data-item');
       
        $.post(URI+'empresas/email_automatico',{ id : id }, function(data){
            
            console.log(data);
            
            if(data)
            {
                alert('E-mail encaminhado com sucesso.');
            }
            else
            {
                alert('Erro ao encaminhar email.');
            }
            
        });
        
    });
    */
    
    $('.oc-play').on('click',function(){
        
        var $this = $(this);
        var id = $(this).attr('data-item');
                
        if($(this).hasClass('glyphicon-play'))
        {
            $(this).removeClass('glyphicon-play');
            $(this).addClass('glyphicon-pause');
            
            $.post(URI+'empresas/add_tempo',{id_ocorrencia: id, tempo_inicio : 1}, function(data){
                
                console.log(data);
                $this.attr('data-id-ocorrencia-tempo', data.id);
                
            }, 'json');
        }
        else
        {
            $(this).removeClass('glyphicon-pause');
            $(this).addClass('glyphicon-play');
            
            var id_tempo = $(this).attr('data-id-ocorrencia-tempo');
            
            $.post(URI+'empresas/add_tempo',{ id : id_tempo, id_ocorrencia: id, tempo_fim : 1}, function(data){
                
                console.log(data);
                
            });
        }
        
    });
    
    var time = new Array();
    
    $(document).ready(function(){
        
        $('.oc-play').each(function(){
            
            var oc = $(this).attr('data-item');
            time.push(oc);
            
        });
        
        var url_t =  URI + 'empresas/get_cookie_tempo/tempo_ocorrencia';
        
        $.getJSON( url_t, function(data){ 
            if ( data )
            {
                $.each(data,function(chave, valor){
                   
                   $.each(valor, function(key, value){
                      
                        if(value)
                        {
                            tempo.hora[chave] = key; 
                        }
                       
                   });
                    
                });
            }
          
        } );
        
    });
    
    setTimeout(function(){
        
        $('.oc-play').each(function(){
            
            var oc = $(this).attr('data-item');
            if(tempo.hora[oc] !== undefined)
            {
                $('.play-'+oc).removeClass('glyphicon-play');
                $('.play-'+oc).addClass('glyphicon-pause');
                $(this).attr('data-id-ocorrencia-tempo', tempo.hora[oc]);
            }
            
        });
        
    }, '600');
    
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

var selects = {
   
    status : function (sufix){
        
        $.get(URI+'empresas/get_status/'+sufix, function (data){
            
            $('.status-'+sufix).empty().append(data);
            
        });
    },
    
    setor : function (sufix){
        
        $.get(URI+'empresas/get_setor/'+sufix, function (data){
            
            $('.setor-'+sufix).empty().append(data);
            
        });
    },
    
    assunto : function (sufix){
        
        $.get(URI+'empresas/get_assunto/'+sufix, function (data){
            
            $('.assunto-'+sufix).empty().append(data);
            
        });
    },
    
    usuario_setor: function(setor, sufix){
        
        $.get(URI+'empresas/get_usuario/'+setor+'/'+sufix, function (data){
            
            if(data)
            {
                $('.usuario-setor-'+sufix).empty().append(data);
            }
            else
            {
                $('.usuario-setor-'+sufix).empty();
            }
            
        });
    },
    
    contatos_empresa : function (id_empresa, sufix){
        
        $.get(URI+'empresas/get_contatos_empresa/'+id_empresa+'/'+sufix, function (data){
            
            $('.contato-empresa-'+sufix).empty().append(data);
            
        });
    },
    
    edita_contatos_empresa : function (id_empresa, id_contato, sufix){
        
        $('#nome_contato').val('');
        $('#email_contato').val('');
        $('#telefone_contato').val('');
        $('#funcao_contato').val('');
        $('#principal_contato').prop('checked', false);
        
        if(id_contato != '')
        {
            $.getJSON(URI+'empresas/get_edita_contatos_empresa/'+id_empresa+'/'+id_contato+'/'+sufix, function (data){

                if(data.dados)
                {
                    $('#nome_contato').val(data.dados.nome);
                    $('#email_contato').val(data.dados.email);
                    $('#telefone_contato').val(data.dados.telefone);
                    $('#funcao_contato').val(data.dados.funcao);
                    
                    if(data.principal == 1)
                    {
                        $('#principal_contato').prop('checked', true);
                    }
                    
                    if(data.atributos.qtde > 0 )
                    {
                        $(data.atributos.itens).each(function(k, v){
                           
                            var campos;
                            campos = '<div class="row">';
                            campos += '<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Campo:';
                            //campos += '<input type="text" class="form-control campos-contato"  data-sequence="'+k+'" value="'+v.campo+'" name="campos">';
                            campos += '<input type="text" class="form-control campos-contato" value="'+v.campo+'" name="campos">';
                            campos += '</label>';
                            campos += '<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6">Valor:';
                            //campos += '<input type="text" class="form-control valores-contato" data-sequence="'+k+'" value="'+v.valor+'" name="valores">';
                            campos += '<input type="text" class="form-control valores-contato" value="'+v.valor+'" name="valores">';
                            campos += '</label>';
                            campos += '</div>';
                            $('#novos-campos').append(campos);
                            
                        });
                    }
                    
                    $('.error-add-contato').hide();
                    $('#save-contato').attr('id', 'save-edita-contato');
                    $('#save-edita-contato').attr('data-item', id_contato);
                    $('#save-edita-contato').attr('data-sufix', sufix);
                    
                    switch(sufix)
                    {
                        case 'oc':
                            $('#modal-oc').modal('hide');
                            break;
                        case 'in':
                            $('.in-add').modal('hide');
                            break;
                    }
                    setTimeout(function(){  
                        
                        $('#modal-contato-add').modal('show');

                    },'500');
                }

            });
        }
        else
        {
            alert('Selecione um usuario para edição.');
        }
        
    },
    
    emails_campanha : function(id_ocorrencia, sufix){
        
        $.getJSON(URI+'empresas/get_emails/'+id_ocorrencia, function (data){
            
            if(data !== '')
            {
                var html = '';
                
                $.each(data, function(k, v){
                   
                    html += '<li><a href="#" class="send-email" data-item="'+v.id+'">'+v.descricao+'</a></li>';
                    
                });
                
                $('.emails-'+sufix).empty().append(html);
            }
            
        });
        
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