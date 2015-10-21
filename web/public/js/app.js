$(function(){

    $('table#tbl-users').on('click', 'a.btn-detalle', function(){
        console.log(this);
		var id = $(this).attr('data-id');
		$.post('/user/view/' + id , function(data){
            $('#modal-user').modal('show');
		},'json');
		return false;
	});

});
