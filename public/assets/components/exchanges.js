/**
 * Exchange Js
 */

function deleteExchange($id) {
    swal({
        title: "هل انت متأكد ؟!",
        text: "ستكون غير قادر علي استرداد البيانات المحذوفة",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "نعم, قم بالحذف!",
        closeOnConfirm: false
    }, function () {
        swal("تم الحذف!", "تم حذف عملية الاستبدال.", "success");
        $.get('/exhanges/delete/' + $id,function(data) {
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
    if($(this).val() == 2){
        $('.clients').fadeOut(250);
        $('.client-data').fadeOut(250);
        $('.delegates').fadeIn(250);
    }else{
        $('.delegates').fadeOut(250);
        $('.clients').fadeIn(250);
        $('.client-data').fadeIn(250);
    }
});

$('select[name="client_id"]').on('change',function(){
    if($(this).val() == 0){
        $('.client-data').fadeIn(250);
    }else{
        $('.client-data').fadeOut(250);
    }
});


function getCalcs(){
    $('input[name="price"]').val(0);
    $('input[name="paid"]').val(0);
    var amount = $('input[name="amount"]').val();
    var convert = $('select[name="details_id"] option:selected').attr('data-area');
    var convertLabel = $('select[name="details_id"] option:selected').attr('data-area2');
    if(convert > 0 && amount > 0 ){
        $('input[name="price"]').val(convert+' '+convertLabel);
        $('input[name="paid"]').val((convert * amount).toFixed(2) + ' '+convertLabel);
    }
}

$('select[name="details_id"]').on('change',function(){
    getCalcs();
});

$('input[name="amount"]').on('blur',function(){
    getCalcs();
});