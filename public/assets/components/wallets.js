/**
 * Wallets Js
 */

function deleteWallet($id) {
    swal({
        title: "هل انت متأكد ؟!",
        text: "ستكون غير قادر علي استرداد البيانات المحذوفة",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "نعم, قم بالحذف!",
        closeOnConfirm: false
    }, function () {
        swal("تم الحذف!", "تم حذف العملية.", "success");
        $.get('/wallets/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

function getCalcs(){
    $('input[name="convert_price"]').val(0);
    $('input[name="total"]').val(0);
    var amount = $('input[name="amount"]').val();
    var convert = $('select[name="details_id"] option:selected').attr('data-area');
    var convertLabel = $('select[name="details_id"] option:selected').attr('data-area2');
    if(convert > 0 && amount > 0 ){
        $('input[name="convert_price"]').val(convert+' '+convertLabel);
        $('input[name="total"]').val((convert * amount).toFixed(2) + ' '+convertLabel);
    }
}

$('select[name="details_id"]').on('change',function(){
    getCalcs();
});

$('input[name="amount"]').on('blur',function(){
    getCalcs();
});