$(document).ready(() => {
    $("#form").submit((event) => {
        event.preventDefault();

        //Проверка формы
        let formValid = true,
            password_equal = $("#password").val() == $("#password_check").val();

        if (!password_equal) {
            formValid = false;
            alert("Извините! Пароли не совпадают.");
            grecaptcha.reset();
        }

        let regx = RegExp("(?=.*[0-9])(?=.*[!-.:-@[-`{-~])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!-.:-@[-`{-~]{8,}", "gm"),
            password_regExp = regx.exec($("#password").val());

        if (!password_regExp) {
            formValid = false;
            alert("Извините! Ваш пароль должен иметь спец.символы, латинские заглавные и прописные буквы.");
            grecaptcha.reset();
        }

        //Проверка капчи
        let captcha = grecaptcha.getResponse();
        if (!captcha.length) {
            $('#recaptchaError').text('* Вы не прошли проверку "Я не робот"');
            $('#recaptchaError').show();
            grecaptcha.reset();
        } else {
            $('#recaptchaError').text('');
            $('#recaptchaError').hide();
        }

        if ((formValid) && (captcha.length)) {
            let formData = new FormData();
            formData.append('firstname', $("#firstname").val());
            formData.append("surname", $("#surname").val())
            formData.append('email', $("#email").val());
            formData.append('login', $("#login").val());
            formData.append('password', $("#password").val());
            formData.append("age", $("#age option:selected").val());
            formData.append("sex", $('input[name=sex]:checked').val())
            formData.append('g-recaptcha-response', captcha);

            $.ajax({
                type: "POST",
                url: "/php/reg.php",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    alert(data);
                    window.location.replace("/auth.html");
                },
                error: function (request) {
                    console.log(request);
                    request = JSON.parse(request["responseText"]);
                    alert(request["result"]);
                    console.log(request);
                    grecaptcha.reset();
                }
            })
        }
    })
})
