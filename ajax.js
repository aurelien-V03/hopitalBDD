 // function AJAX pour supprimer un document 
      function deleteDoc(numDocToDelete){
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
          console.log(xmlhttp.readyState);
          if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            alert("Document N° " + numDocToDelete + " supprimé ");
            // refresh la page après 1 secondes
            setTimeout(function(){ document.location.reload();}, 1000);
          }
        };
        xmlhttp.open("GET", "deleteDocument.php?numDocDelete=" + numDocToDelete, true);
        xmlhttp.send();
      }