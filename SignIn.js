//Gestion des erreurs d'authentification
var form1 = document.querySelector('#SignIn form');
form1.addEventListener('submit', function (evt1) {
  evt1.preventDefault();

  var email = form1.elements['email'].value;
  var pass = form1.elements['pass'].value;

  if(email == "" || pass == ""){
    document.getElementById("erreur").innerHTML = "Veuillez remplir les champs!";
    document.getElementById("erreur").setAttribute("style", "color: red; margin-left:100px;");
  }

  else{
    var ajax1 = new XMLHttpRequest();

    var url1 = "SignIn.php";

    ajax1.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {

        console.log(ajax1.responseText);

        if(ajax1.responseText == 'ERROR'){
          document.getElementById("erreur").innerHTML = "Veuillez vérifier le mail ou le mot de passe!";
          document.getElementById("erreur").setAttribute("style", "color: red; margin-left:100px;")
        }
        else if (ajax1.responseText == 'FAILED') {
          document.getElementById("erreur").innerHTML = "Echec de l'authentification!";
          document.getElementById("erreur").setAttribute("style", "color: orange; margin-left:100px;")
        }
        else{
          window.location="UserPage.php";
        }

      }
    }
    ajax1.open("POST", url1, true);
    ajax1.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
    ajax1.send('email=' + email + '&pass=' + pass);
  }

})
