function searchSubmit(event) {

    console.log(document.getElementById("search-query").value);
    window.location.href="https://ccm.bibliotecas.unam.mx:82/cgi-bin/koha/opac-search.pl?q="+document.getElementById("search-query").value+"&limit=&weight_search=1";

    event.preventDefault();
}

const form = document.getElementById("search-form");
form.addEventListener("submit", searchSubmit);
