## find

Returns if a value exists in the Storage object.

```php
<?php
$found = $Storage->find("hello","world");
$found = $Storage->find(array("hello" => "world","goodbye" => "world"));
?>
```

| method | params |
|:-----|:-----:|
| find | array or string, string or null|
