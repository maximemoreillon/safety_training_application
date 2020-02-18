// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var modal_open_button = document.getElementById("modal_open_button");

// Get the <span> element that closes the modal
var modal_close_button = document.getElementById("modal_close_button");

// When the user clicks on the button, open the modal
modal_open_button.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
modal_close_button.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
