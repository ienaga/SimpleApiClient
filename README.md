# SimpleApiClient

simple api library.

[![UnitTest](https://github.com/ienaga/SimpleApiClient/actions/workflows/integration.yml/badge.svg)](https://github.com/ienaga/SimpleApiClient/actions/workflows/integration.yml)

[![Latest Stable Version](https://poser.pugx.org/ienaga/simple-api-client/v/stable)](https://packagist.org/packages/ienaga/simple-api-client) [![Total Downloads](https://poser.pugx.org/ienaga/simple-api-client/downloads)](https://packagist.org/packages/ienaga/simple-api-client) [![Latest Unstable Version](https://poser.pugx.org/ienaga/simple-api-client/v/unstable)](https://packagist.org/packages/ienaga/simple-api-client) [![License](https://poser.pugx.org/ienaga/simple-api-client/license)](https://packagist.org/packages/ienaga/simple-api-client)


# Composer

```json
{
    "require": {
       "ienaga/simple-api-client": "*"
    }
}
```

# Usage


## GET (AWS ElasticSearch)

```php
$client = new \SimpleApi\Client();
$json = $client
    ->setEndPoint("https://search-****.ap-northeast-1.es.amazonaws.com")
    ->setPath("/index_name/type_name/_search?q=user:kimchy")
    ->send();
```

## POST (Google FireBase)

```php
$client = new \SimpleApi\Client();
$json = $client
    ->addHeader("Authorization", "key=XXXXXXX")
    ->setEndPoint("https://fcm.googleapis.com/fcm/send")
    ->setMethod("POST")
    ->add("to", "INSTANCE_ID")
    ->add("priority", "high")
    ->add("content_available", true)
    ->add("notification", [
        "title" => "title", 
        "body"  => "body", 
        "badge" => 1
    ])
    ->send();
```

## PUT (AWS ElasticSearch)

```php
$client = new \SimpleApi\Client();
$json = $client
    ->setEndPoint("https://search-****.ap-northeast-1.es.amazonaws.com")
    ->setPath("/index_name/type_name")
    ->setMethod("PUT")
    ->add("status", 2)
    ->send();
```

## DELETE (AWS ElasticSearch)

```php
$client = new \SimpleApi\Client();
$json = $client
    ->setEndPoint("https://search-****.ap-northeast-1.es.amazonaws.com")
    ->setPath("/index_name")
    ->setMethod("DELETE")
    ->send();
```
