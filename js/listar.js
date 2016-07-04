// JavaScript Document
$(function(){
        $('.tb_carlos tbody tr').on('click', function() {
            var $this = $(this);
            var $check = $this.find('input[type="checkbox"]');
            if ($check.is(":checked")) {
                    $this.removeClass('warning');
                    $check.prop("checked",false);
            } 
            else 
            {
                    $check.prop("checked", true);
                    $this.addClass('warning');
            }
            verifica_check();
    }).bind("mouseenter", function (event) {
                    linha_selecionada = $(this).find('input[type="checkbox"]').val();
    });

    $('.tb_carlos tbody input[type="checkbox"]').on('click', function() {
            this.checked = !this.checked;
    });

    $('#sel_todos ').on('click', function(){
            if ( ! $(this).is(':checked') )
            {
                    $(this).attr('checked',true);
                    $('tbody input[type=checkbox]').prop('checked',true);
                    $('tbody  tr').addClass('warning');
            }
            else
            {
                    $(this).prop('checked',false);
                    $('tbody input[type=checkbox]').prop('checked', false);
                    $('tbody tr').removeClass('warning');
            }
    });
});


function verifica_check() {
	var $todos = $('#sel_todos');
	$todos.prop("checked", true);
	$('.tb_carlos tbody input[type="checkbox"]').each(function() {
		if (!this.checked) {
			$todos.prop("checked",false);
			return false;
		}
	});
}

/*

$('.sel_todos').live('click',function() {
	var $selecionado = $(this).attr("checked");
	var $linhas = $('.tb_carlos tbody tr');
	if ($selecionado) {
		$linhas.addClass('warning');
	} 
	else 
	{
		$linhas.removeClass('warning');
	}
	$('.tb_carlos input[type="checkbox"]').each(function() {
		this.checked = $selecionado;
	});
});
$('.sel_linha input[type="checkbox"], .sel_linha td').live('click', function(){
	console.log(this);
	if ( $('.sel_linha td').hasClass('warning') )
	{
		$(this).removeClass('warning');
		$(this).removeAttr('checked');
	}
	else
	{
		$(this).addClass('warning');
		$(this + ' input[type="checkbox"]').attr('checked','checked');
	}
	
});
*/
function get_selecionados() {
	var $selecionados = new Array();
	$('.tb_carlos tbody td input[type=checkbox]').each(function() {
		if (this.checked) {
			$selecionados.push(this.value);
		}
	});
	return $selecionados;
}



