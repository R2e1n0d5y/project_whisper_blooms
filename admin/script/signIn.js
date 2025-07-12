document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("loginForm").addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        fetch("../config/signIn.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            let messageDiv = document.getElementById("loginMessage");
            messageDiv.textContent = data.message;
            messageDiv.style.color = data.status === "success" ? "green" : "red";

            if (data.status === "success") {
                setTimeout(() => {
                    window.location.href = "../html/dashboard.php";
                }, 1000);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
