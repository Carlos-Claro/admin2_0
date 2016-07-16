$(function(){

    $('#data_publicacao').setMask('99/99/9999 99:99');
    $('#data_acao_inicio').setMask('99/99/9999 99:99');
    $('#data_acao_fim').setMask('99/99/9999 99:99');
    
//    $('#datetimepicker-inicio').datetimepicker({
//        language: 'pt-BR',
//        useSeconds: true, 
//    });
    
    $('.editar').on({
        click : function(){
            
            var url = URI+"canais_conteudo/editar/"+$(this).attr('data-item')+"/"+$('.valor_pai').val();
            window.location.href = url;
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"canais_conteudo/remover/";
                    if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
                    {
                            $.post($url, { 'selecionados': $selecionados}, function(data){
                                    pop_up(data, setTimeout(function(){location.reload()}, 100));
                            });
                    }
            }
            else
            {
                    pop_up('nenhum item selecionado');
            }
        }
    });
    
    $('#titulo').on('blur', function(){
        
        var titulo =  $(this).val();
        
        var url = URI+"canais_conteudo/gera_link_automatico/";
       
        $.post(url, { titulo: titulo } ,function(data){
            
            $.post(URI+'canais_conteudo/verifica_link/',{ link : data }, function(valor){
                
                if(valor == 0)
                {
                    $('#link').val(data);
                }
                else
                {
                    $('#link').val(valor);
                }
                console.log(valor);
                
            });
            
        });
        
    });
    
    $('#description').on('keyup',function(){

            var alvo  = $("#char-digitado");

            var max = 140;

            var digitados = $(this).val().length;

            var restante = max - digitados;

            if(digitados > max)
            {
                var val = $(this).val();
                $(this).val(val.substr(0, max));
                restante = 0;
            }

            alvo.html(restante);
    });
    
    
    var editor = CKEDITOR.instances.descricao;
    editor.on('instanceReady', function(evt){
       
        this.dataProcessor.writer.setRules('table',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('tr',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('td',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('div',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('p',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('br',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('ul',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('li',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('hr',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        this.dataProcessor.writer.setRules('a',{
           
            indent : false,
            breakBeforeOpen : false,
            breakAfterOpen : false,
            breakBeforeClose : false,
            breakAfterClose : false
            
        });
        
    });
    
   
    //CKEDITOR.instances['descricao'].updateElement();
    
    /*
    var editor = CKEDITOR.instances.descricao;
    editor.on( 'change', function( evt ){
                
            var alvo  = $("#char-digitado");
            var max = 200;

            var digitados = evt.editor.getData().length;
            var restante = max - digitados;

            alvo.html(restante);
                
        
     }, editor.element.$ );
     */
    
});