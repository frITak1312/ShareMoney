const input = document.querySelector('input[type="file"]');
const img = document.querySelector("img");
const isFromDb = !img.src.includes("/default-avatar.png");
const actualSrc = img.src;
const removeButton = document.getElementById("removePhoto");
const returnPhotoButton = document.getElementById("returnPhoto");
console.log("is from db" + isFromDb);
console.log("img :" + actualSrc);

removeButton.addEventListener("click", function () {
    if (img.src.includes("/default-avatar.png")) {
        return;
    }
    img.src = ""; // Nastavení výchozí fotky
    input.value = "";
    document.getElementById("removeAvatar").value = "1"; // Nastavení příznaku pro odebrání fotky
    isFromDb ? returnPhotoButton.classList.remove("d-none") : "";
});

returnPhotoButton.addEventListener("click", function () {
    img.src = actualSrc;
    input.value = "";
    document.getElementById("removeAvatar").value = "0"; // Zrušení příznaku pro odebrání fotky
    returnPhotoButton.classList.add("d-none");
});

input.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
        document.getElementById("removeAvatar").value = "0"; // Zrušení příznaku pro odebrání fotky
        isFromDb ? returnPhotoButton.classList.remove("d-none") : "";
    }
});
