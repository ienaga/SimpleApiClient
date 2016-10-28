# SimpleApi

simple api library.

[![Build Status](https://travis-ci.org/ienaga/SimpleApi.svg?branch=master)](https://travis-ci.org/ienaga/SimpleApi)

[![Latest Stable Version](https://poser.pugx.org/ienaga/simple-api/v/stable)](https://packagist.org/packages/ienaga/simple-api) [![Total Downloads](https://poser.pugx.org/ienaga/simple-api/downloads)](https://packagist.org/packages/ienaga/simple-api) [![Latest Unstable Version](https://poser.pugx.org/ienaga/simple-api/v/unstable)](https://packagist.org/packages/ienaga/simple-api) [![License](https://poser.pugx.org/ienaga/simple-api/license)](https://packagist.org/packages/ienaga/simple-api)


# Composer

```json
{
    "require": {
       "ienaga/simple-api": "*"
    }
}
```

# Usage


## GET

```php
$simpleApi = new \Api\SimpleApi();
$json = $simpleApi
    ->setEndPoint("https://end_point")
    ->execute();
```

## POST

```php
$simpleApi = new \Api\SimpleApi();
$json = $simpleApi
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
    ->execute();
```

## PUT

```php
$simpleApi = new \Api\SimpleApi();
$json = $simpleApi
    ->addHeader("Authorization", "token=XXXXXXX")
    ->setEndPoint("https://end_point")
    ->setMethod("PUT")
    ->add("data", [
        "status" => 2
    ])
    ->execute();
```

## DELETE

```php
$simpleApi = new \Api\SimpleApi();
$json = $simpleApi
    ->setEndPoint("https://end_point")
    ->setMethod("DELETE")
    ->execute();
```