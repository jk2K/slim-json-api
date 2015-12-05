#slim-jsonAPI
This is an extension to the [SLIM framework](https://github.com/codeguy/Slim) to implement json API's with great ease.

##Installation
Using composer you can add use this as your composer.json

```json
    {
        "require": {
            "slim/slim": "2.3.*",
            "slim/json-api": "dev-master"
        }
    }

```

##Usage
To include the middleware and view you just have to load them using the default _Slim_ way.
Read more about Slim Here (https://github.com/codeguy/Slim#getting-started)

```php
    require 'vendor/autoload.php';

    $app = new \Slim\Slim();

    $app->view(new \JsonApiView());
    $app->add(new \JsonApiMiddleware());
```

###.htaccess sample
Here's an .htaccess sample for simple RESTful API's
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [QSA,L]
```

###example method
all your requests will be returning a JSON output.
the usage will be `$app->render( (int)$HTTP_CODE, (array)$DATA);`

####example Code 
```php

    $app->get('/', function() use ($app) {
        $app->render(200,array(
                'msg' => 'welcome to my API!',
            ));
    });

```


####example output
```json
{
    "data": {
        "msg": "welcome to my API!"
    }
}

```

##Errors
To display an error just set the `error => true` in your data array.
All requests will have an `error` param that defaults to false.

```php

    $app->get('/user/:id', function($id) use ($app) {

        //your code here

        $app->render(404,array(
                'msg'   => 'user not found',
            ));
    });

```
```json
{
	"errors": {
		"msg": "user not found",
	}
}
```


##middleware
The middleware will set some static routes for default requests.
**if you dont want to use it**, you can copy its content code into your bootstrap file.

***IMPORTANT: remember to use `$app->config('debug', false);` or errors will still be printed in HTML***
