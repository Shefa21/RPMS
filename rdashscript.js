document.addEventListener("DOMContentLoaded", function () {
    // Button navigation setup
    const pages = {
        viewPapersBtn: "management.php",
        collaborateBtn: "collaborate.html",
        exploreOpportunitiesBtn: "opportunities.html",
        // Remove viewFeedbackBtn from here since we handle it via AJAX
    };

    // Setting up navigation buttons
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

    // JavaScript to handle feedback display when clicking 'View Feedback' button
    const feedbackButtons = document.querySelectorAll('.view-feedback-btn');

    feedbackButtons.forEach(button => {
        button.addEventListener('click', function () {
            const paperId = this.dataset.paperId;

            // Make an AJAX request to fetch feedback for the selected paper
            fetch('fetch_feedback.php?paper_id=' + paperId)
                .then(response => response.json())
                .then(data => {
                    const feedbackList = document.getElementById('feedbackList');
                    feedbackList.innerHTML = ''; // Clear any existing feedback

                    if (data.length === 0) {
                        feedbackList.innerHTML = '<li>No feedback available.</li>';
                    } else {
                        data.forEach(feedback => {
                            const li = document.createElement('li');
                            li.innerHTML = `<strong>${feedback.full_name}:</strong> ${feedback.feedback}`;
                            feedbackList.appendChild(li);
                        });
                    }

                    // Show feedback section
                    document.getElementById('feedbackSection').style.display = 'block';
                })
                .catch(error => console.error('Error fetching feedback:', error));
        });
    });
});
