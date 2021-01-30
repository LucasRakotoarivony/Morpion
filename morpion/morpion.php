<?php
session_start();
?>

<!DOCTYPE html>
<html>

  <head>
    <title>Morpion</title>
    <meta charset="utf-8" />
    <link type="text/css" rel="stylesheet" href="morpion.css" />
    <script type="text/javascript" src="morpion.js"></script>
    <link type="text/css" rel="stylesheet" href="morpion.css" />
  </head>

<body>

<h1>Morpion</h1>

<?php
include("morpion_inc.php");


if(isset($_GET["taille"])) {
  $taille=$_GET["taille"];
 }
else if(!empty($_SESSION)) {$taille=$_SESSION["taille"];}
else {$taille=3;}

//on récupère la taille, par défaut la valeur est 3.


if(isset($_GET["joueur"])) {
  $nb=$_GET["joueur"];
 }
else if(!empty($_SESSION)) {$nb=$_SESSION["joueur"];}
else {$nb=1;}

//récupère le nombre de joueurs par défaut il y'a un seul joueur.


if(isset($_GET["symbole"])) {
  $symbole=$_GET["symbole"];
  $type=$symbole;
 }
else {$symbole="O";$type="O";}

//récupère qui va jouer en premier O ou X


if(empty($_SESSION)) {  
  for($l=0;$l<$taille;$l++) {
    for($m=0;$m<$taille;$m++) {
      $w=$l.",".$m;
      $_SESSION[$w]="white";
    }
  }
  $_SESSION["taille"]=$taille;  
  $_SESSION["joueur"]=$nb;
  $_SESSION["type"]=$type;
  $_SESSION["fin"]=false;
}

//si on est au début du jeu remplit le tableau de blanc
//ensuite on stocke dans une session les informations utiles


$permission=true;
if ( isset($_GET["ligne"]) ) {
  if($_GET["ligne"]<$taille && $_GET["colonne"]<$taille) {
    if($nb==2) {
        $type=$_SESSION["type"];
        if($_SESSION["type"]=="X") {$_SESSION["type"]="O";}
        else if($_SESSION["type"]=="O") {$_SESSION["type"]="X";}   
    }
    else {
      $type="X";
    }

    $res=$_GET["ligne"].",".$_GET["colonne"];
    if($_SESSION[$res]=="white") {
      $_SESSION[$res]=$type;
	if($type=="X") {
      echo "<p id=joueur>Le joueur a joué en ({$_GET["ligne"]},{$_GET["colonne"]})</p>";
	}
	else {
         echo "<p id=ordi>Le joueur a joué en ({$_GET["ligne"]},{$_GET["colonne"]})</p>";
	}
    }
    else {
      echo("Choissisez une autre case.<br>");
      $permission=false;
    }
  }
  else {
    echo("Choissisez une case.<br>");
    $permission=false;
  }
}

//si on joue à deux joueurs on effectue une alternance des types (soit O, soit X), sinon X c'est toujours X qui joue
//si la case demandé est vide on joue dedans sinon on affiche on message d'erreur et grâce à permission on stoppe le jeu


$fX="";$fO="";
for($n=0;$n<$taille;$n++) {
  $fX.="X";
  $fO.="O";
}

//crée deux variables représentant la victoire soit de X, soit de O
//voir plus en détail dans morpion_inc.php.

if($nb==1 && $permission) {
  $resultat=parcourir($taille,$fX,$fO);
   if(!($resultat[0]==$fX || $resultat[1]==$fX || in_array($fX, $resultat[2]) || in_array($fX, $resultat[3]))) {
      $resultat=unjoueur($taille,$fX,$fO,$symbole,parcourir($taille,$fX,$fO));
   }
}
else {
  $resultat=parcourir($taille,$fX,$fO);
}

//si on joue à un seul joueur on utilise la fonction unjoueur (détaillé dans morpion_inc.php)
//si on joue à deux, cela est plus simple à gérer donc on utilise la fonction parcourir (détaillé dans morpion_inc.php)
//l'ensembles des jeux est stocké dans la variable résultat


  $fin='<input id="abandon" type="hidden" name="gagnant" value="abandon" />
  <input type="submit" value="Abandonner" />';
  if($resultat[0]==$fX || $resultat[1]==$fX || in_array($fX, $resultat[2]) || in_array($fX, $resultat[3])) {
    $fin='  <input id="abandon" type="hidden" name="gagnant" value="X" />
  <input type="submit" value="Fin" />';
  echo ("Félicitations X vous avez gagné.<br>");
  echo("Cliquez sur le bouton Fin pour revenir à la page d'accueil.<br>");
  $_SESSION["fin"]=true;
  }
  else if($resultat[0]==$fO || $resultat[1]==$fO || in_array($fO, $resultat[2]) || in_array($fO, $resultat[3])) {
    $fin='  <input id="abandon" type="hidden" name="gagnant" value="O" />
  <input type="submit" value="Fin" />';
  if($nb==2) {
    echo ("Félicitations O vous avez gagné.<br>");
  }
  else {
    echo("Dommage, recommencez pour essayer de gagner.<br>");
  }
  echo("Cliquez sur le bouton Fin pour revenir à la page d'accueil.<br>");
  $_SESSION["fin"]=true;
  }
  if(!in_array("white", $_SESSION)) {
     $fin='  <input id="abandon" type="hidden" name="gagnant" value="egalite" />
  <input type="submit" value="Fin" />';
  echo("La partie est terminée. Aucun gagnant.<br>");
  echo("Cliquez sur le bouton Fin pour revenir à la page d'accueil.<br>");
  $_SESSION["fin"]=true;
  }
  

//les trois cas de fin classique
//si dans le tableau résultat fX est présent cela signifie que X a gagné, idem pour O
//résultat contient également deux tableaux à l'intérieur les éléments 2 et 3 (voir en détail dans morpion_inc.php)
//et si il n'y a plus de blanc cela signifie qu'il y'a eu une égalité 
//$fin répresente le bouton soit abandonner, soit fin

echo "<br>";
echo (affichage($taille,$_SESSION["fin"]));
//on affiche le jeu (fonction affichage détaillé dans morpion_inc.php)
//le paramètre fin permet de ne pas continuer de jouer si la partie est fini
?>

<form action="morpion.php" method="get">
  <input id="ligne" type="hidden" name="ligne" value="9" />
  <input id="colonne" type="hidden" name="colonne" value="9" />
  <input type="submit" value="Jouer" />
</form>
<form action="accueil.php" method="get">
  <?php echo $fin ?>
</form>

</body>
</html>
