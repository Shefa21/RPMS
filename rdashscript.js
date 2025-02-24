// Event listeners for the buttons
document.getElementById("uploadPaperBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/upload"; // Redirect to Upload page
});

document.getElementById("viewPapersBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/submissions"; // Redirect to My Papers page
});

document.getElementById("collaborateBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/collaborate"; // Redirect to Collaboration page
});

document.getElementById("exploreOpportunitiesBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/opportunities"; // Redirect to Opportunities page
});

document.getElementById("editProfileBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/profile"; // Redirect to Profile page
});

document.getElementById("viewFeedbackBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/researcher/feedback"; // Redirect to Feedback page
});
