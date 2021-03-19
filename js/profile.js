$(document).ready(() => {
    let formData = new FormData();
    formData.append('credentials', $("#login").val());
    formData.append('password', $("#password").val());

    $.ajax({
        type: "POST",
        url: "/php/profile.php",
        data: null,
        contentType: false,
        processData: false,
        cache: false,
        success: function (request) {
            request = JSON.parse(request);

            let info = request["result"];
            for(let element in info["profile"]){
                let ph = document.createElement("p");
                ph.textContent = `${element} = ${info["profile"][element]}`;
                $("#profile").append(ph);
            }
            for(let element in info["statistics"]){
                let ph = document.createElement("p");
                ph.textContent = `${element} = ${info["statistics"][element]}`;
                $("#profile").append(ph);
            }

            $("#header").text(`Добро пожаловать, ${info["profile"]["firstname"]}`);

            console.log(request);
        },
        error: function (request) {
            request = JSON.parse(request["responseText"]);
            alert(request["result"]);
            console.log(request);
        }
    })

    $("#form").submit((event) => {
        event.preventDefault();

        if ($("#search").val() != ""){
            let formData = new FormData();
            formData.append('usersearch', $("#search").val());

            $.ajax({
                type: "POST",
                url: "/php/search.php",
                data: formData,
                contentType: false,
                processData: false,
                cache: false,
                success: function (data) {
                    data = JSON.parse(data);
                    alert(data["result"]);
                }
            })
        }else{
            alert("Введите текст для поиска");
        }
    })
})
