//Gestion des erreurs de mot de passe
var form = document.querySelector('#ChangePass form');
form.addEventListener('submit', function (evt) {
  evt.preventDefault();

  var old_pass = form.elements['old_pass'].value;
  var new_pass = form.elements['new_pass'].value;
  var new_pass2 = form.elements['new_pass2'].value;


  var ajax = new XMLHttpRequest();

  var url = "ChangerMdpBD.php";

  ajax.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {

      console.log(ajax.responseText);

      if(ajax.responseText == 'ERROR1'){
        document.getElementById("error").innerHTML = "Veuillez vérifier le nouveau mot de passe!";
        document.getElementById("error").setAttribute("style", "color: red;");
      }
      else if (ajax.responseText == 'ERROR2') {
        document.getElementById("error").innerHTML = "Veuillez vérifier l'ancien mot de passe!";
        document.getElementById("error").setAttribute("style", "color: red;");
      }
      else if (ajax.responseText == 'FAILED') {
        document.getElementById("error").innerHTML = "Veuillez réessayer plus tard...";
        document.getElementById("error").setAttribute("style", "color: yellow;");
      }
      else {
        document.getElementById("error").innerHTML = "Modifications enregistrées avec succès!";
        document.getElementById("error").setAttribute("style", "color: #4682B4;");
      }

    }
  }
  ajax.open("POST", url, true);
  ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  ajax.send('old_pass=' + old_pass + '&new_pass=' + new_pass + '&new_pass2=' + new_pass2);
})
