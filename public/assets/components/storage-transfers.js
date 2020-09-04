/**
 * Storage Transfers Js
 */

function deleteStorageTransfer($id) {
    swal({
        title: "هل انت متأكد ؟!",
        text: "ستكون غير قادر علي استرداد البيانات المحذوفة",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "نعم, قم بالحذف!",
        closeOnConfirm: false
    }, function () {
        swal("تم الحذف!", "تم حذف التحويل من الصندوق.", "success");
        $.get('/storage-transfers/delete/' + $id,function(data) {
            if (data.status.status == 1) {
                $('#tableRaw' + $id).remove();
                successNotification(data.status.message);
            } else {
                errorNotification(data.status.message);
            }
        })
    });
}

$('select[name="type"]').on('change',function(e){
    e.preventDefault();
    e.stopPropagation();
    var type_value = $(this).val();
    if(type_value){
        $('select[name="to_id"]').empty();

        $.get('/storage-transfers/get_to/'+type_value,function(data){
            if(type_value == 1){
                $('select[name="to_id"]').append('<option value="">اختر الصندوق</option>');
                $.each(data,function(index,item){
                    $('select[name="to_id"]').append('<option value="'+item.id+'">صندوق '+item.shop_name+'</option>');
                });
            }else if(type_value == 2){
                $('select[name="to_id"]').append('<option value="">اختر الحساب البنكي</option>');
                $.each(data,function(index,item){
                    $('select[name="to_id"]').append('<option value="'+item.id+'">'+item.name+'</option>');
                });
            }
            $('select[name="to_id"]').select2();
        });
    }
});
