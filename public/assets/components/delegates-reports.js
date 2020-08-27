$('#example').DataTable( {
    dom: 'Bfrtip',
    buttons: [
        'csv', 'excel', 'pdf', 'print',
    ]
} );

$('.print').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    $('.buttons-csv')[0].click();
});