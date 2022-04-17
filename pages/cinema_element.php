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
        <?php require 'header_nav.php'; 
        
        include '../db/database.php';
        global $db;
        $id_user_active = $_SESSION['id_user_active'];
        $id_element = $_GET["id"];
        
        $a = $db->prepare("SELECT titre, annee, genre, pays, resume, poster, vote FROM films WHERE id = :id");
        $a->execute([ 'id' => $id_element]);
        $result = $a->fetch();
        
        $titre= $result[0];
        $annee= $result[1];
        $pays= $result[3];
        $resume= $result[4];
        $poster= $result[5];
        $vote= $result[6];
        $genre_tableau= explode("-", $result[2]);
        $genre = "";
        foreach ($genre_tableau as $value) {
            $genre = $genre . $value .", ";
        }
        $genre = substr($genre, 2, -2);

        $b = $db->prepare("SELECT note, commentaire FROM films_users WHERE id_user = :id_user AND id_film = :id_film ");
        $b->execute([ 'id_user' => $id_user_active, 'id_film' => $id_element ]);
        $resultb = $b->fetch();

        $note = $resultb[0];
        $commentaire = $resultb[1];

        if(isset($_POST["modifelement"])){
            extract($_POST);

            $m = $db->prepare("UPDATE films_users SET note = ?, commentaire = ?, date_realise = ? WHERE id_user = ? AND id_film = ?");
            $m->execute([$note, $commentaire, date("Y-m-d h:i:s",time()), $id_user_active, $id_element]);

            header("Location: cinema_element.php?id=".$id_element."");
            die();
        }

        if(isset($_POST["supp_element"])){

            $n = $db->prepare("DELETE FROM films_users WHERE id_user = :id_user AND id_film = :id_film");
            $n->execute(['id_user' => $id_user_active, 'id_film' => $id_element]);

            header("Location: cinema_realise.php");
            die();
        }
        
        ?>

        <div class="titre">
            <img src="../assets/img/titre_cinema.svg" alt="Page Cinéma" id="cinema_titre">
        </div>

        <main class="page_main">

            <div id="fait_nonfait">
                <a id="afaire" class="nav_b" href="cinema.php">À FAIRE</a><span> | </span><a id="realise" class="nav_b" href="cinema_realise.php">RÉALISÉS</a>
            </div>

            <div id="retour_reglage">
                <a href="cinema_realise.php" class="retour"><img src="../assets/img/retour.svg" alt="Retour" width="25px"> Retour</a>

                <button type="button" id="btn_ouvrirb" class="btn_reglage" onclick="ouvrir_modal('myModalb');">MODIFIER <img src="../assets/img/reglage.svg" alt="modif_element" id="modif_element" width="20px"></button>

                <div id="myModalb" class="modal">
                    <div class="modal-content modalb">
                        <span class="closeb" onclick="ferme_modal('myModalb', 0);">&times;</span>

                        <div id="contenu_reglage">
                            <h3 style="font-size:25px; margin-bottom:3rem;" class="hparam">RÉGLAGE DU FILM/DE LA SÉRIE</h3>

                            <div id="m_nom" style="display:none;">
                                <form method="post" id="modif_element">
                                    <label for="note">VOTRE NOTE SUR 10</label>
                                    <input type="number" name="note" id="note" style="width: 370px;" min="0" max="10" value="<?php echo $note?>" step="0.1" required class="cInput" placeholder="Entrez votre prénom..."></br>
                                    <label for="commentaire">VOS COMMENTAIRES</label>
                                    <textarea rows="4" name="commentaire" id="commentaire" style="width: 370px;" class="cInput_text"><?php echo $commentaire?></textarea></br>
                                    <input type="submit" name="modifelement" id="modifelement" class="bouton_a bouton_c" value="MODIFIER">
                                </form>
                            </div>

                            <button id="change_nom" onclick="ouvre('m_nom','change_nom','MODIFIER LE NOM');" type="button" class="bouton_a">MODIFIER</button>

                            <div id="supprime_element" style="display:none;">
                                <form method="post" id="sup_element">
                                    <label style="display:inline-block; height:60px">VOULEZ-VOUS VRAIMENT<br>SUPPRIMER CE FILM/CETTE SÉRIE?</label><br>
                                    <input type="submit" name="supp_element" id="supp_element" class="bouton_a bouton_c" value="SUPPRIMER">
                                </form>
                            </div>
                            <button id="supression" onclick="ouvre('supprime_element','supression', 'SUPPRIMER');" type="button" class="bouton_a">SUPPRIMER</button>
                        </div>
                    
                    </div>
                </div>
            </div>

            <div id="contenant_element">

                <div id="infos_element">
                    <img src="<?php echo $poster?>" alt="<?php echo $titre?>">

                    <div id="infos">
                        <h1><?php echo mb_strtoupper($titre)?></h1>
                        <h3 class="sous-t"><?php if ($pays != "undefined"){echo $pays. ", ";}?><?php echo $annee?></h3>
                        <h3 class="sous-tb"><?php echo $genre?></h3>
                        <p><?php echo $resume?></p>
                        
                        <div id="note_IMBD">
                            <h6>Note IMDB : <span><?php echo $vote?></span></h6>
                        </div>
                    </div>
                </div>

                <div id="avis_element">
                    <h1>Votre note :<br><img src="../assets/img/etoile.svg" alt="Étoile" width="45px"> <span><?php echo $note?></span></h1>

                    <div>
                        <h2>Vos commentaires :</h2>
                        <p><?php echo $commentaire?></p>
                    </div>
                </div>
            </div>

        </main>

        <?php require 'footer.php'; ?>

        <script src="../assets/js/main.js"></script>
    </body>
</html>