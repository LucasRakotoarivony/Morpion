function un(){
	let nombre=document.getElementById("joueur");
	nombre.value=1;
}
function deux() {
	let nombre=document.getElementById("joueur");
	nombre.value=2;
}
//dans ces deux fonctions on modifie le nombre de joueurs

function taille(tl) {
	let tail=document.getElementById("taille");
	tail.value=tl;
	let siz=document.getElementById("write");
	siz.innerHTML="La taille choisie est "+tl+"x"+tl;

}
function motif(mot) { 
	let sb=document.getElementById("symbole");
	sb.value=mot;
}

//dans ces deux fonctions on modifie la taille et le symbole de départ