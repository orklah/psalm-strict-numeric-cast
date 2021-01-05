# psalm-strict-numeric-cast 
A [Psalm](https://github.com/vimeo/psalm) plugin to restrict the use of (int) and (float) to numeric-string only

Installation:

```console
$ composer require --dev orklah/psalm-strict-numeric-cast
$ vendor/bin/psalm-plugin enable orklah/psalm-strict-numeric-cast
```

Usage:

Run your usual Psalm command:
```console
$ vendor/bin/psalm
```

Explanation:

This plugin aims to avoid code like this:
```php
function a(string $potential_int){
    $int = (int) $potential_int;
    //...
}
```
This cast is performed on a string that could have any value from a static analysis point of view.

The issue can be resolved in a few ways that will force you to have a better confidence in your variables types.

- You can check that the variable is indeed numeric:
```php
function a(string $potential_int){
    if(is_numeric($potential_int)){
        $int = (int) $potential_int;
    }
    else{
        //throw
    }
    //...
}
```
```php
function a(string $potential_int){
    Assert::numeric($potential_int);
    $int = (int) $potential_int;
    //...
}
```
- You can make psalm understand that the function expects a numeric (this will force you to correctly type any input to this function):
```php
/** @psalm-param numeric-string $potential_int */
function a(string $potential_int){
    $int = (int) $potential_int;
    //...
}
```
