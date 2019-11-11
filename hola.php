<?php

$dataBase= new PDO ("mysql:dbname=planetariodb;host:localhost;port:3306","root","");

function buscarRespuesta(PDO $db) {
$buscador= $_POST["pregunta"];
$conec=$db->prepare("SELECT respuesta from preguntas where pregunta like '%$buscador%'");
  $conec->execute();
  $res= $conec->fetch(PDO::FETCH_ASSOC);
  if($res === ""){
    $respuestaDefault="Puede que no estamos preparados para responder eso, pero seguro nuestros colegas del Planetario sí! ¿Querés que te contemos acerca de los próximos eventos?";
    return $respuestaDefault;
  } else {
  return $res;
}}
$pregunta="Hola! Nosotros somos GALyLEO ¿En qué podemos ayudarte?";
$respuesta="   ";
$respuestaBase="
¿Te gustaría conocer más? ¡Visitanos!
¿Te contamos acerca de los próximos eventos?";

if ($_POST) {

  $res=buscarRespuesta($dataBase);
  $respuesta=$res["respuesta"];
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
  </head>
  <body>
    <form class="" action="hola.php" method="post">
      <div class="pregunta">
        <h3><?=$pregunta?><br></h3>
      </div>
      <div class="respuesta">
          <h3><?=$respuesta?><br></h3>
      </div>

          <input type="text" name="pregunta" value="">
          <br>

          <input type="submit" name="" value="enviar">
    </form>
    <div class="astros">
      <img src="IMG/galyleo.jpg" alt="">
    </div>
  </body>
</html>
