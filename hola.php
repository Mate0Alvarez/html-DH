<?php
$pregunta="Hola! Mi nombre es N.A.Z.A. ¿En qué puedo ayudarte?";
$respuesta="respuesta";
$preguntas=["hola","alvarez alvalos carretero yudhack","Soñaba con ser astronauta desde chico?","Es difícil convertirse en astronauta?","Qué se siente estar en el espacio?","Qué necesita un joven para convertirse en astronauta?","Pueden volar los pájaros en el espacio?","Tiene final el espacio?","¿Cómo se creó el espacio?","Cuánto pesa un transbordador espacial?","¿Se pueden ver las estrellas desde el espacio?","A qué velocidad se mueve la estación espacial Internacional?","temperatura en el espacio exterior","Qué es la zona Ricitos de Oro?","Qué hay en la órbita terrestre?","Cuánto tiempo se tarda en dar una vuelta completa a la Tierra?","¿Por qué Marte se llama así","Qué es un cosmonauta","Se envejece en el espacio","Qué es una sonda espacial","Marte tiene gravedad","Dónde está el Centro Espacial Kennedy?","A qué velocidad máxima puede ir un transbordador espacial?","Qué es el espacio-tiempo?",
"Podemos vivir en Marte?", "A qué distancia está el espacio?","Por qué el espacio es negro?","Quién fue la primera mujer en el espacio?","Dónde está el cinturón de asteroides?","Cuándo se descubrió Marte?","Qué es una órbita?","Se puede ver la Gran Muralla China desde el espacio a simple vista?","Se puede ver Marte desde la Tierra?","Quién fue el primer estadounidense en el espacio?","Marte tiene atmósfera?","Quién fue el primer ser humano en llegar al espacio","Cuánto se tarda en llegar al espacio?","Dónde está la Estación Espacial Internacional?","Cuánto dura un año marciano?","Es Marte más grande que la Tierra?","Por qué Marte es rojo?","Cuántos satélites hay alrededor de la Tierra?","Es el espacio un vacío?","Cuál es la temperatura en Marte?","Se pueden oír sonidos en el espacio?","Qué es un asteroide","Hay vida en Marte? ","Cuántas lunas tiene Marte?","Qué significa NASA?",
"Cuánto se tarda en llegar a Marte?"];
$respuestas=["Creo que no estoy preparado para responder eso, pero seguro mis colegas del Planetario lo sí! ¿Querés que te cuente acerca de los próximos eventos?","Me di cuenta de que quería ser astronauta cuando era muy joven. ¡Me estaban programando y lo supe! ",
"Para nada! Sólo tenés que venir al Planetario de Buenos Aires y sentir lo que es ser un verdadero astronauta. ",
"Estar en el espacio es algo que todos deberían vivir, te cambia la forma de pensar. ",
"Estudiar, entrenamiento físico, visitar el Planetario de Buenos Aires, entre otras cosas. ",
"No. ","Asumo que no, pero en realidad nadie lo sabe. ",
"Según la teoría actual, en una gran explosión llamada Big Bang. ",
"2.041 toneladas métricas (2.041.065 kilos) en el despegue. ","Sí.","28.163 km/h.","-270 grados Celsius, pero no significa mucho porque es en vacío. ",
"Ricitos de Oro es la zona de habitabilidad alrededor de una estrella en la que la temperatura no es ni muy caliente ni muy fría. Se llama así en honor al cuento. ",
"La Luna, la Estación Espacial Internacional y alrededor de 1.700 satélites","Depende de la altura, la Luna da una vuelta completa cada 27 días, la ISS cada 90 minutos. ",
"En honor del dios griego de la guerra, Marte, probablemente por su tonalidad rojiza. Los romanos nombraron los cinco planetas más brillantes con los nombres de sus dioses.","Un astronauta ruso. ","Sí, pero un poco más lento que en la Tierra. ",
"Un vehículo no tripulado que recorre el espacio recopilando datos y envía esos datos de vuelta a la Tierra para su estudio. ",
"Sí, pero solo un 38% de la terrestre. ",
"Una medida teórica que interpreta las tras dimensiones que vemos en el espacio más una cuarta flecha que representa el tiempo. ",
"No tal cual. Hace falta un soporte vital adecuado para resistir sus condiciones hostiles. ",
"Si lo medimos desde la superficie terrestre a nivel del mar comienza a unos 100 km de altura. ",
"Porque nuestra vista no es lo bastante aguda como para ver la luz de fondo. ",
"Valentina Tereshkova. ",
"Entre las órbitas de Marte y Júpiter. ","No se sabe, los astrónomos babilonios ya lo conocían en el año 400 antes de Cristo. ",
"La trayectoria circular de un objeto al moverse alrededor de un planeta o estrella. ",
"No, es un mito. ",
"Sí, pero solo de noche. Su brillo depende de la fecha. ",
"Alan B. Shepard. ",
"Sí, pero no es respirable para el ser humano. ",
"Yuri Gagarin. ",
"Depende de la nave. Unos 9 minutos a bordo de un transbordador. Los Falcon 9 de SpaceX tardan 10. ",
"Constantemente en movimiento sobre la órbita terrestre. ",
"687 días terrestres. ",
"No. ","Por el óxido de hierro que cubre su superficie. ","El vacío perfecto no existe, pero el espacio se le acerca mucho. ","Máxima: 30º Celsius, Mínima: -175º Celsius. Media: -62º Celsius. ","El sonido existe en el espacio, pero los seres humanos no pueden oirlo.","Es un cuerpo rocoso demasiado pequeño para ser un planeta.","No que yo sepa. ","Dos, Deimos y Fobos. ","National Aeronautics and Space Administration. ","Entre seis y nueve meses. El Curiosity Rover tardó 254 días (8,3 meses). "
];
$respuestaBase="
¿Te gustaría conocer más? ¡Visitanos!
¿Te cuento acerca de los próximos eventos?";


function chat($array,$preguntas,$pregunta,$respuestas,$respuestaBase){
  $envio=$array;
  $chat="";
  $str2="hola";
  $i=0;
  foreach ($preguntas as $pregunt) {
        similar_text($envio,$pregunt[$i], $porsentaje);
        if ($porsentaje >= 25){
          $chat= $respuestas[$i]. $respuestaBase;

        } elseif(strncasecmp($envio, $str2, 3)===0) {
          return $pregunta;
        }elseif($envio==="") {
          return $pregunta;
        }else{
        $chat= $respuestas[0];

        }
        $i++;
    }
      return $chat;
  }

if ($_POST) {
  $respuesta=chat($_POST["pregunta"],$preguntas,$pregunta,$respuestas,$respuestaBase);
  $pregunta=$_POST["pregunta"];;

 }

var_dump($preguntas);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>BOT</title>
  </head>
  <body>
    <form class="" action="hola.php" method="post">
      <div class="pregunta">
        <h3><?=$pregunta?></h3>
      </div>
      <div class="respuesta">
          <h3><?=$respuesta?></h3>
      </div>
          <input type="text" name="pregunta" value="">
          <br>

          <input type="submit" name="" value="enviar">
    </form>
  </body>
</html>
