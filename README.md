bolt-ui-options
======================
This extension makes it easy to create an option panel for your Bolt site.

Setup
======================
Define tabs and option fields in the extension its config file.

```
options:
    -
        name: 'Example options'
        slug: example-options
        fields:
            -
                name: 'First option!'
                slug: option-first
                value: 'Hello world!'
                type: text
```


Usage
======================
in a twig template use the following function to render the option value

```
{{ uioption('my-option-slug') }}
```
