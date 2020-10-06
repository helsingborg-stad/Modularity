$(document).ready(function () {
    $('.datatable').DataTable({
        dom: "lf<'clearfix'>" +
               "tr" +
               "ip<'clearfix'>",
        oLanguage: {
            "sEmptyTable": datatablesLang.sEmptyTable,
            "sInfo": datatablesLang.sInfo,
            "sInfoEmpty": datatablesLang.sInfoEmpty,
            "sInfoFiltered": '(' + datatablesLang.sInfoFiltered + ')',
            "sInfoPostFix": '',
            "sInfoThousands": ',',
            "sLengthMenu": datatablesLang.sLengthMenu,
            "sLoadingRecords": datatablesLang.sLoadingRecords,
            "sProcessing": datatablesLang.sProcessing,
            "sSearch": '<span class="sr-only">' + datatablesLang.sSearchLabel + '</span>',
            "sZeroRecords": datatablesLang.sZeroRecords,
            "oPaginate": {
                "sFirst": datatablesLang.sFirst,
                "sLast": datatablesLang.sLast,
                "sNext": datatablesLang.sNext,
                "sPrevious": datatablesLang.sPrevious
            },
            "oAria": {
                "sSortAscending":datatablesLang.sSortAscending,
                "sSortDescending": datatablesLang.sSortDescending
            }
        }
    });
});
