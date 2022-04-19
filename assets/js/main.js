const afaire = document.getElementById("afaire");
const realise = document.getElementById("realise");

if(currentLocation.includes("cinema.php") || currentLocation.includes("liste.php") || currentLocation.includes("litterature.php") || currentLocation.includes("voyage.php") || currentLocation.includes("gastronomie.php") || currentLocation.includes("jeux.php") || currentLocation.includes("spectacles.php") || currentLocation.includes("activite.php") ){
    afaire.classList.add("activeb");
}

if(currentLocation.includes("realise.php") || currentLocation.includes("element.php")){
    realise.classList.add("activeb");
}

function ouvrir_modal(a){
	var modal = document.getElementById(a);

	modal.style.display = "block";
}

function ferme_modal(a, b){
	let modal = document.getElementById(a);
	
	modal.style.display = "none";
	if (b = 1){
		window.location.reload();
	}
}

function ouvre(div, btnouvre, message, styledis, ferme) {
    var spanb = document.getElementById(div);
    var btnouvre = document.getElementById(btnouvre);

    if(spanb.style.display == "none") {
        spanb.style.display = styledis;
        btnouvre.innerText = ferme;
    } else {
        spanb.style.display = "none";
        btnouvre.innerText = message;
    }
}; 

function envoie_donnee(vise_a, valeur_a, vise_b, valeur_b){
	var a = document.getElementById(vise_a);
	var b = document.getElementById(vise_b);

	a.value = valeur_a;
	b.value = valeur_b;
};

function redirige(url){
	window.location.href = url;
}

//API FILMS ET SÉRIES

const API_KEY = "&api_key=51e44724607a37d263fcb02957422f3f";
const BASE_URL = "https://api.themoviedb.org/3"
const search_movie_url = BASE_URL + "/search/movie?" + API_KEY;
const search_tv_url = BASE_URL + "/search/tv?" + API_KEY;
const IMG_url = "https://image.tmdb.org/t/p/w500";

const form = document.getElementById('ajout_element_film');
const search_film = document.getElementById('recherche_film');
const search_serie = document.getElementById('recherche_serie');
const section = document.getElementById('resultat_recherche');

form.addEventListener("submit", (e) =>{
	e.preventDefault();

	if(search_film != null){

		const searchTerm = search_film.value;

		if(searchTerm){
			getMovies(search_movie_url + "&query=" + searchTerm);

		}
	}

	if(search_serie != null){

		const searchTerm = search_serie.value;
	
		if(searchTerm){
			getTv(search_tv_url + "&query=" + searchTerm);
	
		}
	}
})

function getMovies(url){

	fetch(url).then(res => res.json()).then(data => {
		showMovies(data.results);
	})
}

function showMovies(data) {
	section.innerHTML ="";

	if (data.length != 0){

		data.forEach(movie => {
			const {title, poster_path, vote_average, overview, genre_ids, origin_country, release_date} = movie;
			const movieEL = document.createElement('div');
			movieEL.classList.add('movie');

			const annee = release_date.substring(0, 4);
			const genre = findgenreMovie(genre_ids);

			movieEL.innerHTML = `
			<form method="POST">
			<input type="hidden" name="nom" value="${title}" />
			<input type="hidden" name="poster" value="${IMG_url+poster_path}" />
			<input type="hidden" name="vote" value="${vote_average}" />
			<input type="hidden" name="resume" value="${overview}" />
			<input type="hidden" name="pays" value="${origin_country}" />
			<input type="hidden" name="genre" value="${genre}" />
			<input type="hidden" name="annee" value="${annee}" />
			<button type="submit" name="add_element" id="add_element">${title}<br><span>${annee}</span></button>
			</form>
			`;
			
			section.appendChild(movieEL);
		});

	}else{
		section.innerHTML = "<h2 style='font-size:20px;'>Aucun résultat</2>";
	}
}

function getTv(url){

	fetch(url).then(res => res.json()).then(data => {
		showTv(data.results);
	})
}

