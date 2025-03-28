document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const paperId = urlParams.get("paper_id");

    if (paperId) {
        document.getElementById("paper_id").value = paperId;

        // Fetch existing paper details
        fetch(`get_paper.php?paper_id=${paperId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById("title").value = data.title;
                document.getElementById("abstract").value = data.abstract;
                document.getElementById("category").value = data.category;

                // Populate authors
                let authorFields = document.getElementById("authorFields");
                authorFields.innerHTML = ""; // Clear existing fields

                let authors = data.authors.split(", ");
                authors.forEach((author, index) => {
                    let newAuthorDiv = document.createElement("div");
                    newAuthorDiv.classList.add("author-input");

                    let newAuthorInput = document.createElement("input");
                    newAuthorInput.type = "text";
                    newAuthorInput.name = "authors[]";
                    newAuthorInput.value = author;
                    newAuthorInput.required = true;

                    let removeButton = document.createElement("button");
                    removeButton.innerText = "Remove";
                    removeButton.type = "button";
                    removeButton.classList.add("remove-author");
                    removeButton.addEventListener("click", function () {
                        authorFields.removeChild(newAuthorDiv);
                    });

                    newAuthorDiv.appendChild(newAuthorInput);
                    newAuthorDiv.appendChild(removeButton);
                    authorFields.appendChild(newAuthorDiv);
                });

                // Populate date fields (day, month, and year)
                let publicationDate = new Date(data.publication_date);
                document.getElementById("day").value = publicationDate.getDate();
                document.getElementById("year").value = publicationDate.getFullYear();
                document.getElementById("month").value = publicationDate.getMonth() + 1; // JavaScript months are 0-11
            })
            .catch(error => console.error("Error fetching paper details:", error));
    }

    // Add new author field
    document.getElementById("addAuthor").addEventListener("click", function () {
        let authorFields = document.getElementById("authorFields");

        // Check for duplicate authors before adding new input
        let existingAuthors = Array.from(authorFields.getElementsByTagName("input")).map(input => input.value.trim());
        
        let newAuthorInputValue = prompt("Enter new author's name:");
        if (newAuthorInputValue && !existingAuthors.includes(newAuthorInputValue.trim())) {
            let newAuthorDiv = document.createElement("div");
            newAuthorDiv.classList.add("author-input");

            let newAuthorInput = document.createElement("input");
            newAuthorInput.type = "text";
            newAuthorInput.name = "authors[]";
            newAuthorInput.value = newAuthorInputValue;
            newAuthorInput.required = true;

            let removeButton = document.createElement("button");
            removeButton.innerText = "Remove";
            removeButton.type = "button";
            removeButton.classList.add("remove-author");

            removeButton.addEventListener("click", function () {
                authorFields.removeChild(newAuthorDiv);
            });

            newAuthorDiv.appendChild(newAuthorInput);
            newAuthorDiv.appendChild(removeButton);
            authorFields.appendChild(newAuthorDiv);
        } else {
            alert("This author is already in the list or input is empty.");
        }
    });

    // Populate the day, month, and year dropdowns dynamically
    let daySelect = document.getElementById("day");
    let monthSelect = document.getElementById("month");
    let yearSelect = document.getElementById("year");

    // Populate days
    for (let i = 1; i <= 31; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        daySelect.appendChild(option);
    }

    // Populate months (1-12 for January to December)
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    months.forEach((month, index) => {
        let option = document.createElement("option");
        option.value = index + 1; // Month value should be 1 to 12
        option.textContent = month;
        monthSelect.appendChild(option);
    });

    // Populate years dynamically (current year and earlier)
    let currentYear = new Date().getFullYear();
    for (let i = currentYear; i >= 1900; i--) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        yearSelect.appendChild(option);
    }
});
