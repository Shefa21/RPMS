document.addEventListener("DOMContentLoaded", function () {
    fetch("session.php")
        .then(response => response.json())
        .then(data => {
            const authLink = document.getElementById("authLink");

            if (data.loggedIn) {
                authLink.innerHTML = '<a href="logout.php" class="logout-btn">Logout</a>';
            } else {
                authLink.innerHTML = '<a href="login_form.php" class="login-btn">Login</a>';
            }
        })
        .catch(error => console.error("Error fetching session status:", error));
});
