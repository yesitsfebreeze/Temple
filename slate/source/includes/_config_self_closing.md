## self_closing

Sets the self closing html tags.

This setting is automatically filled with the default values for html 5.    
You still can add your own value tho.

```php
<?php
$caramel->config()->extend("self_closing", "mytag");
?>
```

| setting | type | default
|:-----|:-----:|:-----|
| self_closing | array | array("br", ...) |