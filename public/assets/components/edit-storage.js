var productsArray = JSON.parse($('input[name="items"]').val());
var prodCounter = 0;

// Remove Record From Distributions Table
$(document).on('click','.btn-danger.removeRecord',function(e){
	e.preventDefault();
	var rowIndex = $('table.hover tr').index($(this).closest('tr'));
	var currency_id = $(this).data('area');
	removeItemFromArray(productsArray,rowIndex-1);
	$('select.currency_id option[value="'+currency_id+'"]').attr('disabled',false);
	$('select.currency_id').select2();
	$(this).parents('tr').remove();
});

function removeItemFromArray(data,index){
    if (index > -1) {
      data.splice(index, 1);
    }
    return data;
}

// Add New Shoe Inside Ditributions Table
$(document).on('click','.btn-success.addNewShoe',function(e){
	e.preventDefault();
	var shopName = $('select.shop_id option:selected').text();
	var currencyName = $('select.currency_id option:selected').text();
	var currency_id = $('select.currency_id option:selected').val();
	var shop_id = $('select.shop_id option:selected').val();
	var balance = $('input[name="balance"]').val();
	var myRow = '';
	
	if(shop_id != 0 && currency_id != 0  && balance){
		prodCounter++;
		var elemIndex = productsArray.length;
		myRow = '<tr>'+
					'<td>'+ shopName +'</td>'+
					'<td>'+ currencyName +'</td>'+
					'<td>'+ balance +'</td>'+
					'<td><button class="btn btn-xs btn-danger removeRecord" data-area="'+currency_id+'"><i class="fa fa-trash"></i> حذف</button></td>'+
				'</tr>';
		var distArray = [currency_id,balance];
		checkInsertedData(elemIndex,distArray);
		$('select.currency_id option[value="'+currency_id+'"]').attr('disabled',true);
		$('select.currency_id').select2();
		$('table tbody tr.empty').remove();			
		$('table tbody').append(myRow);
		resetDistributedValues();	
	}
});

// Reset Distribution Table
function resetDistributedValues(){
	$('select.currency_id').val(0).trigger('change');	
	$('select.shop_id').attr('disabled',true);	
	$('input[name="balance"]').val('1');
}

function checkInsertedData(elemIndex,distArray){
	if(!productsArray[elemIndex]){
		productsArray[elemIndex] = [];
		productsArray[elemIndex] = distArray;
	}else{
		var prodDistIndex = productsArray[elemIndex].length;
		productsArray[elemIndex][prodDistIndex] = distArray;
	}		
}


$('.addSupply').on('click',function(e){
	e.preventDefault();
	e.stopPropagation();
	var myElem = $(this);
	$id = myElem.attr('data-area');
	myElem.attr('disabled',true);
	var myCheck = 0;
	if($('input[name="active"]').is(':checked')){
		myCheck = 1;
	}
	$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type:'post',
        url: '/storages/update/'+$id,
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'data': JSON.stringify(productsArray),
            'shop_id': $('select.shop_id').val(),
            'is_active': myCheck,
        },
        success:function(data){
        	if(data.status.status == 0){
				myElem.attr('disabled',false);
				errorNotification(data.status.message);
        	}else if(data.status.status == 1){
				successNotification(data.status.message);
				setTimeout(function(){
					myElem.attr('disabled',false);
					window.location.href = '/storages';
				},2000);
        	}
        },
        error:function(data){
			myElem.attr('disabled',false);
			errorNotification('حدث خطأ في النظام');
        }
    });  
});
