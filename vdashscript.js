document.addEventListener("DOMContentLoaded", function () {
    // Fetch the session status (logged in or not)
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

    // Save Paper Button
    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', function() {
            const paperId = this.closest('.paper').dataset.id;
            fetch('save_paper.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `paper_id=${paperId}`
            })
            .then(response => response.text())
            .then(data => alert(data)) // Display the response (success or error message)
            .catch(error => console.error('Error saving paper:', error));
        });
    });

    // Follow Researcher Button
    document.querySelectorAll('.follow-btn').forEach(button => {
        button.addEventListener('click', function() {
            const researcherId = this.closest('.paper').dataset.researcherId;
            fetch('follow_researcher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `researcher_id=${researcherId}`
            })
            .then(response => response.text())
            .then(data => alert(data)) // Display the response (success or error message)
            .catch(error => console.error('Error following researcher:', error));
        });
    });

    // Recommend Paper Button
    document.querySelectorAll('.recommend-btn').forEach(button => {
        button.addEventListener('click', function() {
            const paperId = this.closest('.paper').dataset.id;
            fetch('recommended_paper.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `paper_id=${paperId}`
            })
            .then(response => response.text())
            .then(data => alert(data)) // Display the response (success or error message)
            .catch(error => console.error('Error recommending paper:', error));
        });
    });

    // Share Paper Button
    document.querySelectorAll('.share-btn').forEach(button => {
        button.addEventListener('click', function() {
            const paperId = this.closest('.paper').dataset.id;
            fetch('share_paper.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `paper_id=${paperId}`
            })
            .then(response => response.text())
            .then(data => alert(data)) // Display the share action response (success or link)
            .catch(error => console.error('Error sharing paper:', error));
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    // Feedback Button
    document.querySelectorAll('.feedback-btn').forEach(button => {
        button.addEventListener('click', function() {
            const paper_id = this.closest('.paper').dataset.id;
            const feedback = prompt('Please provide your feedback for this paper:'); // Simple prompt to capture feedback

            if (feedback) {
                fetch('submit_feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `paper_id=${paper_id}&feedback=${encodeURIComponent(feedback)}`
                })
                .then(response => response.text())
                .then(data => alert(data)) // Display response message
                .catch(error => console.error('Error:', error));
            }
        });
    });
});
