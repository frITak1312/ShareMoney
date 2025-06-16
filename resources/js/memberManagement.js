$(document).ready(function () {
    const table = $("#member-management-table").DataTable({
        responsive: true,
        order: [[0, "asc"]],
        language: {
            search: "Hledat:",
            lengthMenu: "_MENU_ záznamů na stránku",
            pageLength: 5,
            info: "Zobrazují se záznamy _START_ až _END_ z _TOTAL_",
            infoEmpty: "",
            infoFiltered: "filtrováno z _MAX_ celkových záznamů",
            zeroRecords: "Nenalezeny žádné odpovídající záznamy",
            emptyTable: "Žádné záznamy k zobrazení.",
            paginate: {
                next: "Další",
                previous: "Předchozí",
            },
        },
        lengthMenu: [5, 10, 25, 50, 100],
        dom:
            "<'row mb-3 align-items-center'" +
            "<'col-sm-6'l>" +
            "<'col-sm-6 d-flex justify-content-end align-items-center gap-2'fB>" +
            ">" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        buttons: [
            {
                attr: {
                    id: "add-member-btn",
                    class: "btn btn-success btn-sm",
                },
                text: '<i class="fa fa-plus-circle me-1"></i> Přidat člena',
            },
        ],
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                searchable: false,
            },
        ],
    });
    //modal pro přidání člena
    $("#add-member-btn").click(() =>
        new bootstrap.Modal("#addMemberModal").show(),
    );
});
