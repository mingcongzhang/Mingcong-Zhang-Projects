var counter = 1;
var limit = 10;
function addInput(divName){
     if (counter == limit)  {
          alert("You have reached the limit of adding " + counter + " inputs");
     }
     else {
          var newdiv = document.createElement('div');
          newdiv.innerHTML = "New Group " + (counter + 1) + " <br><input type='text' name='selectgroups[]'>";
          document.getElementById(divName).appendChild(newdiv);
          counter++;
     }
}
