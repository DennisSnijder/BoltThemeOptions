bolt-ui-options
======================
This extension makes it easy to create an option panel for your Bolt site.

![alt text](https://i.gyazo.com/5fc8373bbd06fcb20522b0fc265b4e3c.png "Default option screen")


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
