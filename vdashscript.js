// Event listeners for the buttons
document.getElementById("viewPapersBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/viewer/papers"; // Redirect to View Research Papers page
});

document.getElementById("exploreOpportunitiesBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/viewer/opportunities"; // Redirect to Research Opportunities page
});

document.getElementById("editProfileBtn").addEventListener("click", () => {
    window.location.href = "/dashboard/viewer/profile"; // Redirect to Profile page
});
