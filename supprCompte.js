var a = document.getElementById('delete');
a.addEventListener("click",
        function (event) {
            event.preventDefault();
            if (confirm('Etes-vous sûre?')) {
              var ajax = new XMLHttpRequest();

              var url = "supprCompte.php";

              ajax.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                  console.log(ajax.responseText);
                  if(ajax.responseText == "SUCCESS"){
                    window.location = "MainPage.html";
                  }
                  else{
                    window.alert("Veuillez réessayer plus tard!")
                  }


                }
              }
              ajax.open("POST", url, true);
              ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
              ajax.send();
            }
          },false);
