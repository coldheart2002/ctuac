//button links
const button = document.getElementById('data');
button.addEventListener('click', () => {
    window.location.href = 'login/others/others_display_data.php';
});


function limitInputId(event) {
    const inputField = document.getElementById("id");
    const inputValue = inputField.value.replace(/\D/g, '').slice(0, 7);
    inputField.value = inputValue;
}

function limitInputContact(event) {
    const inputField = document.getElementById("contact");
    const inputValue = inputField.value.replace(/\D/g, '').slice(0, 11);
    inputField.value = inputValue;
}

window.onload = () => {
    const inputFieldId = document.getElementById("id");
    inputFieldId.addEventListener("input", limitInputId);

    const inputFieldContact = document.getElementById("contact");
    inputFieldContact.addEventListener("input", limitInputContact);
};



// Function to update the state of thse preview button
function updatePreviewButtonState() {
    var firstName = document.getElementById('first_name').value;
    var middleInitial = document.getElementById('middle_initial').value;
    var lastName = document.getElementById('last_name').value;
    var gfirstName = document.getElementById('gfirst_name').value;
    var gmiddleInitial = document.getElementById('gmiddle_initial').value;
    var glastName = document.getElementById('glast_name').value;
    var id = document.getElementById('id').value;
    var contact = document.getElementById('contact').value;
    var course = document.getElementById('course').value;
    var blood = document.getElementById('blood').value;
    var bday = document.getElementById('bday').value;

    var previewButton = document.getElementById('previewButton');

    //firstName !== '' && lastName !== '' && gfirstName !== '' && glastName !== '' && id !== '' && contact !== '' && course !== '' && blood !== '' && bday !== ''
    if (id !== '') {
        if (todayEntries >= 50) { // Check if limit is reached
            previewButton.disabled = true; // Disable the button
        } else {
            previewButton.disabled = false; // Enable the button
        }
    } else {
        previewButton.disabled = true;
    }
}
// Fetch today's entry count from the server
var todayEntries = 0;

fetch("php/get_today_entries.php")
    .then(response => response.json())
    .then(data => {
        todayEntries = parseInt(data.entry_count);
        updatePreviewButtonState(); // Update the button state after fetching data
    })
    .catch(error => {
        console.error("Error fetching today's entry count:", error);
    });

function showPreview() {
    var firstName = document.getElementById('first_name').value;
    var middleInitial = document.getElementById('middle_initial').value;
    var lastName = document.getElementById('last_name').value;
    var gfirstName = document.getElementById('gfirst_name').value;
    var gmiddleInitial = document.getElementById('gmiddle_initial').value;
    var glastName = document.getElementById('glast_name').value;
    var id = document.getElementById('id').value;
    var contact = document.getElementById('contact').value;
    var course = document.getElementById('course').value;
    var blood = document.getElementById('blood').value;
    var bday = document.getElementById('bday').value;

    // Combine First Name, Middle Initial (with dot), and Last Name into 'Name'
    var name = firstName + ' ' + (middleInitial ? middleInitial + '. ' : '') + lastName;
    var gname = gfirstName + ' ' + (gmiddleInitial ? gmiddleInitial + '. ' : '') + glastName;

    var currentDateTime = new Date().toLocaleString(); // Get current date and time

    // Set the value of the hidden datetime input field
    document.getElementById('datetime').value = currentDateTime;

    var previewContent = `
            <h2>Are You Sure?</h2>
            <p><strong>Name:</strong> ${name}</p>
            <p><strong>ID Number:</strong> ${id}</p>
            <p><strong>Contact Number:</strong> ${contact}</p>
            <p><strong>Course:</strong> ${course}</p>
            <p><strong>Blood Type:</strong> ${blood}</p>
            <p><strong>Birthday:</strong> ${bday}</p>
            <p><strong>Guardian:</strong> ${gname}</p>
        `;

    document.getElementById('previewContent').innerHTML = previewContent;
    document.getElementById('overlay').style.display = 'flex';

    // Disable the "Submit" button initially
    document.getElementById('confirmButton').disabled = true;

    // Add event listener to enable "Submit" button when checkbox is checked
    document.getElementById('confirmCheckbox').addEventListener('change', function () {
        document.getElementById('confirmButton').disabled = !this.checked;
    });
}


document.getElementById('confirmButton').addEventListener('click', function () {
    if (document.getElementById('confirmCheckbox').checked) {
        document.forms[0].submit();
        // ...
        // After submission, show the priority number
        var priorityNumber = currentPage.toUpperCase() + '-' + counter;

        document.getElementById('priorityNumber').style.display = 'block';
    }
});

// Function to hide the preview overlay
function hidePreview() {
    document.getElementById('overlay').style.display = 'none';
}

document.getElementById('previewButton').addEventListener('click', showPreview);
document.getElementById('cancelButton').addEventListener('click', hidePreview);
document.getElementById('confirmButton').addEventListener('click', function () {
    document.forms[0].submit();
});
document.getElementById('confirmButton').addEventListener('click', function () {
    // ...
    // After submission, show the priority number
    var priorityNumber = currentPage.toUpperCase() + '-' + counter;

    document.getElementById('priorityNumber').style.display = 'block';
});

// Add event listeners for input changes to update the preview button state
document.getElementById('first_name').addEventListener('input', updatePreviewButtonState);
document.getElementById('middle_initial').addEventListener('input', updatePreviewButtonState);
document.getElementById('last_name').addEventListener('input', updatePreviewButtonState);
document.getElementById('gfirst_name').addEventListener('input', updatePreviewButtonState);
document.getElementById('gmiddle_initial').addEventListener('input', updatePreviewButtonState);
document.getElementById('glast_name').addEventListener('input', updatePreviewButtonState);
document.getElementById('id').addEventListener('input', updatePreviewButtonState);
document.getElementById('contact').addEventListener('input', updatePreviewButtonState);
document.getElementById('course').addEventListener('input', updatePreviewButtonState);
document.getElementById('blood').addEventListener('input', updatePreviewButtonState);
document.getElementById('bday').addEventListener('input', updatePreviewButtonState);


// Initial update of the preview button state
updatePreviewButtonState();
