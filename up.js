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

    
    // Populate years (2020 to 2030)
    const yearSelect = document.getElementById("year");
    for (let i = 2020; i <= 2030; i++) {
        let option = document.createElement("option");
        option.value = i;
        option.textContent = i;
        yearSelect.appendChild(option);
    }

    // Date validation: Handle months with fewer than 31 days
    const monthSelectElement = document.getElementById("month");
    const daySelectElement = document.getElementById("day");

    monthSelectElement.addEventListener("change", function() {
        validateDaySelection();
    });

    function validateDaySelection() {
        const month = parseInt(monthSelectElement.value, 10);
        const year = parseInt(yearSelect.value, 10);
        let maxDays = 31;

        if (month === 2) {
            maxDays = (isLeapYear(year)) ? 29 : 28;  // Leap year check
        } else if ([4, 6, 9, 11].includes(month)) {
            maxDays = 30;  // Months with 30 days
        }

        // Remove days that are not valid for the selected month
        for (let i = daySelectElement.options.length - 1; i >= 0; i--) {
            let option = daySelectElement.options[i];
            if (parseInt(option.value, 10) > maxDays) {
                daySelectElement.removeChild(option);
            }
        }

        // Add any missing days to the dropdown
        for (let i = daySelectElement.options.length; i <= maxDays; i++) {
            let option = document.createElement("option");
            option.value = i + 1;
            option.textContent = i + 1;
            daySelectElement.appendChild(option);
        }
    }

    function isLeapYear(year) {
        return (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0));
    }
});
