<header id="header-page">
    <div>
        <a href="index.php"><img src="../assets/img/logo_gaudia.svg" alt="Logo Gaudia" id="logo_gaudia"></a>
    </div>

    <div>
        <div id="deroulant">
            <h6 class="prenom_header"><?= $_SESSION['prenom'];?></h6>
            <img src="../assets/img/icon_compte.svg" alt="icon compte" id="icon_compte">
            <ul class="sous" id="sousmenu">
                <li><a href="parametre_compte.php">PARAMÈTRE DU COMPTE</a></li>
                <li><a href="index.php?deconnexion=true">DÉCONNEXION</a></li>
            </ul>
        </div>
    </div>
</header>

<div id="navigation">
    <span id="burger_menu"><img src="../assets/img/burger_menu.svg" alt="menu" id="menu"></span>

    <nav id="nav">
        <a class="nav_a" href="cinema.php">CINÉMA</a>
        <a class="nav_a" href="litterature.php">LITTÉRATURE</a>
        <a class="nav_a" href="voyage.php">VOYAGE</a>
        <a class="nav_a" href="gastronomie.php">GASTRONOMIE</a>
        <a class="nav_a" href="jeux.php">JEUX</a>
        <a class="nav_a" href="spectacles.php">SPECTACLES</a>
        <a class="nav_a" href="activite.php">AUTRES ACTIVITÉS</a>
    </nav>
</div>

<?php 
    if(!isset($_SESSION['prenom'])){
        header("Location: ../connexion.php");
        die();
    }

    if (isset($_GET['deconnexion'])) {
        session_unset();
        session_destroy();
        header("Location: ../connexion.php");
        die();
    }
?>

<script type="text/javascript">
    const currentLocation = location.href;
    const navItem = document.getElementsByClassName("nav_a");
    const navLength = navItem.length;
 
    for (let i=0; i<navLength; i++){
        var a = navItem[i].href.lastIndexOf("/");
        var b = navItem[i].href.lastIndexOf(".");
        var page = navItem[i].href.substring(a, b);

        if(currentLocation.includes(page)){
            navItem[i].classList.add("active");
        }
    }

    

    const mainnav = document.getElementById("nav");
    const menuIcon = document.getElementById("burger_menu")
    const bouton = document.getElementById("deroulant");
    const modal = document.getElementById("sousmenu")

    bouton.addEventListener("click", function(){
        if (modal.style.display != 'inline'){
            modal.style.display = 'inline';
        }else{
            modal.style.display = 'none';
        }
    })

    menuIcon.addEventListener("click", function(){
        if (mainnav.style.display != 'flex'){
            mainnav.style.display = 'flex';
            this.innerHTML = '<img src="../assets/img/burger_menu_x.svg" alt="menu" id="menu">';
        }else{
            mainnav.style.display = 'none';
            this.innerHTML = '<img src="../assets/img/burger_menu.svg" alt="menu" id="menu">';
        }
    })

</script>