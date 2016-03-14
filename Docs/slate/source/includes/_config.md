---
includes:
    - config_extension
---


# Config

The config can customize caramel to your needs.

Caramel ships with a default config json file.

```json
{
  "extension":       "crml",
  "use_cache":       false,
  "cache_dir":       "../Cache",
  "comment_symbol":  "#",
  "show_comments":   true,
  "block_comments":  false,
  "variable_symbol": "@",
  "left_delimiter":  "{",
  "right_delimiter": "}",
  "file_header":     "Caramel template engine.",
  "self_closing":    [
    "br",
    ...
  ],
  "inline_elements": [
    "b",
    ...
  ]
}
```