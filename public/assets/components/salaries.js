$(document).on('change','select.shop_id',function(){
	var shop_id = $(this).val();
	$('select.user_id').empty();
	$('select.user_id').append('<option value="0">اختر البائع</option>');
	if(shop_id > 0){
        $.get('/sales-invoices/add/getUsers/'+shop_id,function(data) {
            $.each(data,function(index,item){
                $('select.user_id').append('<option value="'+item.id+'">'+item.name+'</option>');
            });
        })
        $('select.user_id').select2();
    }
});