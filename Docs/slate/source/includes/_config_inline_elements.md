## inline_elements

Sets the default html inline elements.

This setting is automatically filled with the default values for html 5.    
You still can add your own value tho.

```php
<?php
$caramel->config()->extend("inline_elements", "mytag");
?>
```

| setting | type | default
|:-----|:-----:|:-----|
| inline_elements | array | array("b", ...) |