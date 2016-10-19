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

## CONFIG

```php
$config = [
    "time_out"         => 10, // default 10
    "connect_time_out" => 5, // default 5
    "end_point"        => "https://end_point",
    "method"           => "POST",  // default GET
    "content_type"     => "application/json"  // default application/json
];
```


## GET

```php
$simpleApi = new \Api\SimpleApi($config);
$json = $simpleApi
    ->setEndPoint("https://end_point")
    ->execute();
```

## POST

```php
$simpleApi = new \Api\SimpleApi($config);
$json = $simpleApi
    ->addHeader("Authorization", "key=XXXXXXX")
    ->setEndPoint("https://fcm.googleapis.com/fcm/send")
    ->setMethod("POST")
    ->add("to", "INSTANCE_ID")
    ->add("priority", "high")
    ->add("content_available", true)
    ->add("notification", array(
        "title" => "title", 
        "body"  => "body", 
        "badge" => 1)
    )
    ->execute();
```
