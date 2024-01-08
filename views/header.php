<header class="header">
    <h1>todo.Ai</h1>
    <nav class="header__nav">
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Features</a></li>
            <li><a href="#">Product</a></li>
            <li><a href="#">Testimonial</a></li>
            <li><a href="#">FAQ</a></li>
        </ul>
    </nav>
    <div class="header__auth">
        <?php
        // Verificar si el usuario está autenticado
        $isLoggedIn = isset($_SESSION['login']);
        

        if ($isLoggedIn) {
            // Si el usuario está autenticado, muestra los botones con texto 'Logout' y 'Profile Info'
            echo '<a href="./views/logout.php" class="btn btn--login">Logout</a>';
            echo '<a href="./views/profile.php" class="btn btn--sig nup">Profile Info</a>';
        } else {
            // Si el usuario no está autenticado, muestra los botones con texto 'Login' y 'Sign Up'
            echo '<a href="./views/login.php" class="btn btn--login">Login</a>';
            echo '<a href="./views/register.php" class="btn btn--sig nup">Sign Up</a>';
        }
        ?>
    </div>
</header>
