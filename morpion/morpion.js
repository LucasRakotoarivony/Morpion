
function click_at(ligne, colonne, image) {
	var lg = document.getElementById("ligne");
	var co = document.getElementById("colonne");
	lg.value=ligne;
	co.value=colonne;
}
//affecte les coordonnées de la case cliquées aux paramètres ligne et colonne

function clique(ligne,colonne) {
	var texte="La case ("+ligne+","+colonne+") a deja ete coché";
	alert(texte);

}
//affiche un message d'erreur si on clique sur une case déjà coché