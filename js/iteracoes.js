/*
 * Classe destinada a gerenciar as iterações.
 */

$(function() {
    
});

var iteracao = {
    iteracoes_abertas: function() {
        console.log('oooo');
        
        url = URI + 'tarefas/set_qtde_iteracoes';
        $.post(url, [], function(data) {
            // só mostra se tiver uma quantidade acima de 0
            if (data > 0) {
                $('.qtde_iteracoes').html(data);
                console.log(data);
            }
        })
    }
};