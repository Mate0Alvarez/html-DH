<?php
$opt= [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
$dataBase= new PDO ("mysql:dbname=compumundo_db;host:localhost;port:3306","root","", $opt);

function buscarRespuesta(PDO $db) {
$buscador= trim($_POST["pregunta"]);
$conec=$db->prepare("SELECT respuesta from preguntas where pregunta like '%$buscador%'");
  $conec->execute();
  $res= $conec->fetchAll(PDO::FETCH_ASSOC);
  if($res != null){
    return $res;
  } else {
    $respuestaDefault=[["respuesta"=>"Puede no te esté entendiendo o no esté preparados para responder eso, pero mis colegas geeks de COMPUMUNDO seguramente sí!"]];
    return $respuestaDefault;
}}
$pregunta1="¡Hola! Soy tu Asistente Perosnal S.M.A.R.T. ¿En qué puedo ayudarte?";

if ($_POST) {

  $res=buscarRespuesta($dataBase);
  $respuesta=$res[0]["respuesta"];
  $pregunta=$_POST["pregunta"];;

 }
 // var_dump($res);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>BOT</title>
    <link rel="stylesheet" href="CSS/gal.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Code+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="CSS/fontawesome/css/all.css">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="HandheldFriendly" content="true">
  </head>
  <body>
    <div class="fondo">
      <div class="logo">
        <img src="IMG/compuLogo.png" alt="">
      </div>
      <div class="astros">
        <img src="IMG/bot.PNG" alt="">
      </div>

    </div>

    <div class="chatbot">
      <div class="chatlogs">
        <div class="chat galyleo">
          <div class="user-photo"><img src="IMG/bot.PNG" alt=""></div>
          <p class="chat-mensaje"><?=$pregunta1?></p>
        </div>

        <?php if($_POST){
           ?><div class="chat usuario">
                <div class="user-photo"><img src="IMG/usuario.png" alt=""></div>
                <p class="chat-mensaje"><?=$_POST["pregunta"]?></p>
              </div>
              <div class="chat galyleo">
                <div class="user-photo"><img src="IMG/bot.PNG" alt=""></div>
                <p class="chat-mensaje"><?=$respuesta?></p>
              </div>
              <?php
        } ?>
      </div>
      <div class="chat-form">
        <form class="" id="form" action="hola.php" method="post">
        <textarea id="mensaje" name="pregunta"></textarea>
        <button type="submit" name="button" for="form"><i class="fas fa-chevron-circle-right"></i></button>
        </form>
      </div>
    </div>

  </body>
</html>
