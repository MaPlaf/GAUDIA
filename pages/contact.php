<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>GAUDIA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="../assets/css/style.css" />
        <link rel="icon" type="./image/svg+xml" sizes="32x32" href="../assets/img/icon.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Roboto&display=swap" rel="stylesheet">
	</head>

    <body>

        <?php 
		
		require 'header_nav.php';
		$message_send = null;

		function check_email($mail){
			$mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
			if (filter_var($mail, FILTER_VALIDATE_EMAIL)){
			return true;
			}
		}

		if(isset($_POST["envoie_mail"])){

			extract($_POST);

			$to = "info@gaudia.ca";
			$subject = htmlspecialchars($objet);
			$message = htmlspecialchars($message);
			$headers = "From: " . $email. "\r\n" .
			"Nom:". htmlspecialchars($nom).";";
			

			if(check_email($email)){
				mail($to,$subject,$message,$headers);
				$message_send = "<h3 style='margin-bottom:2.5rem;'><img src='../assets/img/check.svg' alt='Vérifié' width='20px'> Votre message a bien été envoyé</h3>";
			}else{
				$message_send = '<p style="color:red;">Une erreur est survenue.<br>Merci de vérifier vos informations et de réesayer de nouveau.</p>';
			}
		}
		?>

		<main class="pageb_main">

			<div class="titre_page">

				<h1>NOUS JOINDRE <img src="../assets/img/contact.svg" alt="Contact" id="Contact" width="45px"></h1>

				<h3>Vous avez une question? Une suggestion?<br>N'hésitez pas à nous écrire!</h3>

			</div>

			<?php echo $message_send ?>

			<div id="contact_form">
				<form method="post" id="envoiemail">
					<label for="nom">NOM</label>
					<input type="text" name="nom" id="nom" required class="cInput"></br>
					<label for="email">COURRIEL</label>
					<input type="email" name="email" id="email" required class="cInput"></br>
					<label for="objet">OBJET</label>
					<input type="text" name="objet" id="objet" required class="cInput"></br>
					<label for="message">MESSAGE</label>
					<textarea rows="4" name="message" id="message" class="cInput_text" required></textarea></br>
					<input type="submit" name="envoie_mail" id="envoie_mail" class="bouton_a" value="ENVOYEZ">
				</form>
			</div>

		</main>

        <?php require 'footer.php'; ?>	

	</body>
</html>