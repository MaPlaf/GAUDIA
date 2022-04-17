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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>

    <body>

        <?php require 'header_nav.php'; ?>

		<main class="pageb_main">

			<div class="titre_page">
				<h1>À PROPOS <img src="../assets/img/apropos.svg" alt="À propos" id="apropos" width="40px"></h1>
				<h3>GAUDIA, c'est quoi?</h3>
			</div>

			<div class="para_apropos">

				<p>De son nom qui vient du latin et qui veut dire joie, GAUDIA est une entreprise québecoise qui souhaite offrir à ses utilisateurs une plateforme où ils 
					pourront stocker, noter et organiser leurs loisirs et divertissements qu’ils ont pu faire, ainsi que de de se fixer des objectifs avec la création de listes à réaliser.</p>
				<p>Quelques sites semblables existent déjà, mais sont toujours spécifiques à un seul loisir. GAUDIA se démarque en incluant tout au même endroit pour vous rendre la vie plus facile.
					C'est plus efficace à gérer et plus facile à se retrouver.</p><br>

				<img src="../assets/img/icon_loisirs_ligne.svg" alt="Logos activités" width="675px">

				<ul class="mise_en_situation">
					<li><i class="fa fa-circle"></i> Vous voulez vous souvenir d'une recette que vous avez fait il y a des mois?</li>
					<li><i class="fa fa-circle"></i> Vous voulez vous faire une liste des films classiques à écouter?</li>
					<li><i class="fa fa-circle"></i> Vous voulez recommander à un ami les livres que vous avez le plus aimé?</li>
					<li><i class="fa fa-circle"></i> Vous voulez faire une liste des artistes que vous aimeriez voir en spectacle?</li>
            	</ul>

				<h1>GAUDIA est pour vous!</h1>

			</div>


			<section>

			</section>
		</main>

        <?php require 'footer.php'; ?>	

	</body>
</html>