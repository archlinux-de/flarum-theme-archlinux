## Arch Linux Theme for Flarum

This [Flarum](https://flarum.org/) extension is specific to [forum.archlinux.de](https://forum.archlinux.de/). While the
code itself is free software, please ensure to adhere to
the [Arch Linux trademark policy](https://wiki.archlinux.org/index.php/DeveloperWiki:TrademarkPolicy).

### Installation

```sh
composer require archlinux-de/flarum-theme-archlinux
```

### Optional configuration:

To configure header and footer links add the following entries to `/config.php`:

```php
<?php return [
    // ...
    'arch' => [
        'home' => 'https://www.archlinux.de/',
        'navbar' => [
            'Start' => 'https://www.archlinux.de/',
            'Pakete' => 'https://www.archlinux.de/packages',
            'Forum' => 'https://forum.archlinux.de/',
            'Wiki' => 'https://wiki.archlinux.de/title/Hauptseite',
            'AUR' => 'https://aur.archlinux.de/',
            'Download' => 'https://www.archlinux.de/download'
        ],
        'navbar_selected' => 'Forum',
        'footer' => [
            'Datenschutz' => 'https://www.archlinux.de/privacy-policy',
            'Impressum' => 'https://www.archlinux.de/impressum'
        ]
    ]
];
```
