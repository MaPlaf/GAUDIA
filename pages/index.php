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

        <?php require 'header_nav.php'; 
			include '../db/database.php';
			$id_user_active = $_SESSION['id_user_active'];
			
			$film = $db->prepare("SELECT id FROM films_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $film->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_film = $film->rowCount();

			$livre = $db->prepare("SELECT id FROM livres_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $livre->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_livre = $livre->rowCount();

			$voyage = $db->prepare("SELECT id FROM voyage_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $voyage->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_voyage = $voyage->rowCount();

			$gastronomie = $db->prepare("SELECT id FROM gastronomie_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $gastronomie->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_gastronomie = $gastronomie->rowCount();

			$jeux = $db->prepare("SELECT id FROM jeux_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $jeux->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_jeux = $jeux->rowCount();

			$spectacles = $db->prepare("SELECT id FROM spectacles_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $spectacles->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_spectacles = $spectacles->rowCount();

			$activite = $db->prepare("SELECT id FROM activite_users WHERE id_user = :id_user AND date_realise != :date_realise");
            $activite->execute([ 'id_user' => $id_user_active, 'date_realise' =>  'null']);
            $resultat_activite = $activite->rowCount();
		
		?>

		<main class="pageb_main">

            <div class="titre_page">
				<?php  if(isset($_SESSION['prenom'])){?> 
                    <h1>BIENVENUE <?php echo strtoupper($_SESSION['prenom']); ?></h1>
                    
                <?php }?>
				<?php  if(isset($_SESSION['message'])){?> 
                    <h3><?php echo $_SESSION['message']; ?> <img src="../assets/img/check_bl.svg" alt="Logos activités" width="20px"></h3>
                <?php }?>
            </div>

			<h3 id="index_titre">VOS LOISIRS RÉALISÉS</h3>

			<div id="contenant_statistique">

				<div class="stats_loisir">
					<span title="Cinéma"><img src="../assets/img/icon_cinema.svg" alt="Icon Cinema" width="100px"></span>
					<h3><?php echo $resultat_film; ?></h3>
				</div>
				<div class="stats_loisir">
					<span title="Littérature"><img src="../assets/img/icon_litterature.svg" alt="Icon Littérature" width="100px"></span>
					<h3><?php echo $resultat_livre; ?></h3>
				</div>

				<div class="stats_loisir">
					<span title="Voyage"><img src="../assets/img/icon_voyage.svg" alt="Icon Voyage" width="100px"></span>
					<h3><?php echo $resultat_voyage; ?></h3>
				</div>

				<div class="stats_loisir">
					<span title="Gastronomie"><img src="../assets/img/icon_gastronomie.svg" alt="Icon Gastronomie" width="100px"></span>
					<h3><?php echo $resultat_gastronomie; ?></h3>
				</div>

				<div class="stats_loisir">
					<span title="Jeux"><img src="../assets/img/icon_jeux.svg" alt="Icon Jeux" width="100px"></span>
					<h3><?php echo $resultat_jeux; ?></h3>
				</div>

				<div class="stats_loisir">
					<span title="Spectacles"><img src="../assets/img/icon_spectacle.svg" alt="Icon Spectacles" width="100px"></span>
					<h3><?php echo $resultat_spectacles; ?></h3>
				</div>

				<div class="stats_loisir">
					<span title="Autres Activités"><img src="../assets/img/icon_autres_activites.svg" alt="Autres Activités" width="100px"></span>
					<h3><?php echo $resultat_activite; ?></h3>
				</div>

			</div>

		</main>

        <?php require 'footer.php'; ?>

	</body>
</html>
