#### API ####

**GET: /api/search?q={search_query}** - возвращает информацию о количестве найденных вариантов в каждой из доступных моделей;
**GET: /api/search/preview?q={search_query}** - возвращает краткую информацию о найденных сущностях;
**GET: /api/search/detail/{model}?q={search_query}** - возвращает детальную информацию по найденным сущностям выбранной модели.

Поля ответа:
* model - название модели, по которой происходит поиск. Доступны: blogs, diamonds, weddings и engagements;
* q - поисковый запрос.

Коды ответа:

* 200 - все ок;
* 404 - модель не найдена;
* 500 - проблема с сервером, нужен разработчик для решения.