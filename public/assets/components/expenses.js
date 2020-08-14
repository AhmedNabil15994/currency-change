/**
 * Expenses Js
 */

function deleteExpense($id) {
    swal({
        title: "هل انت متأكد ؟!",
        text: "ستكون غير قادر علي استرداد البيانات المحذوفة",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "نعم, قم بالحذف!",
        closeOnConfirm: false
    }, function () {
        swal("تم الحذف!", "تم حذف المصروف.", "success");
        $.get('/expenses/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

$(document).on('change','select[name="type"]',function(){
    if($(this).val() == 2){
        $('select[name="user_id"]').attr('disabled',false);
    }else{
        $('select[name="user_id"]').attr('disabled',true);
    }
});