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
            <img src="../assets/img/titre_litterature.svg" alt="Page Litterature" id="litterature_titre">
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

                $scriptjs = '<script type="text/javascript">function displayFunction(){document.getElementById("myModalc").style.display = "block";};</script>';
                $appel_script = '<BODY onLoad="displayFunction(), envoie_donnee(`nom_livre_pese`,`'.$titre.'`,`poster_livre_pese`,`'.$poster.'`);">';

                $a = $db->prepare("SELECT id FROM livres WHERE resume = :resume");
                $a->execute([ 'resume' => $resume]);
                $resultat_a = $a->rowCount();
                $resulta = $a->fetch();

                if($resultat_a == 0){

                    $q = $db->prepare("INSERT INTO livres(titre, auteur, annee, genre, resume, poster, nbpage) VALUES(:titre , :auteur, :annee, :genre, :resume, :poster, :nbpage)");
                    $q->execute([
                        'titre' => $titre,
                        'auteur' => $auteur,
                        'annee' => $annee,
                        'genre' => $genre,
                        'resume' => $resume,
                        'poster' => $poster,
                        'nbpage' => $nbpage
                        ]);
                        
                    $b = $db->prepare("SELECT id FROM livres WHERE resume = :resume");
                    $b->execute([ 'resume' => $resume]);
                    $resultb = $b->fetch();

                    $r = $db->prepare("INSERT INTO livres_users(id_user, id_livres) VALUES(:id_user, :id_livres)");
                    $r->execute([
                        'id_user' => $id_user_active,
                        'id_livres' => $resultb['id']
                        ]);

                    echo $scriptjs ;
                    echo $appel_script;
                                    
                }else{

                    $d = $db->prepare("SELECT id FROM livres_users WHERE id_user = :id_user AND id_livres = :id_livres");
                    $d->execute(['id_user' => $id_user_active, 'id_livres' => $resulta['id']]);
                    $resultd = $d->fetch();
                    $resultat_d = $d->rowCount();

                    if($resultat_d == 0){

                        $i = $db->prepare("INSERT INTO livres_users(id_user, id_livres) VALUES(:id_user, :id_livres)");
                        $i->execute([
                            'id_user' => $id_user_active,
                            'id_livres' => $resulta['id']
                            ]);

                        echo $scriptjs ;
                        echo $appel_script;

                    }else{

                        $dd = $db->prepare("SELECT date_realise FROM livres_users WHERE id_user = :id_user AND id_livres = :id_livres");
                        $dd->execute(['id_user' => $id_user_active, 'id_livres' => $resulta['id']]);
                        $resultdd = $dd->fetch();
                    
                        if($resultdd ='null'){

                            echo $scriptjs ;
                            echo $appel_script;

                        }else{

                            $deja_existe = "<p style='color:red; text-align:center;'>Ce livre a déjà été lu!</p>";
                            echo '<script type="text/javascript">function displayFunction(){document.getElementById("myModal").style.display = "block";}</script>';
                            echo '<BODY onLoad="displayFunction()">';
                        }
                    }   
                }
            }

            if(isset($_POST["ajout_realise"])){
                extract($_POST);

                $ee = $db->prepare("SELECT id FROM livres WHERE titre = :titre AND poster = :poster");
                $ee->execute(['titre' => $nom, 'poster' =>$poster]);
                $resultee = $ee->fetch();

                $hh = $db->prepare("SELECT id FROM livres_users WHERE id_user = :id_user AND id_livres = :id_livres");
                $hh->execute(['id_user' => $id_user_active, 'id_livres' =>$resultee['id']]);
                $resulthh = $hh->fetch();
                
                $ff = $db->prepare("UPDATE livres_users SET note = ? , commentaire = ?, date_realise = ? WHERE id = ?");
                $ff->execute([$note, $commentaire, date("Y-m-d h:i:s",time()), $resulthh['id']]);

                $gg = $db->prepare("DELETE FROM livres_elements_listes WHERE id_livres_user = :id_livres_user");
                $gg->execute(['id_livres_user' => $resulthh['id']]);

                header("Location: litterature_element.php?id=".$resultee['id']."");
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
                $auteur = "";
                $resume = "";
                $poster= "";
                $nbpage = "";
                $tableau = array();
                
                foreach($db->query("SELECT id_livres, note, commentaire, date_realise FROM livres_users WHERE id_user = $id_user_active AND date_realise != 'null'") as $row ){
                    $id = $row[0];
                    $note = $row[1];
                    $commentaire = $row[2];
                    $date_realise = $row[3];

                    foreach($db->query("SELECT titre, annee, genre, auteur, resume, poster, nbpage FROM livres WHERE id = $id") as $row ){
                        $titre = $row[0];
                        $annee = $row[1];
                        $genre = $row[2];
                        $pays = $row[3];
                        $resume = $row[4];
                        $poster= $row[5];
                        $vote = $row[6];

                        array_push($tableau, array('id'=>$id, 'note'=>$note, 'commentaire'=>$commentaire, 'date_realise'=>$date_realise, 'titre'=>$titre, 'annee'=>$annee, 'genre'=>$genre, 'auteur'=>$auteur, 'resume'=>$resume, 'poster'=>$poster, 'nbpage'=>$nbpage ));
                    }
                }

                if(isset($_POST["filtre_ajout"])){
                    extract($_POST);
                    $filtres_appliques = array();
                    $titre_tableau = array();
                    $note_tableau = array();
                    $nbpage_tableau = array();
                    $annee_tableau = array();
                    $comment_tableau = array();
                    $genre_tableau = array();
                    $auteur_tableau = array();
                    $resume_tableau = array();
                    

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
                        if( $tableau[$row]['nbpage'] >= $nbpage_min and $tableau[$row]['nbpage'] <= $nbpage_max) { 
                            array_push($nbpage_tableau, $tableau[$row]);
                        }
                    }

                    array_push($filtres_appliques, $nbpage_tableau);


                    for ($row = 0; $row < count($tableau); $row++) {
                        if( $tableau[$row]['annee'] >= $annee_min and $tableau[$row]['annee'] <= $annee_max) { 
                            array_push($annee_tableau, $tableau[$row]);
                        }
                    }
                    
                    array_push($filtres_appliques, $annee_tableau);


                    if($comment != ""){
                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['comment'], $comment) !== false){
                                array_push($comment_tableau, $tableau[$row]);
                                
                            }
                        }
                        array_push($filtres_appliques, $comment_tableau);
                    }


                    if($genre != ""){

                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['genre'], $genre) !== false){
                                array_push($genre_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $genre_tableau);
                    }


                    if($auteur != ""){

                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['auteur'], $auteur) !== false){
                                array_push($auteur_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $auteur_tableau);
                    }

                    if($resume != ""){
                        for ($row = 0; $row < count($tableau); $row++) {
                            if(stripos($tableau[$row]['resume'],$resume) !== false){ 
                                array_push($resume_tableau, $tableau[$row]);
                            }
                        }
                        array_push($filtres_appliques, $resume_tableau);
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
                            '<div class="liste_element" onclick="redirige(`litterature_element.php?id='.$result[$row]['id'].'`);">
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
                            '<div class="liste_element" onclick="redirige(`litterature_element.php?id='.$tableau[$row]['id'].'`);">
                                <img src="'.$tableau[$row]['poster'].'" alt="'.$tableau[$row]['titre'] .'">
                                    <h5 class="noteimg">'.$tableau[$row]['titre'].'</h5>
                                    <h5 class="noteh5">Votre note : <span> '.$tableau[$row]['note'].'</span></h5>
                                </div>';
                    }
                }
            }

            
            $f = $db->prepare("SELECT id_livres, date_realise FROM livres_users WHERE id_user = $id_user_active AND date_realise != 'null'");
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
                $vide = "<h3 style='margin-top:5rem;'>Vous n'avez pas encore marqué de livre comme réalisé!</h3>";
            }
        ?>

        <main class="page_main">
            <div id="fait_nonfait">
                <a id="afaire" class="nav_b" href="litterature.php">À FAIRE</a><span> | </span><a id="realise" class="nav_b" href="litterature_realise.php">RÉALISÉS</a>
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

                <button type="button" id="btn_ouvrir" onclick="ouvrir_modal('myModal');"><span title="Ajouter un livre">+</span></button>

            </div>

            <div id="filtre">
                <div id="m_filtre" style="display:none;">
                    <form method="post" id="modif_filtre" class="form_para">
                        
                        <div>
                            <div>
                                <label for="titre">TITRE</label>
                                <input type="text" name="titre" id="titre" class="cInput" require>
                            </div>
                            <div>
                                <label for="pays">AUTEUR</label>
                                <input type="text" name="auteur" id="auteur" class="cInput">
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
                                <label for="nbpage_min">NB PAGES MIN</label>
                                <input type="number" name="nbpage_min" id="nbpage_min" min="0" max="3000" value="100" step="1" class="cInput">
                            </div>
                            <div>
                                <label for="vote_max">NB PAGES MAX</label>
                                <input type="number" name="nbpage_max" id="nbpage_max" min="0" max="13000" value="400" step="1" class="cInput">
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
                <div class="modal-content modalc">
                    <span class="close" onclick="ferme_modal('myModal', 1);">&times;</span>

                    <form id="ajout_element_livres" method="post">
                        <div>
                            <div>
                                <label for="titre">TITRE</label>
                                <input type="text" name="titre" id="titre" class="cInput" required></br>
                            </div>
                            <div>
                                <label for="auteur">AUTEUR(E)</label>
                                <input type="text" name="auteur" id="auteur" class="cInput" required></br>
                            </div>
                            <div>
                                <label for="annee">ANNÉE DE PUBLICATION</label>
                                <input type="number" name="annee" id="annee" min="1700" max="2030" value="2015" step="1" class="cInput" required></br>
                            </div>
                            <div>
                                <label for="genre">GENRE</label>
                                <input type="text" name="genre" id="genre" class="cInput" required></br>
                            </div>
                            <div>
                                <label for="resume">RÉSUMÉ</label>
                                <textarea rows="2" name="resume" id="resume" class="cInput_text" required></textarea></br>
                            </div>
                            <div>
                                <label for="page">NOMBRE DE PAGES</label>
                                <input type="number" name="nbpage" id="nbpage" min="2" max="5000" value="400" step="1" class="cInput" required></br>
                            </div>
                            <div>
                                <label for="couverture">LIEN DE LA COUVERTURE</label>
                                <input type="text" name="poster" id="poster" class="cInput" required></br>
                            </div>
                        </div>

                        <input type="submit" name="add_element" class="bouton_a" value="SAUVEGARDER">

                        <?php echo $deja_existe?>
                    </form>
                </div>
            </div>

            <h1>LIVRES LUS</h1>

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
                            <input type="hidden" id="nom_livre_pese" name="nom" value="" />
                            <input type="hidden" id="poster_livre_pese" name="poster" value="" />
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