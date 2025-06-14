document.addEventListener("DOMContentLoaded", function () {
    const checkbox = document.getElementById("formCheck-2");
    const accounts = document.querySelectorAll(".col[data-flag]");
    const noAdminMsg = document.getElementById("no-admin-accounts-msg");

    checkbox.addEventListener("change", function () {
        let visibleAdminCount = 0;

        accounts.forEach((account) => {
            const isAdmin = account.getAttribute("data-flag") === "admin";

            if (this.checked) {
                if (!isAdmin) {
                    account.classList.add("d-none");
                } else {
                    account.classList.remove("d-none");
                    visibleAdminCount++;
                }
            } else {
                account.classList.remove("d-none");
            }
        });

        if (this.checked && visibleAdminCount === 0) {
            noAdminMsg.classList.remove("d-none");
            noAdminMsg.classList.add("d-block");
        } else {
            noAdminMsg.classList.remove("d-block");
            noAdminMsg.classList.add("d-none");
        }
    });
});
