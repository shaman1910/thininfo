<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Атрибут pattern</title>
 </head>
  <body>
   <form>
    <p>Введите телефон в формате 2-xxx-xxx, где вместо x 
    должна быть цифра:</p>
    <p><input type="tel" pattern="2-[0-9]{3}-[0-9]{3}"></p>
    <p><input type="submit" value="Отправить"></p>
   </form>
  </body>
</html>