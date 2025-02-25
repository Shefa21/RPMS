document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("addAuthor").addEventListener("click", function() {
        let authorFields = document.getElementById("authorFields");

        // Create a new div for the additional author input
        let newAuthorDiv = document.createElement("div");
        newAuthorDiv.classList.add("author-input");

        // Create a new input field for the author name
        let newAuthorInput = document.createElement("input");
        newAuthorInput.type = "text";
        newAuthorInput.name = "authors[]";
        newAuthorInput.placeholder = "Enter Another Author Name";
        newAuthorInput.required = true;

        // Create a remove button
        let removeButton = document.createElement("button");
        removeButton.innerText = "Remove";
        removeButton.type = "button";
        removeButton.classList.add("remove-author");

        // Add event listener to remove button
        removeButton.addEventListener("click", function() {
            authorFields.removeChild(newAuthorDiv);
        });

        // Append the input and remove button to the div
        newAuthorDiv.appendChild(newAuthorInput);
        newAuthorDiv.appendChild(removeButton);

        // Append the new div to the author fields container
        authorFields.appendChild(newAuthorDiv);
    });
});