document.getElementById('buy-now-form').addEventListener('submit', function(event) {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let address = document.getElementById('address').value;
    let quantity = document.getElementById('quantity').value;

    if (name === '' || email === '' || address === '' || quantity <= 0) {
        alert("Please fill in all fields correctly.");
        event.preventDefault();
    }
});
