$(function(){
    var group = [];

    $('#btn-add-num').click(function(){
        group.push($('#numero').val());
        var cadena = '';
        group.forEach(function(campo, index) {
            if ( index == 0 ) {
                cadena += campo;
            }else{
                cadena += ', ' +campo;
            }
        });
        $('#contenedor-set').html('Set: [ ' + cadena + ' ]');
        $('#numero').val('').focus();
	});

    $('#btn-ordenar').click(function () {
        var arreglo = 0;
        if ( group.length > 0 ) {
            arreglo = group;
        }

		$.post('/group-by-interval/ordenar', { rango : $('#rango').val(), group : arreglo }, function(ordenado){
            var cadena_ordenada = '';

            if ( ordenado.ordenado == true ) {
                var arreglo         = ordenado.group_ordenado;
                arreglo.forEach(function(campo, index) {
                    if ( index == 0 ) {
                        cadena_ordenada += campo;
                    }else{
                        cadena_ordenada += ', ' +campo;
                    }
                });
            }else{
                cadena_ordenada = ordenado.mensaje;
            }

            $('#contenedor-set-resultado').html('Set: [ ' + cadena_ordenada + ' ]');
		},'json');
		return false;
    });

});
