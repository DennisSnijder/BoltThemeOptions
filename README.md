bolt-ui-options
======================
This extension makes it easy to create an option panel for your Bolt site.

![alt text](https://i.gyazo.com/a0a8e1f591f8840e16b49cb56567a17e.png "Default option screen")


Setup
======================
Define tabs and option fields in the extension its config file.

Or add the example below to your 'theme.yml' file

```
ui-options:
    -
        name: 'Example options'
        slug: example-options
        fields:
            -
                name: 'Text option'
                slug: text-field
                value: 'Hello textfield!'
                type: text
            -
                name: 'Textarea option'
                slug: text-area
                value: "Hello textarea!"
                type: textarea
            -
                name: 'Select option'
                slug: select-option
                value: "First option"
                type: select
                options:
                    - "First option"
                    - "Second option"
                    - "Third option"
            -
                name: 'Date option'
                slug: date-field
                value: '2016-12-22'
                type: date
    -
        name: 'More options'
        slug: options-more
        fields:
            -
                name: 'Another date option'
                slug: another-date-field
                value: '2016-12-22'
                type: date
            -
                name: 'Color option'
                slug: color-field
                value: '#f00'
                type: color
```


Usage
======================
In a twig template use the following function to render the option value

```
{{ uioption('my-option-slug') }}
```

Or use the option value within your extension

```
$app['ui.options']->getOptionValue('my-option-slug');
```