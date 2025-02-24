document.getElementById("flip-to-signup").addEventListener("click", function () {
    document.querySelector(".container").classList.add("flip");
});

document.getElementById("flip-to-signin").addEventListener("click", function () {
    document.querySelector(".container").classList.remove("flip");
});
