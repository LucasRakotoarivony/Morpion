<?php
	session_start();
	if(isset($_GET["gagnant"])) {
		$gagnant=$_GET["gagnant"];
		if($gagnant=="O" || $gagnant=="X") {
		$texte="Le gagnant est ".$gagnant.".";
		}
		else if($gagnant=="abandon") {
			$texte="Vous avez choisi d'abandonner.";
		}
		else {
			$texte="La partie s'est terminée sur une égalité.";
		}
		$texte.="<br> Si vous voulez une nouvelle partie, choisissez les paramètres.";
		session_unset(); 
		session_destroy();
		//si on vient de terminer une partie on affiche un message personnalisé
	}
	else {
		$texte="Choisissez les paramètres :";
		//sinon on affiche le message standard
	}

	
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Page d'accueil Morpion</title>
		<meta name="author" content="Lucas Rakotoarivony & Aurélien Faucheux">
		<meta name="viewport" content="width=device-width; initial-scale=1.0">
		<link rel="stylesheet" href="morpion.css">
		<script type="text/javascript" src="accueil.js"></script>
		<link type="text/css" rel="stylesheet" href="accueil.css" />

	</head>
	<body>
		<h1>Morpion</h1>
		<hr>
		
		<form action="morpion.php" method="get">
			<?php echo $texte."<br>" ?>
			<br>
			<img class="morpion" src="images/3x3.jpg" alt="morpion 3x3" border = "1" onclick="taille(3)">
			<img class="morpion" src="images/4x4.png" alt="morpion 4x4" border = "1" onclick="taille(4)">
			<img class="morpion" src="images/5x5.png" alt="morpion 5x5" border = "1" onclick="taille(5)">
			<img class="morpion" src="images/6x6.png" alt="morpion 6x6" border = "1" onclick="taille(6)">
			<img class="morpion" src="images/7x7.png" alt="morpion 7x7" border = "1" onclick="taille(7)">
			<img class="morpion" src="images/8x8.png" alt="morpion 8x8" border = "1" onclick="taille(8)"><br/>
			<!-- les images représentant les différentes tailles, il faut cliquer dessus pour jouer dans cette taille
				nous avons crées ces images nous-mêmes -->
			<br/>
			<div id="write">
			</div>
			<br/>
			<div>
			<input type="button" value="1 joueur" onclick="un();">
			<input type="button" value="2 joueurs" onclick="deux();"></div>
			<!-- on choisit combien de joueurs vont participer -->
			<br/>
			<p>
                Quel joueur commence ? 
				<input type="button" value=X onclick="motif('X')">
				<input type="button" value=O onclick="motif('O')">
			</p>
			<br>
			<input id="symbole" type="hidden" name="symbole" value="O" /> 
			<input id="taille" type="hidden" name="taille" value="3" /> 
			<input id="joueur" type="hidden" name="joueur" value="1" />
			<!-- les paramètres par défaut --> 
			<input type="submit" value="Envoyer">
		</form>
		<div class=aide>
                <p>
                <a href="help.html">Aide</a>
                </p>
            </div>
            <h2>Rakotoarivony Lucas & Faucheux Aurélien</h2>
	</body>
</html>