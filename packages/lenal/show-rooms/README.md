#### API ####

**GET: /api/show-rooms/** - возвращает массив сущностей show rooms, сгруппированных по странам.
**GET: /api/show-rooms/{country_code}** - возвращает массив сущностей show rooms, сгруппированных по странам.

Поля ответа объекта country:
* country_code - символьный код страны;
* country_title - заголовок страны;
* show_rooms - массив объектов сущности show room;

Поле объекта show_room:

* title - заголовок сущности;
* description - описание сущности (многострочный текст);
* location:
    * lat - координаты широты;
    * lng - координаты долготы.
* address - текстовое описание адреса;
* image - путь к изображениею;
* youtube_link - сслыка к youtube видео;
* phone:
    * number - номер телефона;
    * description - дополнительное описание к телефону.
* schedule - текст с содержанием расписания (многострочный текст).

Коды ответа:

* 200 - все ок;
* 404 - объект country не был найден;
* 500 - проблема с сервером, нужен разработчик для решения.