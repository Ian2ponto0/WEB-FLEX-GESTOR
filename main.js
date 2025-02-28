// --------------------------------------------Login:

function removertexto() {
    event.target.value = ""; 

    const a = document.querySelectorAll ("username");
    const b = document.querySelectorAll("password");
    while (a === "") {
        a.forEach = "Usuário";
        (a => {
            a.value = ""; // 
          });
    };
    while (b === "") {
        b.forEach = "Senha";
        (b => {
            b.value = ""; 
          });
    };
};




document.getElementById("login-form").addEventListener ("submit", function(event){
    event.preventDefault();

    var username = document.getElementById("username");
    var senha = document.getElementById ("password");
    
    if (username === "aa" && password === "aa") {
        alert ("Login Feito com eixo")

    } else { 
        aler ("Falha ao fazer o login, verifique os campos.");
        

    };

});






