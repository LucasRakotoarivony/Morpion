<?php
function affichage($taille,$fin) {
	 $action="<div id='grid'>
<table cellspacing='0' border='2' cellpadding='0'>
  <tbody>";

    for($row=0;$row<$taille;$row++) {
      $action.="<tr>";
      for($ja=0;$ja<$taille;$ja++) {
        $ind=$row.",".$ja;
        $action.='<td><img align="center" src="images/';
        if(isset($_SESSION[$ind])) {
          $col=$_SESSION[$ind];
        }
        if($col!="white" || $fin==true) {
          $action.=$col.'.png" onclick="clique(';
          $action.=$row.",".$ja.')" /></td>';
        }
        else {
          $action.=$col.'.png" onclick="click_at(';
          $action.=$row.",".$ja.',this)" /></td>';
        }
      }
      $action.="</tr>";
    }
    $action.= "</tbody>
</table>
</div>";
return $action;
}
//dans cette partie on affiche la grille du morpion
//$fin est égale à true si la partie est terminée, dans ce cas on empêche l'utilisateur de continuer de jouer.
//à chaque case on attribue un élément onclick, si la case est blanche on utilise click_at sinon on utilise clique qui déclenche une erreur.


function parcourir($taille,$fX,$fO) {
  $hor="";$ver="";
$horizontal=array();$vertical=array();
$diag="";$da="";
  $danger=array();$gagner=array();
for($i=0;$i<$taille;$i++) {
  $hor="";$ver="";
  $c=$i.",".$i;
  $d=$i.",".($taille-1-$i);
  $q=substr_replace($fX, "W", $i,1);$s=substr_replace($fO, "W", $i,1);
  $danger[]=$q;$gagner[]=$s;



  if($_SESSION[$c]=="X") {
      $diag.="X";
    }
  else if($_SESSION[$c]=="O") {
    $diag.="O";
  }
  else {$diag.="W";}

  if($_SESSION[$d]=="X") {
      $da.="X";
    }
  else if($_SESSION[$d]=="O") {
    $da.="O";
  }
  else {$da.="W";}


  for($j=0;$j<$taille;$j++) {
    $a=$i.",".$j;
    $b=$j.",".$i;
    if($_SESSION[$a]=="X") {$hor.="X";}
     else if($_SESSION[$a]=="O") {
        $hor.="O";
    }
    else {$hor.="W";}

    if($_SESSION[$b]=="X") {$ver.="X";}
    else if($_SESSION[$b]=="O") {
        $ver.="O";
    }
    else {$ver.="W";}

  }
  $horizontal[]=$hor;$vertical[]=$ver;
}
$liste=array($diag,$da,$horizontal,$vertical,$danger,$gagner);
return $liste;
}
//Dans cette fonction on effectue plusieurs choses.
//Tout d'abord on parcourt la grille à l'aide de for et on crée 4 variables $diag qui représente la diagonale de en haut à gauche à en bas à droite
//$da représente l'autre diagonale, $horizontal représente l'ensemble des lignes de la première à la dernière et $vertical représente l'ensemble des colonnes
//si on rencontre du blanc on rajoute W à la variable,une X on rajoute X et un O on rajoute un O
//par exemple si sur la diagonale se trouve dans l'ordre (1:blanc 2:O 3:X), alors $diag sera égale à WOX

//Ensuite on crée les tableaux $danger et $gagner qui représente respectivement les situations ou l'ordinateur doit défendre ou attaquer.
//dans $danger représente tous les string de forme XXW, XWX, WXX, c'est à dire les cas ou l'utilisateur peut aligner 3 X et ainsi gagner.
//$gagner lui contient OOW,OWO,WOO dans ces cas l'ordinateur peut gagner.

//ces exemples valent pour le cas où $taille=3 cependant le fonctionnement est analogue pour des tailles différentes.
//Finalement on retourne un tableau contenant toutes les variables crés


