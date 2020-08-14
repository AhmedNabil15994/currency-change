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
