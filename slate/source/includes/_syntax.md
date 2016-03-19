# Syntax

> caramel markup

```php
html
  head
   title > test
  body
   div > hello world
```

Caramel works with an indent style syntax.
Each new line represents a new tag or function.

The indent ***must*** be consistent throughout one template file.

> parsed output

```text
<html>
  <head>
    <title>test</title>
  </head>
  <body>
    <div>hello world</div>
  </body>
</html>
```

This technique lets you save time while writing templates and puts the focus towards the result instead of the code.

Since all templating functions are handled with [plugins](#plugins), all specific syntax definitions are documented there.