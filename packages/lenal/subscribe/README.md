API для фронта:

**POST: /api/subscribe/save** - сохраняет подписку  

Параметры:  
email - уникальный валидный e-mail пользователя  
type - массив с категориями подписки. Доступные значения: sale, discounts, new_collection  
gender - значение пола: man или woman  
Все параметры обязательны.  

Ответ сервера (json):  
статус 200 - все ок  
статус 400 - ошибка (скорее всего, при валидации полей), описание оишбки в ответе по ключу errors  

**GET: /api/subscribe/get-form** - возвращает данные для формы подписки:  
banner - баннер  
type -  sale, discounts, new_collection  
gender - man, woman  
Ответ сервера (json):  
статус 200 - все ок  