# Cloning repository
```
$ git clone https://github.com/prvnpuri/test-rtechsols.git
```

# Docker deplyment

## 1.1 Build

Run following command in your project folder

```
$ docker build -t test-rtechsols .
$ docker run --rm -v $(pwd):/app composer install
```


## 1.2 Run through cli

```
$ docker run -it --rm --name test-rtechsols -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.4-cli php artisan dyno:find bipedal
```

## 1.3 Output 

![alt text](https://raw.githubusercontent.com/prvnpuri/test-rtechsols/master/Screenshot%20from%202021-08-18%2022-01-42.png)