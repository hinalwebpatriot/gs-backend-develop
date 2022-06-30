Добавление статей осуществляется в админке в разделе Blog/Articles  
Перед добавлением статьи необходимо добавить категорию в Blog/Categories, если ее не существует  
Можно сгенерировать категории и статьи сидами:   
php artisan db:seed --class=BlogCategorySeeder  
php artisan db:seed --class=ArticlesSeeder  

**API**  

**/api/blog/list** - листинг статей  
параметры:  
page - запрашиваемый номер страницы (если не передан. то 1)  
perPage - количество статей на странице (если не передан - 15)  
categoryId - id категории (если не передан - все статьи)  
Ответ содержит:  
items - статьи: id, categoryId, dateCreated (Unix timestamp), title, previewText, previewImage  
pagination - пагинация: currentPage, lastPage(количество страниц), totalArticles (количество статей)  
categories - список категорий: id, name  
Коды ответа:  
200 - все ок  
404 - ошибка, запрашиваемая страница вне доступного диапазона  

**/api/blog/detail** - детальная статьи  
параметры:  
id - id статьи, обязательный  
Поля ответа:  
dateCreated (Unix timestamp)  
category: id, name  
title  
previewText  
previewImage  
content  
tags (array)  
Коды ответа:  
200 - все ок  
404 - ошибка, запрашиваемая статья не найдена  
400 - ошибка, некорректный или отсутствующий id  
