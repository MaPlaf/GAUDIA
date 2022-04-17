<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<title>GAUDIA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link rel="stylesheet" href="assets/css/style.css" />
        <link rel="icon" type="./image/svg+xml" sizes="32x32" href="assets/img/icon.svg">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Roboto&display=swap" rel="stylesheet">
	</head>


	<body>

    <main id="pageInscri">

        <section id="whyInscri">
            <img src="assets/img/logo_slogan.svg" alt="Logo Gaudia Slogan" id="logo_gaudia_slogan">

            <ul>
                <li>Stockez, notez et organisez vos loisirs et divertissements</li>
                <li>Fixez-vous des objectifs en vous créant des listes à réaliser</li>
                <li>Retrouvez facilement les recettes que vous avez fait, les films que vous avez écouté, les livres que vous avez lu et beaucoup plus encore</li>
            </ul>

            <img src="assets/img/icon_loisirs.svg" alt="icon_loisir" id="iconloisir">
        </section>

        <section id="inscriForm">

            <h3 class="ins">Je crée mon compte<br> maintenant!</h3>

            <?php 

                if(isset($_SESSION['prenom'])){
                    header("Location: pages/index.php");
                    die();
                }

                function check_email($mail){
                    $mail = filter_var($mail, FILTER_SANITIZE_EMAIL);
                    if (filter_var($mail, FILTER_VALIDATE_EMAIL)){
                    return true;
                    }
                }

                if(isset($_POST["inscri"])){

                    extract($_POST);
                    $prenom = htmlspecialchars($prenom);

                    if(!empty($password) && !empty($cpassword) && !empty($email) && !empty($prenom)){

                        if(check_email($email)){

                            if($password == $cpassword){
                                
                                $options = [
                                    'cost' =>12
                                ];

                                $hashpass = password_hash($password, PASSWORD_BCRYPT, $options);

                                include 'db/database.php';
                                global $db;

                                $c = $db->prepare("SELECT email FROM user WHERE email = :email");
                                $c->execute([ 'email' => $email]);
                                $result = $c->rowCount();

                                if($result == 0){

                                    $q = $db->prepare("INSERT INTO user(email, password, prenom) VALUES(:email, :password, :prenom)");
                                    $q->execute([
                                    'email' => $email,
                                    'password' => $hashpass,
                                    'prenom' => $prenom
                                ]);

                                    $_SESSION['message'] = "Votre compte a été créé avec succès";
                                    $_SESSION['prenom'] = $prenom;
                                    $_SESSION['email_active'] = $email;

                                    $e = $db->prepare("SELECT * FROM user WHERE email = :email");
                                    $e->execute(["email" => $email]);
                                    $result_id = $e->fetch();

                                    $_SESSION['id_user_active'] = $result_id["id"];

                                    header("Location: pages/index.php");
                                    die();
                                    
                                }else{
                                    echo"<p style='color:red;'>Ce courriel est déjà relié à un<br> compte existant</p>";
                                }
                            }else{
                                echo"<p style='color:red;'>Veuillez rentrer un courriel valide</p>";
                            }
                        }else{

                        }
                    }
                }


                ?>

            
                <form method="post" id="inscription">
                    <label for="prenom">PRÉNOM</label>
                    <input type="text" name="prenom" id="prenom" required class="cInput"></br>
                    <label for="email">COURRIEL</label>
                    <input type="email" name="email" id="email" required class="cInput"></br>
                    <label for="password">MOT DE PASSE</label>
                    <input type="password" name="password" id="password" required class="cInput"></br>
                    <label for="cpassword">CONFIRMER LE MOT DE PASSE</label>
                    <input type="password" name="cpassword" id="cpassword" required class="cInput"></br>
                    <input type="submit" name="inscri" id="inscri" class="bouton_a" value="CRÉER MON COMPTE">
                </form>

                <p>Vous avez déjà un compte?<br>
                    <a href="connexion.php">Connectez-vous</a>
                </p>

            </section>

        </main>

    </body>
</html>