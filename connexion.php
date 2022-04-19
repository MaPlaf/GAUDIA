<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GAUDIA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="Stockez, notez et organisez vos loisirs et divertissements, fixez-vous des objectifs en vous créant des listes à réaliser et retrouvez facilement les recettes que vous avez fait, les films que vous avez écouté, les livres que vous avez lu et beaucoup plus encore" />
		<meta name="keywords" content="listes, loisirs, divertissements, organisation, cinéma, littérature, voyage, gastronomie, jeux, spectacles, activités" />
		<meta name="theme-color" content="#654472;"/>
		<link rel="stylesheet" href="assets/css/style.css" />
        <link rel="icon" type="./image/svg+xml" sizes="32x32" href="assets/img/icon.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto&display=swap" rel="stylesheet">
	</head>

	<body>
    
    <main id="pageConnex">

    <header>
        <img src="assets/img/logo_slogan.svg" alt="Logo Gaudia" id="logo_connex">
    </header>

    <div id="conForm">

        <?php 
    
            if(isset($_SESSION['prenom'])){
                header("Location: pages/index.php");
                die();
            }

            function check_email($mail){
                $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)){
                return true;
                }
            }
        

            if(isset($_POST["connex"])){

                extract($_POST);

                include 'db/database.php';
                global $db;

                if(!empty($lpassword) && !empty($lemail)){

                    if(check_email($lemail)){

                        $q = $db->prepare("SELECT * FROM user WHERE email = :email");
                        $q->execute(["email" => $lemail]);
                        $result = $q->fetch();

                        if($result == true){

                            if(password_verify($lpassword, $result["password"])){

                                $_SESSION['id_user_active'] = $result["id"];
                                $_SESSION['prenom'] = $result['prenom'];
                                $_SESSION['email_active'] = $result['email'];

                                header("Location: pages/index.php");
                                die();

                            }else{
                                echo "<p style='color:red;'>Mot de passe incorrect<br><a href='pages/oubli_password.php' style='color:red;'>Vous l'avez oublié?</a></p>";
                            }

                        }else{
                            echo "<p style='color:red;'>Il n'y pas de compte relié à ce courriel</p>";
                        }
                    }else{
                        echo "<p style='color:red;'>Veuillez rentrer un courriel valide</p>";
                    }
                }
            }

        ?>

        <form method="post" id="connexion">
            <label for="lemail">COURRIEL</label>
            <input type="email" name="lemail" id="lemail" required class="cInput"></br>
            <label for="lpassword">MOT DE PASSE</label>
            <input type="password" name="lpassword" id="lpassword" required class="cInput"></br>
            <input type="submit" name="connex" id="connex" class="bouton_a" value="SE CONNECTER">
        </form>

        <p>Pas encore de compte?<br>
            <a href="inscription.php">Inscrivez-vous maintenant</a>
        </p>

    </div>

    </main>

    </body>
</html>