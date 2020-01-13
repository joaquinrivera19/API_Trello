<!DOCTYPE html>
<html lang="en">
<head>
  <title>Exportacion Trello</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


  <script src="https://api.trello.com/1/client.js?key=5dac96962ab254dc70b5e897f6b7e4b7"></script>


  <style type="text/css">
  

  body {
    font-family: arial;
    font-size: 12px;
  }

  .menu_listado {
    text-align: center;
    font-size: 20px;
    padding-top: 30px;
  }

  #loggedin {
    /*display: none;*/
  }

  #header {
    padding: 4px;
    border-bottom: 1px solid #000;
    background: #eee;
  }

  #output {
    padding: 4px;
  }

  .card {
    display: block;
    padding: 2px;
  }



</style>

</head>
<body>

  <div class="container" style="padding: 15px; width: 100%" >

    <div class="menu_login">
      <a id="href_login_trello" href="#"> Ingresar con tu cuenta de Trello </a>
    </div>

    <div class="menu_listado">
      <a id="connectLink1" href="#">Ver mi listado de tareas de Trello</a></br>
      <!-- <a id="connectLink2" href="#">Ver listado de tareas de Trello a Historial Enero </a>  -->
      Ver listado de tareas del DashBoard Historial: 
      <select id="list"></select>
      </br>
      <a id="disconnect" href="#">Log Out</a>
    </div>

    <div class="loggedin">
      <div id="header">
        Ingreso con  <span id="fullName"></span>
      </div>

      <div id="list_me_id"></div>

      <table>
        <thead>
            <tr>
                <th>Select</th>
                <th>Nombre Tarjeta</th>
                <th>Label</th>
            </tr>
        </thead>
        <tbody id="list_table">
        </tbody>

    </table>



    </div>




  </div>

</body>


<script type="text/javascript">

var list_me = "members/me/cards";
//var list_card = "lists/5bf807efc378ee2da334b721/cards"; //Listado: Mover a produccion

var list_card = "lists/5c082d902a234a1b4f58543d/cards"; // Listado: Enero

var list_board = "/boards/5c082d902a234a1b4f58543a/lists"; // boar Historial

$("#connectLink1").click(function(){

  $("#list_me_id").empty();
  $("#list_table").empty();

  onAuthorize(list_me);

});

$("#connectLink2").click(function(){

  $("#list_me_id").empty();
  $("#list_table").empty();

  onAuthorize(list_card);

});

$("#list").change(function(){
    var list_selected = $('select[id=list]').val();

     $("#list_me_id").empty();
     $("#list_table").empty();

//"lists/5c082d902a234a1b4f58543d/cards";

    console.log(list_selected);

    var list_card = "lists/" + list_selected +"/cards";

    onAuthorize(list_card);

});


$("#href_login_trello").click(function(){

  Trello.authorize({
    type: 'popup',
    name: 'Getting Started Application',
    scope: {
      read: 'true',
      write: 'true' },
      expiration: 'never',
      success: updateLoggedIn
    })

});

var onAuthorize = function(parameter) {
  updateLoggedIn();

  $(".loggedin").show();

  var tarjeta = $("<div>").text("Loading Cards...").appendTo("#list_me_id");
  var list_label = [];
  var element = "";
  
  Trello.get(parameter, function(cards) {
    tarjeta.empty();
    $.each(cards, function(ix, card) {

      console.log(card);

      if (element != card.id){

        $.each(card.idLabels, function(id, label){
          Trello.get("labels/" + label, function(lab) {

          //console.log(lab.color);

            //$(".label_card").append(lab.name);
          });

        });

      } else {

      }

      element = card.id;




      console.log(list_label);

      tarjeta ="<tr><td class='label_card'>ssss</td><td><a href='"+ card.url +"' target='_blank'>" + card.name + "</a></td><td>tercer elemento</td></tr>";
      $("table tbody").append(tarjeta);

    });
  });

};


var updateLoggedIn = function() {
  var isLoggedIn = Trello.authorized();
  //$("#loggedout").toggle(!isLoggedIn);
  //$("#loggedin").toggle(isLoggedIn);

  console.log(isLoggedIn);

  if(isLoggedIn){

    Trello.members.get("me", function(member) {
      $("#fullName").text(member.fullName);
    });


    $("#list").empty();

    Trello.get(list_board, function(cards) {

      $("#list").append('<option value="Seleccione una opción"></option>');

      $.each(cards, function(ix, list) {
        $("#list").append('<option value='+list.id+'>'+list.name+'</option>');
      });
    });


    $(".menu_login").hide();
    $(".menu_listado").show();
    $(".loggedin").show();
  } else {
    $(".menu_login").show();
    $(".menu_listado").hide();
    $(".loggedin").hide();
  }

  // si es true esta logueado
  // es es false no esta logueado
};

var logout = function() {

  console.log('ads');

  Trello.deauthorize();
  updateLoggedIn();
};

$(document).ready(function(){
  updateLoggedIn();
});

$("#disconnect").click(logout);


</script>

</html>