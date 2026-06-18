document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("imageInput");
    const previewImage = document.getElementById("previewImage");
    const previewContainer = document.getElementById("previewContainer");
    const uploadPlaceholder = document.getElementById("uploadPlaceholder");
    const removeImage = document.getElementById("removeImage");

    // ページ読み込み時、sessionに一時画像があればプレビュー表示
    const savedImageUrl = document.getElementById("savedImageUrl")?.value;
    if (savedImageUrl) {
        previewImage.src = savedImageUrl;
        previewContainer.style.display = "block";
        uploadPlaceholder.style.display = "none";
    }

    imageInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (!file) return;

        // Ajaxで一時保存
        const formData = new FormData();
        formData.append("image", file);
        formData.append(
            "_token",
            document.querySelector('meta[name="csrf-token"]').content,
        );

        fetch("/sell/upload-temp", {
            method: "POST",
            body: formData,
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success) {
                    // プレビュー表示
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewContainer.style.display = "block";
                        uploadPlaceholder.style.display = "none";
                    };
                    reader.readAsDataURL(file);
                }
            });
    });

    removeImage.addEventListener("click", function () {
        // 一時ファイルを削除
        fetch("/sell/remove-temp", {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
            },
        });

        previewImage.src = "";
        previewContainer.style.display = "none";
        uploadPlaceholder.style.display = "flex";
        imageInput.value = "";
    });
});
