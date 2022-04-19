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
		<link rel="stylesheet" href="../assets/css/style.css" />
        <link rel="icon" type="./image/svg+xml" sizes="32x32" href="../assets/img/icon.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&family=Roboto&display=swap" rel="stylesheet">
	</head>

	<body>
    
    <main>

        <header>
            <img src="../assets/img/logo_slogan.svg" alt="Logo Gaudia" id="logo_connex">
        </header>

        <div id="oubliForm">

            <h3 class="ins">Demander un nouveau<br> mot de passe</h3>

            <form method="post" id="oubliPassword">
                <label for="oemail">VOTRE COURRIEL</label></br>
                <input type="email" name="oemail" id="oemail" required class="cInput"></br>
                <input type="submit" name="newp" id="newp"  class="bouton_a" value="M'ENVOYER LE MOT DE PASSE">
            </form>

            <?php

                if(isset($_POST['newp'])){

                    extract($_POST);
                    include '../db/database.php';

                    $f = $db->prepare("SELECT email FROM user WHERE email = :email");
                    $f->execute([ 'email' => $oemail]);
                    $result = $f->rowCount();                      

                    if($result == true){

                        $password = uniqid();
                        
                        $options = ['cost' =>12];
                        $hashpass = password_hash($password, PASSWORD_BCRYPT, $options);

                        $subject = "Mot de passe oublié";
                        $message = "Bonjour, voici votre nouveau mot de passe : $password";
                        $headers = "From: noreply@gaudia.com";

                        if(mail($oemail, $subject, $message, $headers)){

                            $e = $db->prepare("UPDATE user SET password = ? WHERE email = ?");
                            $e->execute([$hashpass, $oemail]); 

                            echo "Mail envoyé";
                        }else{

                            echo "<p style='color:red;'>Une erreur est survenue</p>";
                        }
                    }else{
                        echo"<p style='color:red;'>Il n'y pas de compte relié à ce courriel</p>";
                    }
                }
            ?>

            <p><a href="../connexion.php">Retour au formulaire de connexion</a></p>

        </div>

    </main>

    </body>
</html>