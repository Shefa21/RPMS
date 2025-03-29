document.addEventListener("DOMContentLoaded", function () {
    // Button navigation setup
    const pages = {
        addConferenceBtn: "add_conference.php", // Ensure this page exists and is correctly linked

        // Add more button-page mappings as needed
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
    fetch("session.php")
        .then(response => response.json())
        .then(data => {
            console.log("Session data:", data); // Log the session data response

            const authLink = document.getElementById("authLink");
            if (data.loggedIn) {
                authLink.innerHTML = `<a href="logout.php" class="logout-btn">Logout</a>`; // Change to logout if logged in
            } else {
                authLink.innerHTML = `<a href="login_form.php" class="login-btn">Login</a>`; // Show login if not logged in
            }
        })
        .catch(error => console.error("Error fetching login status:", error));
});
