const select = document.getElementById("payment_method");
const paymentMethodCell = document.querySelector(".table__payment-method");

paymentMethodCell.textContent =
    select.options[select.selectedIndex].text === "選択してください"
        ? ""
        : select.options[select.selectedIndex].text;

select.addEventListener("change", function () {
    paymentMethodCell.textContent =
        this.options[this.selectedIndex].text === "選択してください"
            ? ""
            : this.options[this.selectedIndex].text;

    fetch("/purchase/save-payment-method", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
        body: JSON.stringify({
            payment_method: this.value,
        }),
    });
});
