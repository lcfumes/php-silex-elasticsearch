# php-silex-elasticsearch
Silex with Elasticsearch

## Docker ##

### dependencies ####

```
sudo apt-get install curl php5-cli php5-curl
```

### Install Docker ###

```
wget -qO- https://get.docker.com/ | sh
```

### Added your user to Docker group ###

```
sudo usermod -aG docker YOUR_USER
```

###  Install Docker-Compose ###

```
curl -L https://github.com/docker/compose/releases/download/1.4.0/docker-compose-`uname -s`-`uname -m` > /usr/local/bin/docker-compose
```

or you can use pip

```
pip install -U docker-compose
```

Apply executable permissions to the binary

```
chmod +x /usr/local/bin/docker-compose
```



## To Run ##
```
/webproject/vendor/lcfumes/docker/docker-compose up
```

## To Stop ##

```
/webproject/vendor/lcfumes/docker/docker-compose stop
```


## Config ##

add in

```
sudo vim /etc/hosts

127.0.0.1 webproject.dev.io
127.0.0.1 elasticsearch.dev.io
127.0.0.1 database.dev.io
```

## web ##

```
http://webpŕoject.dev
```

## database ##

```
mysql -uroot -proot -hdatabase.dev
```

## elasticsearch ##

Plugins 
 - HEAD
 - HQ

```
http://elasticsearch.dev.io:9200/_plugin/head

http://elasticsearch.dev.io:9200/_plugin/HQ
```

Contact: Luiz Fumes <lcfumes@gmail.com>
