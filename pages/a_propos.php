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
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>

    <body>

        <?php require 'header_nav.php'; ?>

		<main class="pageb_main">

			<div class="titre_page">
				<h1>À PROPOS <img src="../assets/img/apropos.svg" alt="À propos" id="apropos" width="45px"></h1>
				<h3>GAUDIA, c'est quoi?</h3>
			</div>

			<div class="para_apropos">

				<p>De son nom qui vient du latin et qui veut dire joie, GAUDIA est une entreprise québecoise qui souhaite offrir à ses utilisateurs une plateforme où ils 
					pourront stocker, noter et organiser leurs loisirs et divertissements qu’ils ont pu faire, ainsi que de de se fixer des objectifs avec la création de listes à réaliser.</p>
				<p>Quelques sites semblables existent déjà, mais sont toujours spécifiques à un seul loisir. GAUDIA se démarque en incluant tout au même endroit pour vous rendre la vie plus facile.
					C'est plus efficace à gérer et plus facile à se retrouver.</p><br>

				<div>
					<img src="../assets/img/icon_cinema.svg" alt="Icon Cinema">
					<img src="../assets/img/icon_litterature.svg" alt="Icon Littérature">
					<img src="../assets/img/icon_voyage.svg" alt="Icon Voyage">
					<img src="../assets/img/icon_gastronomie.svg" alt="Icon Gastronomie">
					<img src="../assets/img/icon_jeux.svg" alt="Icon Jeux">
					<img src="../assets/img/icon_spectacle.svg" alt="Icon Spectacles">
					<img src="../assets/img/icon_autres_activites.svg" alt="Autres Activités">
				</div>

				<ul class="mise_en_situation">
					<li>Vous voulez vous souvenir d'une recette que vous avez fait il y a des mois?</li>
					<li>Vous voulez vous faire une liste des films classiques à écouter?</li>
					<li>Vous voulez recommander à un ami les livres que vous avez le plus aimé?</li>
					<li>Vous voulez faire une liste des artistes que vous aimeriez voir en spectacle?</li>
            	</ul>

				<h1>GAUDIA est pour vous!</h1>

			</div>
		</main>

        <?php require 'footer.php'; ?>	

	</body>
</html>