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
            global $db;
            $message = "";

            function check_email($mail){
                $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                if (filter_var($mail, FILTER_VALIDATE_EMAIL)){
                return true;
                }
            }

            if(isset($_POST['modifprenom'])){
                extract($_POST);
                $prenom = htmlspecialchars($prenom);

                $q = $db->prepare("SELECT password FROM user WHERE id = :id");
                $q->execute(["id" => $_SESSION['id_user_active']]);
                $result = $q->fetch();

                if(password_verify($motdep, $result["password"])){

                    $e = $db->prepare("UPDATE user SET prenom = ? WHERE id = ?");
                    $e->execute([$prenom, $_SESSION['id_user_active']]); 

                    $_SESSION['prenom'] = $prenom;
                    header("Location: parametre_compte.php?etat=succes");
                    die();

                }else{
                    header("Location: parametre_compte.php?etat=echec");
                    die();
                }
            }

            if(isset($_POST['modifemail'])){
                extract($_POST);

                if(check_email($email)){

                    $q = $db->prepare("SELECT password FROM user WHERE id = :id");
                    $q->execute(["id" => $_SESSION['id_user_active']]);
                    $result = $q->fetch();

                    if(password_verify($motdep, $result["password"])){

                        $e = $db->prepare("UPDATE user SET email = ? WHERE id = ?");
                        $e->execute([$email, $_SESSION['id_user_active']]); 

                        $_SESSION['email_active'] = $email;
                        header("Location: parametre_compte.php?etat=succes");
                        die();

                    }else{
                        header("Location: parametre_compte.php?etat=echec");
                        die();
                    }
                }else{
                    header("Location: parametre_compte.php?etat=echec");
                    die();
                }
            }

            if(isset($_POST['modifmotdep'])){
                extract($_POST);

                if($n_password == $cn_password){

                    $q = $db->prepare("SELECT password FROM user WHERE id = :id");
                    $q->execute(["id" => $_SESSION['id_user_active']]);
                    $result = $q->fetch();

                    if(password_verify($a_password, $result["password"])){

                        $options = [
                            'cost' =>12
                        ];

                        $hashpass = password_hash($n_password, PASSWORD_BCRYPT, $options);

                        $e = $db->prepare("UPDATE user SET password = ? WHERE id = ?");
                        $e->execute([$hashpass, $_SESSION['id_user_active']]); 

                        header("Location: parametre_compte.php?etat=succes");
                        die();

                    }else{
                        header("Location: parametre_compte.php?etat=echec");
                        die();
                    }
                }
                header("Location: parametre_compte.php?etat=echec");
                die();
            }

            if(isset($_POST['supprime'])){

                extract($_POST);

                $aa = $db->prepare("SELECT id FROM films_listes WHERE id_user = :id_user");
                $aa->execute(['id_user' => $_SESSION['id_user_active']]);
                $resultaa = $aa->fetch();

                $q = $db->prepare("DELETE FROM films_elements_listes WHERE id_liste = ?");
                $q->execute([$resultaa['id']]);

                $r = $db->prepare("DELETE FROM films_listes WHERE id = ?");
                $r->execute([$resultaa['id']]);

                $s = $db->prepare("DELETE FROM films_users WHERE id_user = ?");
                $s->execute([$_SESSION['id_user_active']]);

                $t = $db->prepare("DELETE FROM user WHERE id = ?");
                $t->execute([$_SESSION['id_user_active']]);

                session_unset();
                session_destroy();
                header("Location: fermeture_compte.php");
                die();
            }
        
        
        ?>

        <main class="pageb_main">

            <div class="titre_page">
                <h1>PARAMÈTRES DU COMPTE <img src="../assets/img/reglage.svg" alt="Reglage" id="Reglage" width="35px"></h1>
            </div>

            <?php

                if(isset($_GET["etat"])){

                    if($_GET['etat'] == 'succes'){
                        echo "<h3 style='margin-bottom:2.5rem;'><img src='../assets/img/check.svg' alt='Vérifié' width='20px'>Les modifications ont bien été apportées</h3>";

                    }else{
                        echo "<h3 style='color:red; margin-bottom:2.5rem;'>Les modifications n'ont pas pu être apportées</h3>";
                    }
                }
             ?> 

            <div id="param">

                <h3 class="hparam">VOTRE PRÉNOM:<br>
                    <span><?php echo $_SESSION['prenom'] ?></span></h3>

                <div id="m_prenom" style="display:none;">
                    <form method="post" id="modif_prenom" class="form_para">
                        <label for="prenom">VOTRE NOUVEAU PRENOM</label>
                        <input type="text" name="prenom" id="prenom" required class="cInput"></br>
                        <label for="motdep">MOT DE PASSE</label>
                        <input type="password" name="motdep" id="motdep" required class="cInput"></br>
                        <input type="submit" name="modifprenom" id="modifprenom" class="bouton_a btn_param b" value="MODIFIER VOTRE PRENOM">
                    </form>
                </div>

                <button id="change_prenom" onclick="ouvre('m_prenom','change_prenom', 'MODIFIER', 'inline', 'ANNULER');" type="button" class="bouton_a  btn_param">MODIFIER</button>

                <h3 class="hparam">VOTRE COURRIEL: <br>
                    <span><?php echo $_SESSION['email_active'] ?></span></h3>

                <div id="m_email" style="display:none;">
                    <form method="post" id="modif_email" class="form_para">
                        <label for="email">VOTRE NOUVEAU COURRIEL</label>
                        <input type="email" name="email" id="email" required class="cInput"></br>
                        <label for="motdep">MOT DE PASSE</label>
                        <input type="password" name="motdep" id="motdep" required class="cInput"></br>
                        <input type="submit" name="modifemail" id="modifemail" class="bouton_a  btn_param b" value="MODIFIER VOTRE COURRIEL">
                    </form>
                </div>

                <button  id="change_courriel" onclick="ouvre('m_email','change_courriel', 'MODIFIER', 'inline', 'ANNULER');" type="button" class="bouton_a  btn_param">MODIFIER</button>

                <h3 class="hparam">VOTRE MOT DE PASSE</h3>

                <div id="m_motdep" style="display:none;">
                    <form method="post" id="modif_motdep" class="form_para">
                        <label for="n_password">VOTRE NOUVEAU MOT DE PASSE</label>
                        <input type="password" name="n_password" id="n_password" required class="cInput"></br>
                        <label for="cn_password">CONFIRMER VOTRE NOUVEAU MOT DE PASSE</label>
                        <input type="password" name="cn_password" id="cn_password" required class="cInput"></br>
                        <label for="a_password">VOTRE ANCIEN MOT DE PASSE</label>
                        <input type="password" name="a_password" id="a_password" required class="cInput"></br>
                        <input type="submit" name="modifmotdep" id="modifmotdep" class="bouton_a  btn_param b" value="MODIFIER VOTRE MOT DE PASSE">
                    </form>
                </div>

                <button  id="change_motdep" onclick="ouvre('m_motdep','change_motdep', 'MODIFIER', 'inline', 'ANNULER');" type="button" class="bouton_a  btn_param">MODIFIER</button>

                <div id="supprim">

                <button type="button" class="bouton_a btn_supp" onclick="ouvrir_modal('myModal');">SUPPRIMER LE COMPTE</button>

                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="ferme_modal('myModal', 0);">&times;</span>

                            <form method="post" id="sup_compte">
                                <h2>Voulez-vous vraiment supprimer votre compte?</h2>
                                <label for="motdepasse">Rentrez votre mot de passe</label>
                                <input type="password" name="motdepasse" id="motdepasse" required class="cInput"></br>
                                <input type="submit" name="supprime" id="supprime" class="bouton_a" value="SUPPRIMER LE COMPTE">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
                
        </main>

        <?php require 'footer.php'; ?>

        <script src="../assets/js/main.js"></script>
    </body>
</html>