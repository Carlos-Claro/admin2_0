$(function(){
    
    $('form').on('change','#sel_todos',function()
    {
        if($(this).is(':checked'))
        {
            $('.groups').prop('checked',true);
        }
        else
        {
            $('.groups').prop('checked',false);
        }
    });
    
    $('form').on('change','input[type=checkbox][name=todas_cidades]',function()
    {
        if($(this).is(':checked'))
        {
            $('#grupos').hide();
        }
        else
        {
            $('#grupos').show();
        }
    });
    
    var html_input = ''; 
    $( "#mais_pages" ).on('click',function()
    {
        html_input += '<div class="col-lg-4">';
        html_input += '     <div class="form-group">';
        html_input += '         <label for="id_page">ID da Página</label>';
        html_input += '         <input type="text" name="id_page[]" class="form-control" required="required">';
        html_input += '     </div>';
        html_input += '</div>';
        $( '#add_pages' ).html( html_input );
     });
    
    $('#filtrar_por').on('change', function()
    {
        var url = URI+'facebooks/montar_filtro';
        var valor = $('#filtrar_por option:selected').val();
        $('#valores').html('');
        $('#grupos').html('');
        $.post(url, { 'valor': valor}, function(data){
            $('#resultado').html(data);
        });
    });
    
    $('form').on('change', '#tipo_select',function()
    {
        var tipo_select = $('#tipo_select option:selected').val();
        $('#valores').html('');
        $('#grupos').html('');
        if(tipo_select != '')
        {
            $('#postar_selecionar').prop('disabled', false);
        }
        else
        {
             $('#postar_selecionar').prop('disabled', true);
        }
     });
     
    $('form').on('change', '#postar_selecionar',function()
    {
         var valor = $('#postar_selecionar option:selected').val();
         var filtro = $('#filtrar_por option:selected').val();
         var tipo_select = $('#tipo_select option:selected').val();
         var operacao = $('input[type=hidden][name=operacao]').val();
         $('#grupos').html('');
         
         var url;
         if(filtro == 'facebook_categorias' && valor == 'selecionar_categoria')
         {
             url = URI+'facebooks/'+ 'montar_check';
         }
         else if(filtro == 'estados' && valor == 'selecionar_cidade')
         {
             url = URI+'facebooks/'+ 'montar_select';
         }
         
         $.post(url, { 'tipo_select': tipo_select, 'filtro' : filtro, 'operacao' : operacao }, function(data)
         {
               //console.log(data);
               if(data && valor == 'selecionar_categoria')
               {
                   $('#grupos').html(monta_html(valor, data));
               }
               else if(data && valor == 'selecionar_cidade')
               {
                   $('#valores').html(monta_html(valor, data));
               }
               else
               {
                   $('#valores').html('');
               }
         });
         $('#cidades').html('');
     });
     
     $('form').on('change', '#id_cidade',function()
     {
          var id_cidade = $('#id_cidade option:selected').val();
          var operacao = $('input[type=hidden][name=operacao]').val();
          if(id_cidade != '')
          {
             var url = URI+'facebooks/montar_check';
             $.post(url, { 'id_cidade': id_cidade, 'operacao' : operacao}, function(data){
                $('#grupos').html(data);
            });
          }
          else
          {
             $('#grupos').html('');
          }
     });
     
      $('#uf_estado').on('change',function()
      {
            var tipo_select = $('#uf_estado option:selected').val();
            if(tipo_select != '')
            {
               var url = URI+'facebooks/montar_select';
               $.post(url, { 'tipo_select': tipo_select}, function(data){
                  var html = '';
                  html += '<label for="id_cidade">Cidade</label>';
                  html += data;
                  $('#cidades').html(html);
              });
            }
            else
            {
               $('#cidades').html('');
            }
        });
});

function monta_html(filtro, data)
{
    if(filtro == 'selecionar_cidade')
    {
        var div_retorno = '';
        div_retorno +=  '<label for="id_cidade">Cidade</label>';
        div_retorno +=  '<div class="controls " >';
        div_retorno +=  data;
        div_retorno +=  '</div>';
        div_retorno +=  '<div class="checkbox">';
        div_retorno +=  '<label>';
        div_retorno +=  '   <input name="todas_cidades" type="checkbox" value="1"> Postar em Todos os Grupos da Cidade ';
        div_retorno +=  '</label>';
        div_retorno +=  '</div>';
    }
    else if(filtro == 'selecionar_categoria')
    {
        var div_retorno = '';
        div_retorno +=  '<label for="id_categoria">Categorias</label>';
        div_retorno +=  '<div class="controls " >';
        div_retorno +=  data;
        div_retorno +=  '</div>';
        div_retorno +=  '<div class="checkbox">';
    }
    return div_retorno;
}