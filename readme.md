##Formr
###What is Formr?
Formr is a form generator designed to be used in conjusction with the Kohana ORM module. Formr generates a full create or edit form for any given ORM Model passed to it and allows developers to customize the output to suites their needs.
###How do I use it?
Simply call the Formr::create() or Formr::edit() methods and output the response, passing any valid ORM Model and some options if you like:
```PHP
Formr::create($model[,$options])->render();
```
To render an edit form:
```PHP
Formr::edit($model, $id[, $options])->render();
```
Formr::edit requires an additonal *id* parameter to render the objects properties.
###Options
|Key|Type|Default|Example|
|-------------|-------------|-------------|-------------|
|actions|mixed|false|array('submit','reset')|
|enctype|string|'application/x-www-form-urlencoded'|'multipart/form-data'|
|exclude|array|array()|array('id','created')|
|classes|array|array()|array('name' => 'name-class')|
|labels|array|array()|array('name' => 'Full Name', 'address' => 'Full Address')|
|placeholders|array|array()|array('name' => 'Enter your name here')|
|types|array|array()|array('email' => 'email')|
|errors|array|array()|array('name' => 'name must be entered') //standard ORM validation error array|
|help|array|array()|array('name' => 'Enter your name here')|
|disabled|array|array()|array('email','phone')|
|group_by|array|array()|array('name' => 'Enter your name here')|
|fieldsets|boolean|false|'name' => 'organisation'|
|additional|array|array()|'password_confirm'|
|legend|string|''|'Form'|
|sources|array|array()||
|order|mixed|false||
|values|array|array()||
|filters|array|array()||
|display|array|array()||
|attributes|array|array()||
|tabs|false|false||
|html|array|array()||
###Basic Example
###ComplexExample
