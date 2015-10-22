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
                console.table(ordenado.group_ordenado);
                var arreglo = ordenado.group_ordenado;

                arreglo.forEach(function(campo, index) {

                    if ( campo.length == 1 ) {
                        cadena_ordenada += ' [ ' + campo + ' ] ';
                    }else{

                        for (var sub = 0; sub < campo.length; sub++) {

                            if ( sub == 0 ) {
                                cadena_ordenada += ' [ ' + campo[sub];
                            }else{
                                if ( sub == campo.length-1 ) {
                                    cadena_ordenada += ' ,  ' + campo[sub]  + ' ] ';
                                }else{
                                    cadena_ordenada += ' ,  ' + campo[sub];
                                }
                            }

                        }

                    }

                    if ( index < arreglo.length - 1 ) {
                        cadena_ordenada += ' , ';
                    }
                    console.log(campo);
                });
            }else{
                cadena_ordenada = ordenado.mensaje;
            }

            $('#contenedor-set-resultado').html('{ ' + cadena_ordenada + ' }');
		},'json');
		return false;
    });

    $('#btn-limpiar').click(function () {
        group = [];
        $('#rango').val('');
        $('#numero').val('');
        $('#contenedor-set').html('');
        $('#contenedor-set-resultado').html('');
    });

});
