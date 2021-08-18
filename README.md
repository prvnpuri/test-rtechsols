# Cloning repository
[TODO]

# Docker deplyment

## 1.1 Build

Run following command in your project folder

```
$ docker build -t test-rtechsols .
```


## 1.2 Run through cli

```
docker run -it --rm --name test-rtechsols -v "$PWD":/usr/src/myapp -w /usr/src/myapp php:7.4-cli php artisan dyno:find bipedal
```