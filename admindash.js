// Event listeners for the buttons
document.getElementById("manageResearchersBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/admin/manage-researchers"; // Redirect to Manage Researchers page
});

document.getElementById("managePapersBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/admin/manage-papers"; // Redirect to Manage Papers page
});

document.getElementById("viewFeedbackBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/admin/view-feedback"; // Redirect to View Feedback page
});

document.getElementById("settingsBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/admin/settings"; // Redirect to Settings page
});
