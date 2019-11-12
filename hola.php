<?php

$dataBase= new PDO ("mysql:dbname=planetariodb;host:localhost;port:3306","root","");

function buscarRespuesta(PDO $db) {
$buscador= $_POST["pregunta"];
$conec=$db->prepare("SELECT respuesta from preguntas where pregunta like '%$buscador%'");
  $conec->execute();
  $res= $conec->fetchAll(PDO::FETCH_ASSOC);
  if($res != null){
    return $res;
  } else {
    $respuestaDefault=[["respuesta"=>"Puede no te estemos entendiendo o no estamos preparados para responder eso, pero seguro nuestros colegas del Planetario sí!"]];
    return $respuestaDefault;
}}
$pregunta1="Hola! Nosotros somos GAL y LEO ¿En qué podemos ayudarte?";
$respuestaBase="
¿Te gustaría conocer más? ¡Visitanos!
¿Te contamos acerca de los próximos eventos?";

if ($_POST) {

  $res=buscarRespuesta($dataBase);
  $respuesta=$res[0]["respuesta"].$respuestaBase;
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
    <link rel="stylesheet" href="CSS/fontawesome/css/all.css">
  </head>
  <body>
    <div class="chatbot">
      <div class="chatlogs">
        <div class="chat galyleo">
          <div class="user-photo"><img src="IMG/galyleo.jpg" alt=""></div>
          <p class="chat-mensaje"><?=$pregunta1?></p>
        </div>

        <?php if($_POST){
           ?><div class="chat usuario">
                <div class="user-photo"><img src="IMG/usuario.png" alt=""></div>
                <p class="chat-mensaje"><?=$_POST["pregunta"]?></p>
              </div>
              <div class="chat galyleo">
                <div class="user-photo"><img src="IMG/galyleo.jpg" alt=""></div>
                <p class="chat-mensaje"><?=$respuesta?></p>
              </div>
              <?php
        } ?>
      </div>
      <div class="chat-form">
        <form class="" action="hola.php" method="post">


        <textarea id="mensaje" name="pregunta"></textarea>
        <button type="submit" name="button" for="mensaje"><i class="fas fa-chevron-circle-right"></i></button>
        </form>
      </div>
    </div>
    <div class="astros">
      <img src="IMG/galyleo2.png" alt="">
    </div>
    <div class="planetario">
      <img src="IMG/planetario.png" alt="">
    </div>
  </body>
</html>
