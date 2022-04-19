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


            function genere_liste($ordre, $colone){
                global $db;
                global $listes_listes;
                $id_user_active = $_SESSION['id_user_active'];
                $id ="";
                $note = "";
                $commentaire = "";
                $date_realise = "";
                $titre = "";
                $annee = "";
                $genre = "";
                $pays = "";
                $resume = "";
                $poster= "";
                $vote = "";
                $tableau = array();
                
                foreach($db->query("SELECT id_film, note, commentaire, date_realise FROM films_users WHERE id_user = $id_user_active AND date_realise != 'null'") as $row ){
                    $id = $row[0];
                    $note = $row[1];
                    $commentaire = $row[2];
                    $date_realise = $row[3];

                    foreach($db->query("SELECT titre, annee, genre, pays, resume, poster, vote FROM films WHERE id = $id") as $row ){
                        $titre = $row[0];
                        $annee = $row[1];
                        $genre = $row[2];
                        $pays = $row[3];
                        $resume = $row[4];
                        $poster= $row[5];
                        $vote = $row[6];

                        array_push($tableau, array('id'=>$id, 'note'=>$note, 'commentaire'=>$commentaire, 'date_realise'=>$date_realise, 'titre'=>$titre, 'annee'=>$annee, 'genre'=>$genre, 'pays'=>$pays, 'resume'=>$resume, 'poster'=>$poster, 'vote'=>$vote ));
                    }
                }

                if(isset($_POST["filtre_ajout"])){
                    extract($_POST);
                    $nouveau_tableau = array();
                    $filtres_appliques = array();
                    $titre_tableau = array();
                    $note_tableau = array();
                    $vote_tableau = array();
                    $annee_tableau = array();
                    $commentaire_tableau = array();
                    $genre_tableau = array();
                    $pays_tableau = array();
                    $resume_tableau = array();
                    $chaine="";



                    if($titre != ""){

                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['titre'], $titre) !== false){
                                array_push($titre_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $titre_tableau);
                    }

                    for ($row = 0; $row < count($tableau); $row++) {
                        if( $tableau[$row]['note'] >= $note_min and $tableau[$row]['note'] <= $note_max) {
                            array_push($note_tableau, $tableau[$row]);
                        }
                    }

                    array_push($filtres_appliques, $note_tableau);


                    for ($row = 0; $row < count($tableau); $row++) {
                        if( $tableau[$row]['vote'] >= $vote_min and $tableau[$row]['vote'] <= $vote_max) { 
                            array_push($vote_tableau, $tableau[$row]);
                        }
                    }

                    array_push($filtres_appliques, $vote_tableau);


                    for ($row = 0; $row < count($tableau); $row++) {
                        if( $tableau[$row]['annee'] >= $annee_min and $tableau[$row]['annee'] <= $annee_max) { 
                            array_push($annee_tableau, $tableau[$row]);
                        }
                    }
                    
                    array_push($filtres_appliques, $annee_tableau);


                    if($comment != ""){
                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['commentaire'], $comment) !== false){
                                array_push($commentaire_tableau, $tableau[$row]);
                                
                            }
                        }
                        array_push($filtres_appliques, $commentaire_tableau);
                    }


                    if($genre != ""){

                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['genre'], $genre) !== false){
                                array_push($genre_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $genre_tableau);
                    }


                    if($pays != ""){

                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['pays'], $pays) !== false){
                                array_push($pays_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $pays_tableau);
                    }

                    if($resume != ""){
                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['resume'],$resume) !== false){ 
                                array_push($resume_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $resume_tableau);
                    }

                    function serialize_array_values($arr){
                        foreach($arr as $key=>$val){
                            sort($val);
                            $arr[$key]=serialize($val);
                        }
                    
                        return $arr;
                    }

                    function resultat($arr){
                        $prev = array();
     
                        for ($a = 0; $a < count($arr[0]); $a++ ) {
                            for ($b = 0; $b < count($arr[1]); $b++ ) {

                                if($arr[0][$a]['id'] == $arr[1][$b]['id']){

                                    array_push($prev, $arr[1][$b]);
                                }
                            }
                        }
                        
                        
                        for ($c = 2; $c < count($arr); $c++ ) {

                            $next = array();

                            for ($d = 0; $d < count($arr[$c]); $d++ ) {
                                for($e = 0; $e < count($prev); $e++ ) {

                                    if($prev[$e]['id'] == $arr[$c][$d]['id']){

                                        array_push($next, $arr[$c][$d]);
                                    }
                                }
                            }

                            $prev = $next;
                        }

                        $next;
                        return $next;
                    }
                
                    $result = resultat($filtres_appliques);
                    for ($row = 0; $row < count($result); $row++) {

                        $listes_listes = $listes_listes . 
                            '<div class="liste_element" onclick="redirige(`cinema_element.php?id='.$result[$row]['id'].'`);">
                                <img src="'.$result[$row]['poster'].'" alt="'.$result[$row]['titre'] .'">
                                    <h5 class="noteimg">'.$result[$row]['titre'].'</h5>
                                    <h5 class="noteh5">Votre note : <span> '.$result[$row]['note'].'</span></h5>
                                </div>';
                    }

                }else{

                    $columns = array_column($tableau, $colone);
                    array_multisort($columns, $ordre , $tableau);

                    for ($row = 0; $row < count($tableau); $row++) {

                        $listes_listes = $listes_listes . 
                            '<div class="liste_element" onclick="redirige(`cinema_element.php?id='.$tableau[$row]['id'].'`);">
                                <img src="'.$tableau[$row]['poster'].'" alt="'.$tableau[$row]['titre'] .'">
                                    <h5 class="noteimg">'.$tableau[$row]['titre'].'</h5>
                                    <h5 class="noteh5">Votre note : <span> '.$tableau[$row]['note'].'</span></h5>
                                </div>';
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
                        genere_liste(SORT_DESC, 'date_realise');
                        break;

                    case 'date_asc': 
                        genere_liste(SORT_ASC, 'date_realise');
                        break;

                    case 'alpha_asc': 
                        genere_liste(SORT_ASC, 'titre');
                        break;

                    case 'alpha_desc': 
                        genere_liste(SORT_DESC, 'titre');
                        break;

                    default: 
                        genere_liste(SORT_DESC, 'date_realise');
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

            <div id="filtre">
                <div id="m_filtre" style="display:none;">
                    <form method="post" id="modif_filtre" class="form_para">
                        
                        <div>
                            <div>
                                <label for="titre">TITRE</label>
                                <input type="text" name="titre" id="titre" class="cInput">
                            </div>
                            <div>
                                <label for="note_min">NOTE MIN</label>
                                <input type="number" name="note_min" id="note_min" min="0" max="10" value="5" step="0.1" class="cInput">
                            </div>
                            <div>
                                <label for="note_max">NOTE MAX</label>
                                <input type="number" name="note_max" id="note_max" min="0" max="10" value="10" step="0.1" class="cInput">
                            </div>
                            <div>
                                <label for="vote_min">VOTE IMDB MIN</label>
                                <input type="number" name="vote_min" id="vote_min" min="0" max="10" value="5" step="0.1" class="cInput">
                            </div>
                            <div>
                                <label for="vote_max">VOTE IMDB MAX</label>
                                <input type="number" name="vote_max" id="vote_max" min="0" max="10" value="10" step="0.1" class="cInput">
                            </div>
                            <div>
                                <label for="annee_min">ANNEE MIN</label>
                                <input type="number" name="annee_min" id="annee_min" min="1800" max="2030" value="1960" step="1" class="cInput">
                            </div>
                            <div>
                                <label for="annee_max">ANNEE MAX</label>
                                <input type="number" name="annee_max" id="annee_max" min="1800" max="2030" value="2030" step="1" class="cInput">
                            </div>
                            <div>
                                <label for="comment">COMMENTAIRES</label>
                                <input type="text" name="comment" id="comment" class="cInput">
                            </div>
                            <div>
                                <label for="genre">GENRE</label>
                                <input type="text" name="genre" id="genre" class="cInput">
                            </div>
                            <div>
                                <label for="pays">PAYS</label>
                                <input type="text" name="pays" id="pays" class="cInput">
                            </div>
                            <div>
                                <label for="resume">RÉSUMÉ</label>
                                <input type="text" name="resume" id="resume" class="cInput">
                            </div>
                        </div>

                        <input type="submit" name="filtre_ajout" id="filtre_ajout" class="bouton_a btn_param b" value="APPLIQUER LE(S) FILTRE(S)">
                    </form>
                </div>

                <button id="filtre_selection" onclick="ouvre('m_filtre','filtre_selection', 'APPLIQUER DES FILTRES', 'flex', 'ANNULER');" type="button" class="bouton_a">APPLIQUER DES FILTRES</button>
            </div>

            <div id="myModal" class="modal">
                <div class="modal-content modalb">
                    <span class="close" onclick="ferme_modal('myModal', 1);">&times;</span>

                    <form id="ajout_element_film" method="post">
                        <div id="elements_recherche">
                            <div>
                                <label for="recherche_film">RECHERCHER LE FILM</label>
                                <input type="text" name="recherche_film" id="recherche_film" class="cInput">
                                <input type="submit" name="rech_film" id="rech_film" class="bouton_a" value="GO">
                            </div>
                            <div>
                                <label for="recherche_serie">RECHERCHER LA SÉRIE</label>
                                <input type="text" name="recherche_serie" id="recherche_serie" class="cInput"></br>
                                <input type="submit" name="rech_serie" id="rech_serie" class="bouton_a" value="GO">
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
                            <input type="number" name="note" id="note" min="0" max="10" value="5" step="0.1" required class="cInput"></br>
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