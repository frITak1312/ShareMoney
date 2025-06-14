$(document).ready(function () {
    const table = $("#transaction-history-table").DataTable({
        responsive: true,
        language: {
            search: "Hledat:",
            lengthMenu: "_MENU_ záznamů na stránku",
            info: "Zobrazují se záznamy _START_ až _END_ z _TOTAL_",
            infoEmpty: "",
            infoFiltered: "filtrováno z _MAX_ celkových záznamů",
            zeroRecords: "Nenalezeny žádné odpovídající záznamy",
            paginate: {
                next: "Další",
                previous: "Předchozí",
            },
        },
        dom:
            "<'row mb-3 align-items-center'" +
            "<'col-sm-6 d-flex align-items-center'l>" +
            "<'col-sm-6 d-flex justify-content-end align-items-center gap-1'f''B>" +
            ">" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        buttons: [
            {
                extend: "pdf",
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: "export-btn",
            },
        ],
    });

    // Přidání mezery mezi tlačítky (pro jistotu i ručně)
    setTimeout(function () {
        $(".dt-buttons .btn").addClass("me-2");
        $(".dt-buttons .btn:last-child").removeClass("me-2");
    }, 100);

    // Otevření modálů
    $("#leave-account-btn").click(() =>
        new bootstrap.Modal("#leaveAccountModal").show(),
    );
    $("#add-member-btn").click(() =>
        new bootstrap.Modal("#addMemberModal").show(),
    );
    $("#delete-account-btn").click(() =>
        new bootstrap.Modal("#deleteAccountModal").show(),
    );
    $("#deposit-money-btn").click(() =>
        new bootstrap.Modal("#depositMoneyModal").show(),
    );
    $("#send-payment-btn").click(() =>
        new bootstrap.Modal("#sendPaymentModal").show(),
    );
    // Modal s info o uživateli
    $(".user-detail-box").click(function () {
        const name = $(this).data("name");
        const username = $(this).data("username");
        const role = $(this).data("role");
        const joined = $(this).data("joined");

        $("#modal-name").text(name);
        $("#modal-username").text("@" + username);
        $("#modal-role").text(role);
        $("#modal-joined").text(joined);

        new bootstrap.Modal("#userInfoModal").show();
    });
});