function unjoueur($taille,$fX,$fO,$symbole,$tableau) {
  $diag=$tableau[0];$da=$tableau[1];
  $horizontal=$tableau[2];$vertical=$tableau[3];
  $danger=$tableau[4];$gagner=$tableau[5];

  $horgagner=(array_intersect($horizontal, $gagner));
  $horperdre=(array_intersect($horizontal, $danger));
  $vergagner=(array_intersect($vertical, $gagner));
  $verperdre=(array_intersect($vertical, $danger));
 
  echo "<p id=ordi>";
  $a=0;$b=0;$ind="";
  if(in_array($diag, $gagner)) {
    $ind=diagonale($diag);
  }
  else if(in_array($da, $gagner)) {
    $ind=invdiagonale($da,$taille);
  }
  else if(!empty($horgagner)) {
   $ind=horizontalite($horizontal,$gagner);
  }
  else if(!empty($vergagner)) {
    $ind=verticalite($vertical,$gagner);
  }
  
  else if(in_array($diag, $danger)) {
    $ind=diagonale($diag);
  }
  else if(in_array($da, $danger)) {
    $ind=invdiagonale($da,$taille);
  }
  else if(!empty($horperdre)) {
   $ind=horizontalite($horizontal,$danger);
  }
  else if(!empty($verperdre)) {
    $ind=verticalite($vertical,$danger);
  }
  else if(in_array("white", $_SESSION)) {
    if($symbole=="O") {
      $ra=rand(0,$taille-1);$rb=rand(0,$taille-1);
      while($_SESSION[$ra.",".$rb]!="white") {
        $ra=rand(0,$taille-1);
        $rb=rand(0,$taille-1);
      }
      echo "L'ordinateur a joué en ({$ra},{$rb})<br>";
      $ind=$ra.",".$rb;
    }
  }
  echo "</p>";
  $_SESSION[$ind]="O";
  


  if(strlen($ind)>2) {
  $a=$ind[0];
  $b=$ind[2];
  if($a==$b) {
    $diag=substr_replace($diag, "O", $a,1);
  }
  if($a+$b==$taille-1) {
    $da=substr_replace($da, "O", $a,1);
  }
  $horizontal[$a]=substr_replace($horizontal[$a], "O", $b,1);
  $vertical[$b]=substr_replace($vertical[$b], "O", $a,1);
}
$final=array($diag,$da,$horizontal,$vertical);
return $final;
}
//cette méthode est utilisé pour faire jouer l'ordinateur.
//Tout d'abord on récupères les variables issues de $tableau qui représentes les variables crées par la fonction parcourir.
//Ensuite on effectue une série de if.
//Premièrement l'ordinateur vérifie si il existe une position qui lui permettrait de gagner si oui il utilise la fonction lui permettant
//Ensuite si il ne peut pas gagner il regarde si il existe une position où l'utilisateur peut gagner à son prochain mouvement si oui il utilise la fonction correspondante pour le contrer.
//Sinon il joue de manière aléatoire

//Dans le cas classique d'un 3*3 on aurait facilement pu rendre l'ordinateur plus difficile à vaincre en lui demandant par exemple de jouer au centre si cela est possible, cependant nous ne l'avons pas fait car cela rend les parties répétitives et si on joue en 4*4 cela ne présente plus un grand intérêt.

function diagonale($diagonal) {
    $ind=strpos($diagonal, "W");
    $res=$ind.",".$ind;
    echo "L'ordinateur a joué en ({$ind},{$ind})<br>";
    return $res;
}
//on détermine la position du W dans $diagonal et on joue donc un O à la position correspondante à l'aide de $res dans le cas de la première diagonale

function invdiagonale($diagonal,$taille) {
    $ind=strpos($diagonal, "W");
    $scd=$taille-1-$ind;
    $res=$ind.",".$scd;
    echo "L'ordinateur a joué en ({$ind},{$scd})<br>";
    return $res;
}
//idem pour la diagonale opposé

function horizontalite($horizontal,$jeu) {
    $inter=array_intersect($horizontal, $jeu);
    $position=array_keys($inter);
    $ind=strpos($inter[$position[0]], "W");
    $res=$position[0].",".$ind;
    str_replace("W", "O",$inter[$position[0]] );
    echo "L'ordinateur a joué en ({$position[0]},{$ind})<br>";
    return $res;
}
//pour les lignes cela est plus compliqué, on doit alors croiser les tableaux $horizontal et $jeu
//si on veut attaquer $jeu vaudra $gagner et si on veut défendre on utilise $danger

function verticalite($vertical,$jeu) {
  $inter=array_intersect($vertical, $jeu);
    $position=array_keys($inter);
    $ind=strpos($inter[$position[0]], "W");
    $res=$ind.",".$position[0];
    str_replace("W", "O", $inter[$position[0]]);
    echo "L'ordinateur a joué en ({$ind},{$position[0]})<br>";
    return $res;
}
//de manière analogue pour la verticalité

?>