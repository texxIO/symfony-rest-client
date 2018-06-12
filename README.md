# symfony-rest-client
Admin interface to work with symfony-rest-server

INSTALL
-------

1. Git clone

2. Run
 
`composer install`

3. Set the Api service arguments in services.yml
```
 AppBundle\Services\ApiRequestService: 
    arguments: 
        $offers_api_url: 'http://tradus.local 
 ```           
 
4. Configure the `$offers_api_url` with the API server

5. Run using `php bin/console server:run` or with a web server .
