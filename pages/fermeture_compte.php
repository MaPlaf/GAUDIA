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
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Roboto&display=swap" rel="stylesheet">
	</head>


	<body>
    <?php 
		
		$message_send = null;

		if(isset($_POST["envoie_comment"])){

			extract($_POST);

			$to = "info@gaudia.ca";
			$subject = "Commentaires fermeture de compte";
			$message = htmlspecialchars($commentaire);
			$headers = "";
			
			mail($to,$subject,$message,$headers);
			$message_send = "<h3 style='margin-bottom:2.5rem;'><img src='../assets/img/check.svg' alt='Vérifié' width='20px'> Merci pour vos commentaires, ils ont bien été envoyés</h3>";
		}

		?>

		<main class="pageb_main">

            <div class="titre_page fermeture_compte">

                <h1>NOUS SOMMES DÉSOLÉ DE<br> VOUS VOIR NOUS QUITTER <img src="../assets/img/sad.svg" alt="Contact" id="Contact" width="27px"></h1>
                <h3>N'hésitez pas à nous partagez les raisons qui <br>vous ont poussé à fermer votre compte et ce<br> qu'on pourrait faire pour s'améliorer</h3>

            </div>

            <div id="fermeture_form">
				<form method="post">
					<textarea rows="7" name="commentaire" id="commentaire" class="cInput_text fermeture" required></textarea></br>
					<input type="submit" name="envoie_comment" id="envoie_comment" class="bouton_a" value="ENVOYEZ">
				</form>
			</div>

			<a href="../inscription.php" style="color:white;">Page d'inscription</a>

            <?php echo $message_send ?>
            
        </main>
	</body>
</html>
