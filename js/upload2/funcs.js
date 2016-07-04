/**
 * Função upload de imagem, sem refresh...
 * Cria espaço, gerencia todas as ações do upload.
 * No iframe cria o campo, disponibiliza e carrega os arquivos.
 * necessário enviar parametros para funcionamento. verificar em upload.inicia
 * @since 2015-03-09 Carlos Claro
 * 
 * 
 * exemplo de inicialização
    {
        var data_doc = new Array();
            data_doc.classe = '.upload_doc';
            data_doc.acao = HTTP_URL + 'functions_retorno.php?func=upload_file_doc&valor=1';
            data_doc.acao = acao_retorno;
            data_doc.input = 'upload_doc';
            data_doc.multiple = false;
            data_doc.resposta_classe = '.status_doc';
            data_doc.resposta_type = 'html';
            data_doc.pasta = 'js/upload/';
            data_doc.extra = '<input type="hidden" name="id_exame" value="<?php echo $item->id_exame; ?>">';
        upload_doc.inicia(data_doc);
    }
 * 
 * 
 * 
 */
upload = {
    funcao_acao: '',
    frame_id: '',
    classe: '',
    acao: '',
    acao_retorno: 'arquivo.acao',
    input:'',
    multiple: false,
    resposta_type: 'json',
    resposta_classe: '.status',
    pasta: '',
    extra: '',
    iframe: '',
    limite_kb: '1048576',
    type: 'jpeg|png|gif',
    type_array: [],
    /**
     * Inicia o espaço de upload, e executa as ações de inicialização.
     * @param {Array} data - reune todos os parametros de inicialização, nenhum parametro é obrigatório e tem seus valores default.
     * @param {string} data.classe - classe de montagem da ferramenta.
     * @param {string} data.acao - url para onde o sistema deve mandar as imagens no submit
     * @param {string} data.acao_retorno - acao de retorno para a exibi��o
     * @param {string} data.input - nome do input para upload das imagens
     * @param {bollean} data.multiple - se o tipo de upload é multiple
     * @param {string} data.resposta_type - tipo de resposta, html ou json, se json deve ter data.erro, data.arquivo(s)
     * @param {string} data.resposta_classe - para onde os arquivos devem ser salvos.
     * @param {string} data.pasta - local onde o app deve ser chamado.
     * @param {string} data.extra - qualquer informação que deve ser incorporada ao formulario, para post ou get.
     * @returns {void}
     */
    inicia: function(data){
        if ( data != undefined )
        {
            upload.funcao_acao = ( data.funcao_acao != undefined ) ? data.funcao_acao : 'arquivo';
            upload.classe = ( data.classe != undefined ) ? data.classe : '.upload';
            upload.acao = ( data.acao != undefined ) ? data.acao : '#';
            upload.acao_retorno = ( data.acao_retorno != undefined ) ? data.acao_retorno : 'arquivo.acao';
            upload.input = ( data.input != undefined ) ? data.input : 'upload';
            upload.multiple = ( data.multiple != undefined ) ? data.multiple : false;
            upload.resposta_type = ( data.resposta_type != undefined ) ? data.resposta_type : 'json';
            upload.resposta_classe = ( data.resposta_classe != undefined ) ? data.resposta_classe : '.status';
            upload.pasta = ( data.pasta != undefined ) ? data.pasta : './';
            upload.extra = ( data.extra != undefined ) ? data.extra : '';
            upload.limite_kb = ( data.limite_kb != undefined ) ? data.limite_kb : '1048576';
            upload.type = ( data.type != undefined ) ? data.type : 'jpeg|png|gif';
            
        }
        upload.frame_id = 'upload-frame' + upload.input
        var type = upload.type;
        upload.type_array = type.split("|");
        var iframe = upload.get_iframe();
        $(upload.classe).html(iframe);
    },
    verifica_status: function(){
        console.log(upload.resposta_classe + '_status', $(upload.resposta_classe + '_status'));
        $(upload.resposta_classe + '_status').html('<p>Carregando....</p>');
        $(upload.classe + ' #' + upload.frame_id).addClass('hide');
        $(upload.classe + ' #' + upload.frame_id).load(function(){
            $(upload.resposta_classe + '_status').html('<p>Ok.</p>');
            if ( upload.resposta_type == 'json' )
            {
                var resposta = $.parseJSON( $(upload.classe + ' #' + upload.frame_id).contents().find('body').html() );
                arquivo.acao(resposta);
                console.log(resposta);
            }
            else
            {
                var resposta =  $(upload.classe + ' #' + upload.frame_id).contents().find('body').html() ;
                $(upload.resposta_classe).html(resposta);
            }
            setTimeout(function(){
                upload.inicia();
            },200);
        });
    },
    pega_arquivos: function( classe ){
        //$('.upload #upload-frame').contents().find('.upload-input')[0]
        var e = classe;
        var arquivos = new Array();
        for( var a = 0; e.files[a] != undefined; a++ )
        {
            arquivos[a] = e.files[a];
        }
        return arquivos;
    },
    
    carrega_espaco_arquivos: function(arquivos){
        $.each(arquivos,function(k,v){
            var type_valido = '';
            if ( v.type == '' )
            {
                var nome = v.name;
                var tamanho = nome.length
                var t = '/' + nome.substr( ( tamanho - 4 ), 4);
                type_valido = upload.valida_type(t);
            }
            else
            {
                type_valido = upload.valida_type( String(v.type) );
            }
            
            var tamanho_valido = upload.valida_tamanho( v.size );
            var html_img = '<div class="alert alert-';
            html_img += ( ( type_valido || tamanho_valido ) ? 'success' : 'danger' );
            html_img += ' arquivo elemento-' + k + '" data-item="' + k + '">' + v.name;
            html_img += ' ' + ( type_valido ? '' : 'Arquivo Inválido., tente um arquivo: ' + upload.type );
            html_img += ' ' + ( tamanho_valido ? '' : 'O arquivo deve ter no maximo: ' + ( upload.limite_kb / 1048576 ) + ' mb' );
            html_img += '<div class="espaco-carregando"></div></div>';
            $('.' + upload.funcao_acao).append( html_img );
        });
        
    },
    valida_tamanho: function( tamanho ) {
        var retorno = false;
        if ( tamanho <= upload.limite_kb )
        {
            retorno = true;
        }
        return retorno;
    },
    valida_type: function( type ){
        var retorno = false;
        var t = type.split('/');
        $.each( upload.type_array ,function(k,v){
            var t1 = t[1];
            t1 = t1.replace('"','');
            var pos = v.indexOf( t[1] );
            console.log(k,v,t,pos);
            if( retorno != true )
            {
                retorno = ( pos >= 0 ) ? true : false;
            }
        }); 
        return retorno;
    },
    pega_espaco_arquivos: function( arquivos ){
        var arquivos = $(upload.classe + ' #' + upload.frame_id).contents().find('.' + upload.funcao_acao);
        $(upload.resposta_classe).append(arquivos);
        $('.' + upload.funcao_acao + ' .espaco-carregando').html('<img src="' + URI + 'images/loader_azul.gif">');
    },
    get_iframe: function(){
        var retorno = '';
        retorno += '<iframe id="' + upload.frame_id + '" scrolling="no" frameborder="0" width="150px" height="40px" src="' + upload.pasta + 'upload_html.html" style="color:#FFFFFF;">';
        retorno += '</iframe>';
        return retorno;
    },
};
