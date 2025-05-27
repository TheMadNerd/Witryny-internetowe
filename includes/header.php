<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="/css/style.css">
<header>
    <nav class="navbar">
        <div class="logo-container">
            <a href="/index.php">
                <img src="../images/logo.svg" alt="Logo" class="logo">
                <span class="logo-text">SmartTutor</span>
            </a>
        </div>

       <button class="hamburger" id="hamburger">☰</button>

       <ul class="nav-links" id="navLinks">
           <li><button class="close-btn" id="closeBtn">✖</button></li>

           <?php if (isset($_SESSION['user_id'])): ?>
               <li><a href="/pages/profile.php"><i class="fas fa-user"></i><?= htmlspecialchars($_SESSION['name']) ?></a></li>
               <li><a href="/pages/myBookings.php">Moje rezerwacje</a></li>
               <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 3): ?>
                   <li><a href="/pages/admin.php">Panel admina</a></li>
               <?php endif; ?>

               <li><a href="/pages/logout.php">Wyloguj</a></li>
           <?php else: ?>
               <li><a href="/pages/login.php">Zaloguj się</a></li>
               <li><a href="/pages/register.php">Zarejestruj się</a></li>
           <?php endif; ?>
       </ul>
    </nav>
</header>

<script src="/js/menu.js"></script>
