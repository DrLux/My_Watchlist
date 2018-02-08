# My_Watchlist

My_watchlist allows you to choose your favorite TV series from a complete data set and follow the episode outputs on the calendar on the homepage. In addition to providing information on the release dates, the site also provides short descriptions of the storylines and supports both Italian and English.

The site is completely free of waiting for loading thanks to the use of asynchronous calls (in AJAX) and a small local database that buffers the most frequent data. This allows a comfortable and fast navigation.

# Style
All the graphic is made by me without a framework or other automatic code generator (it was for a university exam so I had to prove to know the CSS).


# Database
All the information are downloaded from [TheTVDB.com](https://www.thetvdb.com/), an open source database supported by an international community. TheTVDB has API that is accessible via https://api.thetvdb.com and after you are logged in, it provides all the REST endpoints in JSON format.
         
So my_watchlist send request during the search for new series but the most common information (such the credential, the informations to the episodes, the added tv-series) are cached in a local database.

The logic scheme of local database is:
```
Utente(id_utente, username, password, img_path)
Puntata(punt_id_serie, stagione, num_puntata, punt_lingua, data, trama,
id_img_puntata)
Serie(id_serie, serie_lingua, banner_img, ultima_mod, status, nome_serie)
Watchlist(wl_id_utente, wl_id_serie, wl_lingua, data_aggiunta)
```
![Colored versiont for foreign keys](https://drlux.github.io/db_watchlist.JPG)

# Demonstration
I left a video demonstration below:
[![Watch the video](https://drlux.github.io/my_watchlist.png)](https://www.youtube.com/watch?v=kd1NAzdPOdU&feature=youtu.be)]
