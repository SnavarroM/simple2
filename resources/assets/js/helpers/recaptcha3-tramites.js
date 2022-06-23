function setRecaptchaToken(site_key, form, grecaptcha) {
    grecaptcha.ready(function() {
        grecaptcha.execute(site_key, { action: 'homepage' }).then( function(token) {
            let input = document.getElementById('re-captcha-3');
            if(null == input) {
                input = document.createElement("input");
                input.setAttribute("type", "hidden");
                input.setAttribute("id", "re-captcha-3");
                input.setAttribute("name", "recaptcha3");
            }
            input.setAttribute("value", token);
            form.prepend(input);
        });
    });
}

(function() {
    const initRc3 = setInterval(setRecaptcha3, 1000);
    function setRecaptcha3() {
        const form = document.getElementById('tramite-step-form');
        if (null != form) {
            grecaptcha.ready(function() {
                setRecaptchaToken(RC3_SITE_KEY, form, grecaptcha);
                setInterval(() => setRecaptchaToken(RC3_SITE_KEY, form, grecaptcha), (1000*120) );
            });
            clearInterval(initRc3);
        }
    }
})();