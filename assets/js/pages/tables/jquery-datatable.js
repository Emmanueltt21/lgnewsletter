$(function () {
    $('.js-basic-example').DataTable({
        responsive: true
    });

    //Exportable table
    $('.js-exportable').DataTable({
        dom: 'Bfrtip',
        responsive: true,
        order: [],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});