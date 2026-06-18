document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("profile_image_path");
    const previewImg = document.getElementById("preview-image");

    input.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImg.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
});
