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
        <script src="https://kit.fontawesome.com/a3ddde716a.js" crossorigin="anonymous"></script>
	</head>

	<body>
        <?php require 'header_nav.php'; ?>

        <div class="titre">
            <img src="../assets/img/titre_cinema.svg" alt="Page Cinéma" id="cinema_titre">
        </div>

        <?php 

            include '../db/database.php';
            global $db;
            $id_user_active = $_SESSION['id_user_active'];
            $classe = null;
            $vide ="";
            $listes_listes ="";
            $deja_existe = "";

            if(isset($_POST["add_element"])){

                extract($_POST);

                if($poster == "https://image.tmdb.org/t/p/w500null"){
                    $poster = "../assets/img/visuel_non_dispo.svg";
                }

                $scriptjs = '<script type="text/javascript">function displayFunction(){document.getElementById("myModalc").style.display = "block";};</script>';
                $appel_script = '<BODY onLoad="displayFunction(), envoie_donnee(`nom_film_pese`,`'.$nom.'`,`poster_film_pese`,`'.$poster.'`);">';

                $a = $db->prepare("SELECT id FROM films WHERE resume = :resume");
                $a->execute([ 'resume' => $resume]);
                $resultat_a = $a->rowCount();
                $resulta = $a->fetch();

                if($resultat_a == 0){

                    $q = $db->prepare("INSERT INTO films(titre, annee, genre, pays, resume, poster, vote) VALUES(:titre, :annee, :genre, :pays, :resume, :poster, :vote)");
                    $q->execute([
                        'titre' => $nom,
                        'annee' => $annee,
                        'genre' => $genre,
                        'pays' => $pays,
                        'resume' => $resume,
                        'poster' => $poster,
                        'vote' => $vote
                        ]);
                        
                    $b = $db->prepare("SELECT id FROM films WHERE resume = :resume");
                    $b->execute([ 'resume' => $resume]);
                    $resultb = $b->fetch();

                    $r = $db->prepare("INSERT INTO films_users(id_user, id_film) VALUES(:id_user, :id_film)");
                    $r->execute([
                        'id_user' => $id_user_active,
                        'id_film' => $resultb['id']
                        ]);

                    echo $scriptjs ;
                    echo $appel_script;
                                    
                }else{

                    $d = $db->prepare("SELECT id FROM films_users WHERE id_user = :id_user AND id_film = :id_film");
                    $d->execute(['id_user' => $id_user_active, 'id_film' => $resulta['id']]);
                    $resultd = $d->fetch();
                    $resultat_d = $d->rowCount();

                    if($resultat_d == 0){

                        $i = $db->prepare("INSERT INTO films_users(id_user, id_film) VALUES(:id_user, :id_film)");
                        $i->execute([
                            'id_user' => $id_user_active,
                            'id_film' => $resulta['id']
                            ]);

                        echo $scriptjs ;
                        echo $appel_script;

                    }else{

                        $dd = $db->prepare("SELECT date_realise FROM films_users WHERE id_user = :id_user AND id_film = :id_film");
                        $dd->execute(['id_user' => $id_user_active, 'id_film' => $resulta['id']]);
                        $resultdd = $dd->fetch();
                    
                        if($resultdd ='null'){

                            echo $scriptjs ;
                            echo $appel_script;

                        }else{

                            $deja_existe = "<p style='color:red; text-align:center;'>Ce film ou cette série a déjà été écouté(e)!</p>";
                            echo '<script type="text/javascript">function displayFunction(){document.getElementById("myModal").style.display = "block";}</script>';
                            echo '<BODY onLoad="displayFunction()">';
                        }
                    }   
                }
            }

            if(isset($_POST["ajout_realise"])){
                extract($_POST);

                $ee = $db->prepare("SELECT id FROM films WHERE titre = :titre AND poster = :poster");
                $ee->execute(['titre' => $nom, 'poster' =>$poster]);
                $resultee = $ee->fetch();

                $hh = $db->prepare("SELECT id FROM films_users WHERE id_user = :id_user AND id_film = :id_film");
                $hh->execute(['id_user' => $id_user_active, 'id_film' =>$resultee['id']]);
                $resulthh = $hh->fetch();
                
                $ff = $db->prepare("UPDATE films_users SET note = ? , commentaire = ?, date_realise = ? WHERE id = ?");
                $ff->execute([$note, $commentaire, date("Y-m-d h:i:s",time()), $resulthh['id']]);

                $gg = $db->prepare("DELETE FROM films_elements_listes WHERE id_films_user = :id_films_user");
                $gg->execute(['id_films_user' => $resulthh['id']]);

                header("Location: cinema_element.php?id=".$resultee['id']."");
                die();
            }


            function genere_liste($ordre){
                global $db;
                global $listes_listes;
                $id_user_active = $_SESSION['id_user_active'];
                $id ="";
                $note = "";
                $tableau = array();

                if($ordre == 'date_realise desc' or $ordre == 'date_realise asc'){
                
                    foreach($db->query("SELECT id_film, note  FROM films_users WHERE id_user = $id_user_active AND date_realise != 'null' ORDER BY $ordre") as $row ){
                        $id = $row[0];
                        $note = $row[1];
                        foreach($db->query("SELECT titre, poster FROM films WHERE id = $row[0]") as $row ){
                            $listes_listes = $listes_listes . 
                            '<div class="liste_element" onclick="redirige(`cinema_element.php?id='.$id.'`);">
                                <img src="'.$row[1].'" alt="'.$row[0] .'">
                                <h5>'.$row[0] .'</h5>
                                <h5 class="noteh5">Votre note : <span> '.$note .'</span></h5>
                            </div>';
                        }
                    }

                }else{
                      
                    foreach($db->query("SELECT id_film, note  FROM films_users WHERE id_user = $id_user_active AND date_realise != 'null'") as $row ){
                        $id = $row[0];
                        $note = $row[1];
                        foreach($db->query("SELECT titre, poster FROM films WHERE id = $row[0]") as $row ){
                            array_push($tableau, array($id, $note, $row[0], $row[1]));
                        }
                    }

                    if($ordre == 'titre desc'){

                        $columns = array_column($tableau, 2);
                        array_multisort($columns, SORT_DESC ,$tableau);

                        for ($row = 0; $row < count($tableau); $row++) {

                            $listes_listes = $listes_listes . 
                                '<div class="liste_element" onclick="redirige(`cinema_element.php?id='.$tableau[$row][0].'`);">
                                    <img src="'.$tableau[$row][3].'" alt="'.$tableau[$row][2] .'">
                                    <h5>'.$tableau[$row][2].'</h5>
                                    <h5 class="noteh5">Votre note : <span> '.$note .'</span></h5>
                                </div>';
                        }

                    }else{
                        $columns = array_column($tableau, 2);
                        array_multisort($columns, SORT_ASC ,$tableau);

                        for ($row = 0; $row < count($tableau); $row++) {

                            $listes_listes = $listes_listes . 
                                '<div class="liste_element" onclick="redirige(`cinema_element.php?id='.$tableau[$row][0].'`);">
                                    <img src="'.$tableau[$row][3].'" alt="'.$tableau[$row][2] .'">
                                    <h5>'.$tableau[$row][2].'</h5>
                                    <h5 class="noteh5">Votre note : <span> '.$note .'</span></h5>
                                </div>';
                        } 
                    }
                }
            }

            
            $f = $db->prepare("SELECT id_film, date_realise FROM films_users WHERE id_user = $id_user_active AND date_realise != 'null'");
            $f->execute();
            $nb_res = $f->rowCount();

            if(($nb_res !== 0)){

                if(isset($_POST['classer_par'])){
                    $classe = $_POST['classer_par'];
                }
                
                switch($classe){
                    case 'date_desc': 
                        genere_liste("date_realise desc");
                        break;

                    case 'date_asc': 
                        genere_liste("date_realise asc");
                        break;

                    case 'alpha_asc': 
                        genere_liste("titre asc");
                        break;

                    case 'alpha_desc': 
                        genere_liste("titre desc");
                        break;

                    default: 
                        genere_liste("date_realise desc");
                        break;
                    }

            }else{
                $vide = "<h3 style='margin-top:5rem;'>Vous n'avez pas encore marqué un film comme réalisé!</h3>";
            }
        ?>

        <main class="page_main">
            <div id="fait_nonfait">
                <a id="afaire" class="nav_b" href="cinema.php">À FAIRE</a><span> | </span><a id="realise" class="nav_b" href="cinema_realise.php">RÉALISÉS</a>
            </div>

            <div id="options">

                <form name="myform" method="post">
                    <label for="classer_par">Classer par</label>
                    <select name="classer_par" onchange="this.form.submit()">
                        <option value="date_desc"<?php if($classe == "date_desc"){ echo " selected"; }?>> Date (récent->ancien)</option>
                        <option value="date_asc"<?php if($classe == "date_asc"){ echo " selected"; }?>> Date (ancien->récent)</option>
                        <option value="alpha_asc"<?php if($classe == "alpha_asc"){ echo " selected"; }?>> Ordre alphabétique</option>
                        <option value="alpha_desc"<?php if($classe == "alpha_desc"){ echo " selected"; }?>> Ordre alphabétique inverse</option>
                    </select>
                </form>

                <button type="button" id="btn_ouvrir" onclick="ouvrir_modal('myModal');"><span title="Ajouter un film">+</span></button>

            </div>

            <div id="myModal" class="modal">
                <div class="modal-content modalb">
                    <span class="close" onclick="ferme_modal('myModal', 1);">&times;</span>

                    <form id="ajout_element_film" method="post">
                        <div id="elements_recherche">
                            <div>
                                <label for="recherche_film">RECHERCHER LE FILM</label>
                                <input type="text" name="recherche_film" id="recherche_film" class="cInput">
                            </div>
                            <div>
                                <label for="recherche_serie">RECHERCHER LA SÉRIE</label>
                                <input type="text" name="recherche_serie" id="recherche_serie" class="cInput"></br>
                            </div>
                            <input type="submit" name="add_film" style="display:none;">
                        </div>
                            <div id="resultat_recherche">
                            </div>
                        <?php echo $deja_existe?>
                    </form>
                </div>
            </div>

            <h1>FILMS/SÉRIES ÉCOUTÉES</h1>

            <?php echo $vide; ?>

            <div id="contenant_liste_element">
                <?php echo $listes_listes;?>
            </div>

            <div id="myModalc" class="modal">
                <div class="modal-content">
                    <span class="closeb" onclick="ferme_modal('myModalc', 0);">&times;</span>

                    <div id="contenu_ajout_realise">
                        <h3 class="hparam">QU'EST CE QUE VOUS<br> EN AVEZ PENSÉ?</h3>
                        <form method="post" id="ajoutrealise">
                            <label for="note">VOTRE NOTE SUR 10</label>
                            <input type="number" name="note" id="note" min="0" max="10" value="5" step="0.1" required class="cInput" placeholder="Entrez votre prénom..."></br>
                            <label for="commentaire">VOS COMMENTAIRES</label>
                            <textarea rows="4" name="commentaire" id="commentaire" class="cInput_text" placeholder="Si vous en avez, biensûr."></textarea></br>
                            <input type="hidden" id="nom_film_pese" name="nom" value="" />
                            <input type="hidden" id="poster_film_pese" name="poster" value="" />
                            <input type="submit" name="ajout_realise" id="ajout_realise" class="bouton_a" value="SAUVEGARDER">
                        </form>
                    </div>
                </div>
            </div>
        </main>

        <?php require 'footer.php'; ?>

        <script src="../assets/js/main.js"></script>
    </body>
</html>