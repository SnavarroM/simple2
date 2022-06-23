const form = document.getElementById('form-login');

function loginSubmit(event) {
    event.preventDefault();
    grecaptcha.ready(function() {
        grecaptcha.execute(RC3_SITE_KEY, { action: 'submit' }).then( function(token) {
        var input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "recaptcha3");
        input.setAttribute("value", token);
        form.appendChild(input);
        form.submit();
        });
    });
}

form.addEventListener('submit', loginSubmit);