function populateArticles(articles) {
    const container = document.getElementById("articles-container");
    // <div class="articles-item">
    //     className <div class="article-title">DiclassNameing the square into seven or nine congruent parts</div>
    //     <div class="article-info">DisclassName Mathematics, Vol. 345, Issue 5, 2022, p. 112800</div>
    //     <div class="article-authors">classNamenado, Gerardo L.; Roldán-Pensado, Edgardo</div>
    // </div>

    for (const article of articles) {
        const artItem = document.createElement("div");
        artItem.setAttribute('class', 'articles-item');

        let url="https://gaspacho.matmor.unam.mx/SRB/" + article['id']+"/show"
        artItem.setAttribute("onclick","window.open('"+url+"'"+",'_blank')");
        const artTitle = document.createElement("div");
        artTitle.setAttribute('class', 'article-title ');

        artTitle.innerHTML = article['title'];

        let info = "";
        const artInfo = document.createElement("div");
        artInfo.setAttribute('class', 'article-info');
        if(article['journal'] !== null) {
            info = info + article["journal"];
        }
        if(article['volume'] !== null) {
            info = info+ ", Vol. " + article["volume"];
        }
        if(article['issue'] !== null) {
            info = info+ ", Issue " + article["issue"];
        }
        if(article['pages'] !== null) {
            info = info+ ", p. " + article["pages"];
        }

        artInfo.innerHTML = info;
        //artInfo.innerHTML = "".concat(article['journal'], ", Vol. ", article["volume"], ", Issue ", article["issue"], ", ", article["year"], ", p. ", article["pages"]);
        //artInfo.innerHTML = "".concat(article['journal'], ", Vol. ", article["volume"]);

        const artAuthors = document.createElement("div");
        artAuthors.setAttribute('class', 'article-authors');
        artAuthors.innerHTML = article['author'];


        artItem.appendChild(artTitle);

        artItem.appendChild(artInfo);
        artItem.appendChild(artAuthors);

        container.appendChild(artItem);

    }

}

async function logArticles() {
    try {
        const response = await fetch("https://gaspacho.matmor.unam.mx/SRBexport/app_dev.php/export/2023/5", {
            mode: "cors",
            headers: {
                accept: 'application/json',
            },
        });

        const articles = await response.json();
        console.log(articles);
        populateArticles(articles);

    } catch (error) {
        console.error('An error occurred:', error);
        return null;
    }
}

logArticles();
