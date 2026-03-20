<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Evos',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel is behind a firewall.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>EVOS</b>-SYS',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Evos Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'wait' for a fixed time and 'load' for until the
    | page is fully loaded.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'load',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we can change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_topnav' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the control sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'control_sidebar' => [
        'enabled' => false,
        'at_right' => true,
        'offset' => true,
        'label' => 'Control Sidebar',
        'icon' => 'fas fa-cogs',
        'theme' => 'dark',
        'content_class' => 'p-3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Navbar Search
    |--------------------------------------------------------------------------
    |
    | Here we can modify the navbar search box.
    |
    | For detailed instructions you can look the navbar search section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Customization
    |
    */

    'navbar_search' => [
        'enabled' => true,
        'form_class' => 'navbar-search-inline',
        'input_class' => 'form-control-navbar',
        'icon' => 'fas fa-search',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sidebar Search
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar search box.
    |
    | For detailed instructions you can look the sidebar search section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Customization
    |
    */

    'sidebar_search' => [
        'enabled' => true,
        'img_class' => 'sidebar-search-icon',
        'icon' => 'fas fa-search',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items
        [
            'text' => 'Dashboard',
            'url'  => 'home',
            'icon' => 'fas fa-fw fa-tachometer-alt',
        ],
        ['header' => 'SCHOOL MANAGEMENT'],
        [
            'text' => 'Schools',
            'icon' => 'fas fa-fw fa-school',
            'submenu' => [
                [
                    'text' => 'My Schools',
                    'route'  => 'schools.index',
                    'icon' => 'fas fa-list',
                ],
                [
                    'text' => 'Register School',
                    'route'  => 'schools.create',
                    'icon' => 'fas fa-plus-circle',
                ],
                [
                    'text' => 'Select School',
                    'url'  => '#',
                    'icon' => 'fas fa-check-square',
                ],
                [
                    'text' => 'Current School Info',
                    'url'  => '#',
                    'icon' => 'fas fa-info-circle',
                ],
            ],
        ],
        [
            'text' => 'Students',
            'icon' => 'fas fa-fw fa-user-graduate',
            'submenu' => [
                [
                    'text' => 'Student List',
                    'route'  => 'students.index',
                    'icon' => 'fas fa-users',
                ],
                [
                    'text' => 'Add Student',
                    'route'  => 'students.create',
                    'icon' => 'fas fa-user-plus',
                ],
                [
                    'text' => 'Import Students',
                    'route'  => 'students.import.form',
                    'active' => ['students-import*'],
                    'icon' => 'fas fa-file-import',
                ],
                [
                    'text' => 'Student Profile',
                    'route'  => 'students.profile',
                    'active' => ['students-profile*'],
                    'icon' => 'fas fa-id-card',
                ],
                [
                    'text' => 'Transfer Student',
                    'route'  => 'students.transfer.form',
                    'active' => ['students-transfer*'],
                    'icon' => 'fas fa-exchange-alt',
                ],
            ],
        ],
        [
            'text' => 'Subjects',
            'icon' => 'fas fa-fw fa-book',
            'submenu' => [
                [
                    'text' => 'Manage My Subjects',
                    'route'  => 'subjects.manage',
                    'icon' => 'fas fa-list-ul',
                ],
                [
                    'text' => 'Assign to Class',
                    'route'  => 'subjects.assign-class',
                    'icon' => 'fas fa-tasks',
                ],
            ],
        ],
        [
            'text' => 'Exams',
            'icon' => 'fas fa-fw fa-file-signature',
            'submenu' => [
                [
                    'text' => 'Exam List',
                    'route'  => 'exams.index',
                    'icon' => 'fas fa-clipboard-list',
                ],
                [
                    'text' => 'Create Exam',
                    'route'  => 'exams.create',
                    'icon' => 'fas fa-plus-square',
                ],
            ],
        ],
        [
            'text' => 'Marks',
            'icon' => 'fas fa-fw fa-poll-h',
            'submenu' => [
                [
                    'text' => 'Enter Marks',
                    'route'  => 'marks.entry',
                    'icon' => 'fas fa-keyboard',
                ],
            ],
        ],
        [
            'text' => 'Results',
            'icon' => 'fas fa-fw fa-chart-bar',
            'submenu' => [
                [
                    'text' => 'Student Results',
                    'route'  => 'results.index',
                    'active' => ['results'],
                    'icon' => 'fas fa-user-check',
                ],
                [
                    'text' => 'School Results',
                    'route'  => 'results.school',
                    'active' => ['results/school*'],
                    'icon' => 'fas fa-building',
                ],
                [
                    'text' => 'Performance Analysis',
                    'route'  => 'results.analysis',
                    'active' => ['results/analysis*'],
                    'icon' => 'fas fa-chart-line',
                ],
                [
                    'text' => 'Student Analysis',
                    'route'  => 'results.student-analysis',
                    'active' => ['results/student-analysis*'],
                    'icon' => 'fas fa-user-graduate',
                ],
            ],
        ],
        [
            'text' => 'SMS & Notifications',
            'icon' => 'fas fa-fw fa-sms',
            'submenu' => [
                [
                    'text' => 'Send SMS to Parents',
                    'url'  => '#',
                    'icon' => 'fas fa-paper-plane',
                ],
                [
                    'text' => 'Send SMS to Students',
                    'url'  => '#',
                    'icon' => 'fas fa-comment-alt',
                ],
                [
                    'text' => 'Bulk SMS',
                    'url'  => '#',
                    'icon' => 'fas fa-mail-bulk',
                ],
                [
                    'text' => 'Notifications',
                    'url'  => '#',
                    'icon' => 'fas fa-bell',
                ],
            ],
        ],
        [
            'text' => 'Settings',
            'icon' => 'fas fa-fw fa-cog',
            'submenu' => [
                [
                    'text' => 'Profile Settings',
                    'route'  => 'settings.profile',
                    'icon' => 'fas fa-user-cog',
                ],
                [
                    'text' => 'School Settings',
                    'route'  => 'settings.school',
                    'icon' => 'fas fa-school',
                ],
                [
                    'text' => 'Grading System',
                    'route'  => 'settings.grading',
                    'icon' => 'fas fa-sort-numeric-up',
                ],
                [
                    'text' => 'SMS Settings',
                    'route'  => 'settings.sms',
                    'icon' => 'fas fa-sliders-h',
                ],
            ],
        ],
        // Top Navbar Items
        [
            'type'         => 'navbar-dropdown',
            'text'         => 'User',
            'topnav_right' => true,
            'icon'         => 'fas fa-user',
            'submenu'      => [
                [
                    'text' => 'My Profile',
                    'url'  => '#',
                    'icon' => 'fas fa-id-badge',
                ],
                [
                    'text' => 'Account Settings',
                    'url'  => '#',
                    'icon' => 'fas fa-user-edit',
                ],
                [
                    'text' => 'Logout',
                    'url'  => 'logout',
                    'icon' => 'fas fa-sign-out-alt',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we can modify the iFrame mode configuration.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'tabs_before' => [],
        'tabs_after' => [],
        'tab_items' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
