To configure header and footer links add the following entries to `/config.php`:

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
