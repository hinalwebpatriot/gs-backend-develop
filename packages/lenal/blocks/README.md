### API ###

#### Параметры: ####
page - страница, для которой необходимо получить блок:  
diamonds-feed,  
engagement-rings-feed,  
wedding-rings-feed,  
diamonds-detail,  
engagement-rings-detail,  
wedding-rings-detail  

**GET: /api/blocks/certificate/{page}** - получить блок-сертификат  

Ответ содержит массив элементов data:  
text - текст 
image - изображение-иконка рядом с текстом,  
file - файл для скачивания из дропдауна    

**GET: /api/blocks/guide/{page}** - получить блок-guide  

Ответ содержит:  
text - текст
file - файл pdf
video_link - ссылка на youtube-видео 

**GET: /api/blocks/why-diamonds** - получить блок 'Why GS diamonds' для детальной diamonds

Ответ содержит:  
title - заголовок
text - текст

**GET: /api/blocks/promo/{page}** - получить блок 'mind-blowing promo'

Ответ содержит:  
title - заголовок
text - текст

**GET: /api/blocks/additional-info/{page}** - получить additional info блок 

Ответ содержит данные, разбитые на 2 массива - block и icons:  
block - блок с текстом и видео:  
    title  
    text  
    button  
    video_link  
icons - иконки:   
    title  
    link  
    icon 
Если данные по какому-то из этих блоков отстутствуют, то придет пустой массив   

**GET: /api/blocks/slider/{page}** - получить слайдер

Структура ответа - массив слайдов, слайд => массив изображений (до 2)   
пример:  
[   
    [  
        "http://localhost/storage/26/Iceland_road_winter.jpg",  
        "http://localhost/storage/31/Iceland_road_winter.jpg"  
    ],  
    [  
        "http://localhost/storage/27/Iceland_road_winter.jpg"  
    ]  
]

#### Коды ответа: ####
200 - все ок  
404 - блок для запрашиваемой страницы не существует либо неправильный код страницы  