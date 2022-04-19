<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<title>GAUDIA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="type" content="Stockez, notez et organisez vos loisirs et divertissements, fixez-vous des objectifs en vous créant des listes à réaliser et retrouvez facilement les recettes que vous avez fait, les films que vous avez écouté, les livres que vous avez lu et beaucoup plus encore" />
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
        global $db;
        $id_user_active = $_SESSION['id_user_active'];
        $id_element = $_GET["id"];
        
        $a = $db->prepare("SELECT nom, emplacement, description, photo FROM activite WHERE id = :id");
        $a->execute([ 'id' => $id_element]);
        $result = $a->fetch();
        
        $nom= $result[0];
        $emplacement= $result[1];
        $description= $result[2];
        $photo= $result[3];

        $b = $db->prepare("SELECT note, commentaire FROM activite_users WHERE id_user = :id_user AND id_activite = :id_activite ");
        $b->execute([ 'id_user' => $id_user_active, 'id_activite' => $id_element ]);
        $resultb = $b->fetch();

        $note = $resultb[0];
        $commentaire = $resultb[1];

        if(isset($_POST["modifelement"])){
            extract($_POST);

            $m = $db->prepare("UPDATE activite_users SET note = ?, commentaire = ?, date_realise = ? WHERE id_user = ? AND id_activite = ?");
            $m->execute([$note, $commentaire, date("Y-m-d h:i:s",time()), $id_user_active, $id_element]);

            header("Location: activite_element.php?id=".$id_element."");
            die();
        }

        if(isset($_POST["supp_element"])){

            $n = $db->prepare("DELETE FROM activite_users WHERE id_user = :id_user AND id_activite = :id_activite");
            $n->execute(['id_user' => $id_user_active, 'id_activite' => $id_element]);

            header("Location: activite_realise.php");
            die();
        }
        
        ?>

        <div class="titre">
            <img src="../assets/img/titre_activite.svg" alt="Page activite" id="activite_titre">
        </div>

        <main class="page_main">

            <div id="fait_nonfait">
                <a id="afaire" class="nav_b" href="activite.php">À FAIRE</a><span> | </span><a id="realise" class="nav_b" href="activite_realise.php">RÉALISÉS</a>
            </div>

            <div id="retour_reglage">
                <a href="activite_realise.php" class="retour"><img src="../assets/img/retour.svg" alt="Retour" width="25px"> Retour</a>

                <button type="button" id="btn_ouvrirb" class="btn_reglage" onclick="ouvrir_modal('myModalb');">MODIFIER <img src="../assets/img/reglage.svg" alt="modif_element" id="modif_element" width="20px"></button>

                <div id="myModalb" class="modal">
                    <div class="modal-content modalb">
                        <span class="closeb" onclick="ferme_modal('myModalb', 0);">&times;</span>

                        <div id="contenu_reglage">
                            <h3 style="font-size:25px; margin-bottom:3rem;" class="hparam">RÉGLAGES DE L'ACTIVITÉ</h3>

                            <div id="m_nom" style="display:none;">
                                <form method="post" id="modif_avis">   
                                    <label for="note">VOTRE NOTE SUR 10</label>
                                    <input type="number" name="note" id="note" style="width: 370px;" min="0" max="10" value="<?php echo $note?>" step="0.1" required class="cInput"></br>
                                    <label for="commentaire">VOS COMMENTAIRES</label>
                                    <textarea rows="4" name="commentaire" id="commentaire" style="width: 370px;" class="cInput_text"><?php echo $commentaire?></textarea></br>
                                    <input type="submit" name="modifelement" id="modifelement" class="bouton_a bouton_c" value="MODIFIER">
                                </form>
                            </div>

                            <button id="change_nom" onclick="ouvre('m_nom','change_nom','MODIFIER LE NOM', 'inline', 'ANNULER');" type="button" class="bouton_a">MODIFIER</button>

                            <div id="supprime_element" style="display:none;">
                                <form method="post" id="sup_element">
                                    <label style="display:inline-block; height:60px">VOULEZ-VOUS VRAIMENT<br>SUPPRIMER CETTE ACTIVITÉ?</label><br>
                                    <input type="submit" name="supp_element" id="supp_element" class="bouton_a bouton_c" value="SUPPRIMER">
                                </form>
                            </div>
                            <button id="supression" onclick="ouvre('supprime_element','supression', 'SUPPRIMER', 'inline', 'ANNULER');" type="button" class="bouton_a">SUPPRIMER</button>
                        </div>
                    
                    </div>
                </div>
            </div>

            <div id="contenant_element">

                <div id="infos_element">
                    <img src="<?php echo $photo?>" alt="<?php echo $nom?>">

                    <div id="infos">
                        <h1><?php echo mb_strtoupper($nom)?></h1>
                        <h3 class="sous-t"><?php echo $emplacement ?></h3>
                        <p><?php echo $description?></p>
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