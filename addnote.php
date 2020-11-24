<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirigez-le vers la page de connexion
    if(!isset($_SESSION["username"])){
        header("Location: login.php");
        exit(); 
    }

    $dsn="mysql:host=localhost;dbname=notepro";
    $user="notepro_user";
    $mdp="password";
    try{
        $connexion = new PDO ($dsn,$user,$mdp);
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $smt_eleve = $connexion->prepare('SELECT id_user,username From utilisateurs WHERE grade=3    ');
        $smt_eleve->execute();
        $data_eleve = $smt_eleve->fetchAll();

        $smt_matiere = $connexion->prepare('SELECT matieres From matiere_prof WHERE prof='. $_SESSION['id']);
        $smt_matiere->execute();
        $data_matiere = $smt_matiere->fetch();
        $matiere = $data_matiere['matieres'];

    }catch(PDOException $e){
        die($dsn."erreur".$e->getMessage());
    }
    if(isset($_POST["note"]) && isset($_POST["eleve"])){
        $eleve = $_POST["eleve"];
        $note = $_POST["note"];
        $prof= $_SESSION['id'];
        try{
            $connexion = new PDO ($dsn,$user,$mdp);
            $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //insertion dans la table utilisateur
            $req="INSERT INTO notes(matieres,notes,eleves,prof) values (:m , :n, :e, :p)";
            $traitement = $connexion->prepare($req);
            $traitement->bindvalue(':m',$matiere);
            $traitement->bindvalue(':n',$note);
            $traitement->bindvalue(':e',$eleve);
            $traitement->bindvalue(':p',$prof);
            $traitement->execute();
            $connexion=NULL;
            $res=true;
        } catch(PDOException $e){
            die($dsn."erreur".$e->getMessage());
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <title>NotePro</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <link rel="stylesheet" href="https://bootswatch.com/4/sketchy/bootstrap.min.css" crossorigin="anonymous">
        <!--<link rel="stylesheet" href="/src/style.css" crossorigin="anonymous">-->
        <script src="https://kit.fontawesome.com/058ba3cd65.js" crossorigin="anonymous"></script>
    </head>
	<body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="/">NotePro</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">NotePro
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Pricing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dropdown</a>
                        <div class="dropdown-menu">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="text" placeholder="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
        <div class="jumbotron border-0">
            <div class="card mb-3">
                <h3 class="card-header">Ajout d'une note</h3>
                <div class="card-body bg-lightdark">
                    <img src=""/>
                </div>
                <div class="card-body bg-lightdark">
                    <form action="" method="post">
                        <fieldset>
                            <div class="form-group">
                                <label for="eleve">Selection de l'éleve</label>
                                <select class="form-control" id="eleve" name="eleve">
                                    <?php foreach ($data_eleve as $row): ?>
                                        <option value="<?=$row["id_user"]?>"><?=$row["username"]?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Note(20)</label>
                                <input type='number' class="form-control" id="note" name="note" placeholder="Entrez la note"min="0" max="20" step="0.1">
                            </div>
                            <button type="submit" class="btn btn-success">Valider</button>
                            <?php if (! empty($message)) { ?>
                                <p class="errorMessage"><?php echo $message; ?></p>
                            <?php } ?>
                        </fieldset>
                    </form>
                </div>
                <div class="card-footer text-muted">
                    <?php
                        if($res==true){
                            print "
                            <p class='lead text-success'>Ajout réussi !</p>
                            ";
                        }
                    ?>   
                </div>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    </body>
</html>