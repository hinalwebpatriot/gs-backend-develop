Для реализации ролей и уровней использовалось решение novatoolpermissions для Nova https://github.com/Silvanite/novatoolpermissions, на базе https://github.com/Silvanite/brandenburg  
В админке должна быть создана роль User     

**API**  

Важно! Для корректной работы с API в запросах должен быть установлен header 'Accept: application/json', иначе в случае ответов с middleware и ошибок будет возвращаться html

**POST: /auth/register** - регистрация пользователя с ролью User
параметры:  
name - имя  
email - валидный уникальный e-mail  
password - пароль, не менее 6 символов

Коды ответа:  
200 - все ок, пользователь добавлен  
400 - ошибка, некорректное значение параметров  

После сохранения юзера автоматически отправляется письмо с подтверждением по ссылке типа /auth/verify/USER_ID?expires=...&signature=....

**GET: /auth/verify/{id}?expires=...&signature=....** - подтверждение пользователя  
параметры:  
id - id пользователя  
expires - timestamp, время, до которого ссылка является действительной  
signature - валидный токен  
данные параметры генерируются на стороне api и приходят в ссылке подтверждения  

Коды ответа:  
200 - все ок, пользователь подтвержден  
403 - ошибка, некорректное значение параметров либо ссылка подтверждения уже не действительна  
404 - пользователь не найден, либо ошибка при подтверждении пользователя  

**GET: /auth/resend** - повторная отправка письма с подтверждением  
параметры:  
id - id пользователя  

Коды ответа:  
200 - все ок, письмо отправлено  
404 - пользователь не найден, либо ошибка при отправке, либо пользователь уже подтвержден  

**POST: /auth/login** - авторизация  
параметры:  
email - валидный e-mail  
password - пароль, не менее 6 символов

Коды ответа:  
200 - все ок, пользователь авторизован  
400 - ошибка, некорректное значение параметров  
401 - пользователь не авторизован (не найден/невалидные данные авторизации)  
403 - пользователь не подтвержден  

Параметры ответа, если все ок:  
accessToken - токен для авторизации  
tokenType - тип токена - Bearer  
expires - до какого времени действителен токен  

Для использования токена в дальнейшем неоходимо передавать его в HTTP-заголовке Authorization в виде 'tokenType accessToken'.  
Пример: 'Bearer eyJ0eXAiOiJKV...LCJhbGciOiJSU'

**POST: /auth/logout** - выйти (сброс токена)  
Необходим header Authorization  
Коды ответа: 
401 - пользователь не авторизован (невалидный токен)  
200 - все ок
