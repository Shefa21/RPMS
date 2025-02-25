document.getElementById("addAuthor").addEventListener("click", function() {
    let authorFields = document.getElementById("authorFields");

   
    let newAuthorDiv = document.createElement("div");
    let newAuthorInput = document.createElement("input");
    newAuthorInput.type = "text";
    newAuthorInput.name = "authors[]";
    newAuthorInput.placeholder = "Enter Another Author Name";
    newAuthorInput.required = true;


    let removeButton = document.createElement("button");
    removeButton.innerText = "Remove";
    removeButton.type = "button";
    removeButton.classList.add("remove-author");

    removeButton.addEventListener("click", function() {
        authorFields.removeChild(newAuthorDiv);
    });

 
    newAuthorDiv.appendChild(newAuthorInput);
    newAuthorDiv.appendChild(removeButton);

    authorFields.appendChild(newAuthorDiv);
});

document.addEventListener("DOMContentLoaded", function() {
    // Populate days (1 to 31)
    const daySelect = document.getElementById("day");
    for (let i = 1; i <= 31; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        daySelect.appendChild(option);
    }

    
    const yearSelect = document.getElementById("year");
    for (let i = 2020; i <= 2030; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        yearSelect.appendChild(option);
    }
});