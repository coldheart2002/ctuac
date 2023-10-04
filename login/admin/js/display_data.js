
function updateStatus(selectElement) {
    const id = selectElement.parentNode.getAttribute('data-id');
    const field = selectElement.parentNode.getAttribute('data-field');
    const newValue = selectElement.value;

    // Update the database immediately
    updateDatabase(id, field, newValue);

    // Reload the page after updating
    window.location.reload();
}


function updateDatabase(id, field, newValue) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_status.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.send(`id=${id}&field=${field}&value=${newValue}`);
}
