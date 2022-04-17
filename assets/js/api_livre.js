const API_BOOKS = "https://www.googleapis.com/books/v1/volumes?q=";
const API_BOOKS_KEY = "&key=AIzaSyDSgfBbjWrMcSCPhbJLIwLrd_zZza36EuA";
API_BOOKS  + API_BOOKS_KEY

const form_book = document.getElementById('ajout_element_livres');

var item, titre, auteur, editeur, annee, categorie, resume, poster, nbpage;
var section_book = document.getElementById('resultat_recherche');
var searchData;

function handleResponse(response) {
    for (var i = 0; i < response.items.length; i++) {
      var item = response.items[i];
      // in production code, item.text should have the HTML entities escaped.
      document.getElementById("content").innerHTML += "<br>" + item.volumeInfo.title;
    }
}

function getMovies(url){

	fetch(url).then(res => res.json()).then(data => {
		console.log(data.results);
	})
}

getMovies("https://www.googleapis.com/books/v1/volumes?q=harry+potter&callback=handleResponse");