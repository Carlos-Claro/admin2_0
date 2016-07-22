$(function(){
    
    $('.editar').on({
        click : function(){
           
            var url = URI+"cadastro_has_promocao/editar/"+$(this).attr('data-item');
            window.location.href = url;
            
        }
    });

    $('.deletar').on({
        click : function(){
            var $selecionados = get_selecionados();
            if($selecionados.length > 0)
            {
                    var $url = URI+"cadastro_has_promocao/remover/";
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
    
    
    
    /**
     * pega o id=busca_cadastro e com o .on anexa o manipulador de eventos "keyup", com o .html 
     * a div com o classe resposta_cadastro e retornada vazia, cria a variavel busca como string,
     * a val busca concatena com o conteudo de busca_cadastro e o .val obtem os valores do input,
     * o setTimeout chama a função depois de 500 milisegundos, .getJson é utilizado para receber 
     * os dados JSON do caminho expecificado apos aberto o .getJSON é criada a var lista como string
     * e seu valor é concatenado como uma div de classe=list-group, .each     
     * o valor de lista concatena com o fechamento da div, 
     */
    $('#busca_cadastro').on('keyup', function()
    {
        $('.resposta_cadastro').html('');
        var busca1 = $(this).val();
        if( busca1.length >= 3 )
        {
                $.getJSON(URI+'cadastro_has_promocao/get_cadastro/'+busca1).done(function(data)
                {
                    //console.log(data);
                    var busca2 = $('#busca_cadastro').val();
                    //console.log(busca1, busca2);
                    if(busca === busca2)
                    {
                        var lista = busca.set_lista(data);
                        $('.resposta_cadastro').html(lista);
                    }
                    else
                    {
                        $.getJSON(URI+'cadastro_has_promocao/get_cadastro/'+busca2).done(function(data2)
                        {
                            var lista = busca.set_lista(data2);
                            $('.resposta_cadastro').html(lista);
                        }).fail(function(){
                    $('.resposta_cadastro').html("Erro ao buscar listagem!!");
                });
                    }

                }).fail(function(){
                    $('.resposta_cadastro').html("Erro ao buscar listagem!");
                });

        }
        
        
    });
    
    /**
     * a classe resposta_cadastro com o .on anexa o manipulador de eventos "click", id=list-item, o function 
     * recebe event como parametro, o e.preventDefault pára a ação padrão do elemento "click", a var id recebe 
     * o valor da classe resposta_cadastro com a devolução de data-item, a var descrição recebe
     * o valor da classe resposta_cadastro transformada em texto, o id=id_cadastro é retornado o valor do input 
     * de id, o id=busca_cadastro é retornado o valor do unput de descrição e a classe resposta_cadastro e retornada
     * vazia
     */
    $('.resposta_cadastro').on('click', '.list-group-item', function(e){
        
            e.preventDefault();
            var id = $(this).attr('data-item');
            var descricao = $(this).text();
            $('#id_cadastro').val(id);
            $('#busca_cadastro').val(descricao);
            $('.resposta_cadastro').html('');
    });
});



