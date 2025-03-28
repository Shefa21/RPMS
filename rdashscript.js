document.addEventListener("DOMContentLoaded", function () {
    // Button navigation setup
    const pages = {
        viewPapersBtn: "management.php",
        collaborateBtn: "collaborate.html",
        exploreOpportunitiesBtn: "opportunities.html",
        viewFeedbackBtn: "feedback.html"
    };

    Object.keys(pages).forEach(btnId => {
        const button = document.getElementById(btnId);
        if (button) {
            button.addEventListener("click", () => {
                window.location.href = pages[btnId];
            });
        }
    });

    // Handle Login/Logout dynamically using PHP session check
    fetch("session.php") // Fetch login status from PHP
        .then(response => response.json())
        .then(data => {
            const authLink = document.getElementById("authLink");
            if (data.loggedIn) {
                authLink.innerHTML = `<a href="logout.php" class="logout-btn">Logout</a>`;
            } else {
                authLink.innerHTML = `<a href="login_form.php" class="login-btn">Login</a>`;
            }
        })
        .catch(error => console.error("Error fetching login status:", error));
});
