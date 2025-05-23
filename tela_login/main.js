// --------------------------------------------Login:

function removertexto() {
}

function retornarterxto() {
}

function mostrarsenha() {
    var senhaInput = document.getElementById("password");
    if (senhaInput.type === "password") {
        senhaInput.type = "text";
    } else {
        senhaInput.type = "password";
    }
}

document.getElementById("login-form").addEventListener ("submit", function(event){
    event.preventDefault();

    var username = document.getElementById("username");
    var senha = document.getElementById ("password");
    
    if (username === "aa" && password === "aa") {
        alert ("Login Feito com exito")

    } else { 
        alert ("Falha ao fazer o login, verifique os campos.");
        

    };

    

});






