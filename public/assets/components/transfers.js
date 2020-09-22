/**
 * Transfers Js
 */

function deleteTransfer($id) {
    swal({
        title: "هل انت متأكد ؟!",
        text: "ستكون غير قادر علي استرداد البيانات المحذوفة",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "نعم, قم بالحذف!",
        closeOnConfirm: false
    }, function () {
        swal("تم الحذف!", "تم حذف الحوالة البنكية.", "success");
        $.get('/transfers/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

$('select[name="type"]').on('change',function(){
    if($(this).val() == 1){
        $('.first').fadeOut(250);
    }else{
        $('.first').fadeIn(250);
    }
});

$('select[name="currency_id"]').on('change',function(){
    if($(this).val()){
        $('select[name="bank_account_id"]').empty();
        $('select[name="bank_account_id"]').append('<option value="">اختر..</option>');
        $.get('/transfers/getBanksAccounts/' + $(this).val(),function(data) {
            $.each(data.data,function(index,item){
                $('select[name="bank_account_id"]').append('<option value="'+item.id+'">'+item.account_number +' - '+item.name+' - '+item.shop_name+'</option>');
            });
            $('select[name="bank_account_id"]').select2();
        })
    }
})

function getCalcs(){
    $('input[name="price"]').val(0);
    $('input[name="paid"]').val(0);
    var amount = $('input[name="balance"]').val();
    var commission_rate = $('input[name="commission_rate"]').val();
    var convert = $('select[name="details_id"] option:selected').attr('data-area');
    var convertLabel = $('select[name="details_id"] option:selected').attr('data-area2');
    if(convert > 0 && amount > 0){
        $('input[name="price"]').val(convert+' '+convertLabel);
        $('input[name="paid"]').val(((convert * amount) + (commission_rate / 100 * convert * amount ) ).toFixed(2) + ' '+convertLabel);
    }
}

$('select[name="details_id"]').on('change',function(){
    getCalcs();
});

$('input[name="balance"]').on('blur',function(){
    getCalcs();
});

$('input[name="commission_rate"]').on('blur',function(){
    getCalcs();
});