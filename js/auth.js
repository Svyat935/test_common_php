$(document).ready(() => {

    $("#form").submit((event) => {
        event.preventDefault();

        let formData = new FormData();
        formData.append('credentials', $("#credentials").val());
        formData.append('password', $("#password").val());

        $.ajax({
            type: "POST",
            url: "/php/auth.php",
            data: formData,
            contentType: false,
            processData: false,
            cache: false,
            success: function (data) {
                alert("Вы авторизовались! Добро пожаловать!");
                window.location.replace("/profile.html");
            },
            error: function (request) {
                request = JSON.parse(request["responseText"]);
                alert(request["result"])
                console.log(request);
            }
        })

    })
})
