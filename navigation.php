
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Excellent taste</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            <?php
            //Als de pagina reserveringen is, laat dan een aantal navigatie knoppen zien
            $url = $_SERVER["REQUEST_URI"];
            $pos = strrpos($url, "reserveringen.php");

            if($pos != false) {
                echo "       <li class='nav-item'>
                          <a class='nav-link' href='reserveringen.php'>Reserveringen</a>
                          </li>
                            <li class='nav-item'>
                            <a class='nav-link' href='overzichtbarman.php'>Overzicht Barman</a>
                          </li>
                          <li class='nav-item'>
                          <a class='nav-link' href='factuurklant.php'>Bon voor klant</a>
                          </li>
                          <li class='nav-item'>
                          <a href='weekoverzicht.php' class='nav-link'>Weekomzet</a>
                          </li>
                          ";
            }
            ?>
        </div>
    </div>
</nav>