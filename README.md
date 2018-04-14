# serhatozdal/php-java-optional

Full implementation of JAVA8 Optional for PHP

Usage
=======
```php
// ofEmpty refers Optional#empty() in java
// It renamed as ofEmpty() because of empty() is reserved in PHP 
Optional::ofEmpty()->isPresent(); // false

Optional::of("value")->orElse("elseValue"); // value
 
Optional::ofEmpty()->orElseThrow(function () { throw .... }); // throws exception

Optional::ofEmpty()->filter(function ($a) { return (int) $a; }); // function is not executed

Optional::of(5)->map(function ($a) { return $a * 2; })->get(); // returns 10

Optional::ofEmpty()->orElseGet(function () { return 10; }); // returns 10
```

Resources
=======
* [Java 8 Optional Documentation](https://docs.oracle.com/javase/8/docs/api/java/util/Optional.html)
* [Java 8 Optional Usage](http://www.oracle.com/technetwork/articles/java/java8-optional-2175753.html)