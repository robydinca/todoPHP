<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo Assistant</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="./assets/index.css">

</head>

<body>
  <?php
  if (!file_exists("./config/config.php")) {
    header("Location: ./config/install.php");
  } else {
    header("Location index.php");
  }
  ?>
  <div class="container">
    <?php
    require_once "./views/header.php"
    ?>

    <main>
      <section class="hero">
        <div class="hero__block">
          <h1>
            Gestiona tus tareas de manera eficiente gracias al <span>Asistente IA</span>
          </h1>
          <h2>Incrementa tu productividad con la app Todo y su Asistente de IA</h2>
          <a href="./views/register.php" class="btn">Registrarse</a>
        </div>
        <img src="./assets/hero.webp" alt="">
      </section>


      <section class="services">
        <h2>Gestiona tus tareas fácilmente</h2>
        <p>¿Que posibilidades me ofrece todo.Ai?</p>
        <div class="services__pack">
          <div>
            <img src="./assets/icon1.png" alt="">
            <h3>Apuntes de Cursos</h3>
            <p>Nuestra aplicación Todo con asistencia de IA permite crear y organizar fácilmente apuntes de cursos, facilitando el estudio y la revisión.</p>
          </div>
          <div>
            <img src="./assets/icon2.png" alt="">
            <h3>Menús de Comida</h3>
            <p>Con la asistencia de IA, Todo ayuda a diseñar y planificar menús de comida personalizados, ofreciendo opciones y planificación nutricional.</p>
          </div>
          <div>
            <img src="./assets/icon3.png" alt="">
            <h3>Rutinas de Ejercicios</h3>
            <p>Nuestra aplicación Todo con asistente de IA permite crear y seguir rutinas de ejercicios personalizadas, proporcionando consejos y seguimiento para mantenerse en forma.</p>
          </div>
        </div>
      </section>



      <section class="design">
        <img src="./assets/pana.webp" alt="">
        <div class="design__block">
          <h2>Maximiza tu productividad con la ayuda de la inteligencia artificial</h2>
          <p>Descubre cómo nuestra aplicación de lista de tareas con inteligencia artificial puede elevar tu productividad diaria. Con nuestra avanzada asistencia de IA, puedes gestionar eficientemente tus tareas diarias, obtener recomendaciones personalizadas y mejorar tu enfoque en las actividades más importantes. Simplifica tu día a día, logra más y alcanza tus metas con nuestra innovadora aplicación.</p>
        </div>
      </section>



      <section class="cta">
  <h2>Mejora tu productividad con nuestra aplicación TodoList y asistente de IA</h2>
  <a href="./views/register.php" class="btn">¡Pruébalo ahora!</a>
</section>


    </main>

  </div>

  <script type="module" src="/main.js"></script>
</body>

</html>