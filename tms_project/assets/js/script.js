function validateRegister() {
    let mobile = document.getElementsByName("mob")[0].value;
    if (mobile.length != 10) {
        alert("Mobile number must be 10 digits");
        return false;
    }
    return true;
}

function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}