function showTv(data) {
	section.innerHTML ="";

	if (data.length != 0){

		data.forEach(movie => {
			const {name, poster_path, vote_average, overview, origin_country, genre_ids, first_air_date} = movie;
			const movieEL = document.createElement('div');
			movieEL.classList.add('movie');

			const annee = first_air_date.substring(0, 4);
			const genre = findgenreTv(genre_ids);

			movieEL.innerHTML = `
			<form method="POST">
			<input type="hidden" name="nom" value="${name}" />
			<input type="hidden" name="poster" value="${IMG_url+poster_path}" />
			<input type="hidden" name="vote" value="${vote_average}" />
			<input type="hidden" name="resume" value="${overview}" />
			<input type="hidden" name="pays" value="${origin_country}" />
			<input type="hidden" name="genre" value="${genre}" />
			<input type="hidden" name="annee" value="${annee}" />
			<button type="submit" name="add_element" id="add_element" value="Submit">${name}<br><span>${annee}</span></button>
			</form>
			`;
			
			section.appendChild(movieEL);
		});
	}else{
		section.innerHTML = "<h2 style='font-size:20px;'>Aucun résultat</2>";
	}
}


function findgenreMovie($tableau){

	$genre = "";
	$tableau.forEach(element =>{
		switch (element) {
			case 28:
				$genre = $genre + "-Action";
			  	break;
			case 12:
				$genre = $genre + "-Aventure";
			  	break;
		 	case 16:
				$genre = $genre + "-Animation";
			  	break;
			case 35:
				$genre = $genre + "-Comédie";
			  	break;
			case 80:
				$genre = $genre + "-Crime";
			  	break;
		 	case 99:
				$genre = $genre + "-Documentaire";
			  	break;
			case 18:
				$genre = $genre + "-Drame";
			  	break;
			case 10751:
				$genre = $genre + "-Famille";
			  	break;
		 	case 14:
				$genre = $genre + "-Fantastique";
			  	break;
			case 36:
				$genre = $genre + "-Histoire";
			  	break;
			case 27:
				$genre = $genre + "-Horreur";
			  	break;
		 	case 10402:
				$genre = $genre + "-Musique";
			  	break;
			case 9648:
				$genre = $genre + "-Mystere";
			  	break;
			case 10749:
				$genre = $genre + "-Romance";
			  	break;
		 	case 878:
				$genre = $genre + "-Science Fiction";
			  	break;
			case 10770:
				$genre = $genre + "-Téléfilm";
			  	break;
			case 53:
				$genre = $genre + "-Suspence";
			  	break;
		 	case 10752:
				$genre = $genre + "-Guerre";
			  	break;
			case 37:
				$genre = $genre + "-Western";
			  	break;
			default:
				$genre = "";
		
		}
	})
	return $genre;	
}

function findgenreTv($tableau){

	$genre = "";
	$tableau.forEach(element =>{
		switch (element) {
			case 10759:
				$genre = $genre + "-Action et Aventure";
			  	break;
			case 16:
				$genre = $genre + "-Animation";
			  	break;
		 	case 35:
				$genre = $genre + "-Comédie";
			  	break;
			case 80:
				$genre = $genre + "-Crime";
			  	break;
		 	case 99:
				$genre = $genre + "-Documentaire";
			  	break;
			case 18:
				$genre = $genre + "-Drame";
			  	break;
			case 10751:
				$genre = $genre + "-Famille";
			  	break;
		 	case 10762:
				$genre = $genre + "-Pour enfants";
			  	break;
			case 9648:
				$genre = $genre + "-Mystère";
			  	break;
			case 10763:
				$genre = $genre + "-Actualité";
			  	break;
		 	case 10764:
				$genre = $genre + "-Téléréalité";
			  	break;
			case 10765:
				$genre = $genre + "-Science Fiction et Fantastique";
			  	break;
			case 10766:
				$genre = $genre + "-Feuilleton";
			  	break;
		 	case 10767:
				$genre = $genre + "-Talkshow";
			  	break;
			case 10768:
				$genre = $genre + "-Politique";
			  	break;
			case 37:
				$genre = $genre + "-Western";
			  	break;
			default:
				$genre = "";
		}
	})
	return $genre;	
}