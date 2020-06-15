# ps-training-integration-code

Start by getting the docker images: 
```
docker-compose pull
```

Start the docker: 
```
docker-compose up -d
```

Get the dependencies: 
```
docker-compose exec fpm composer install
```

Run the demo code: 
```
docker-compose exec fpm php code-correction/test.php
```
