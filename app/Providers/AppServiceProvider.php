<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFour();

        // Enforce Sidebar Submenu Dots and Lines globally
        view()->composer('adminlte::page', function ($view) {
            $customCss = '
                <style>
                    /* Sidebar Submenu Dot & Line Styling */
                    .nav-treeview > .nav-item > .nav-link {
                        padding-left: 2rem !important;
                        position: relative !important;
                    }

                    .nav-treeview > .nav-item > .nav-link i {
                        display: none !important;
                    }

                    .nav-treeview > .nav-item > .nav-link::before {
                        content: "" !important;
                        position: absolute !important;
                        left: 1.2rem !important;
                        top: 50% !important;
                        transform: translateY(-50%) !important;
                        width: 6px !important;
                        height: 6px !important;
                        background-color: #adb5bd !important;
                        border-radius: 50% !important;
                        transition: background-color 0.3s !important;
                        z-index: 2 !important;
                    }

                    .nav-treeview > .nav-item > .nav-link:hover::before,
                    .nav-treeview > .nav-item > .nav-link.active::before {
                        background-color: #ffffff !important;
                        box-shadow: 0 0 5px rgba(255, 255, 255, 0.8) !important;
                    }

                    .nav-treeview {
                        position: relative !important;
                        padding-left: 0.5rem !important;
                    }

                    .nav-treeview::before {
                        content: "" !important;
                        position: absolute !important;
                        left: 1.45rem !important;
                        top: 0 !important;
                        bottom: 1rem !important;
                        width: 1px !important;
                        background-color: rgba(173, 181, 189, 0.3) !important;
                        z-index: 1 !important;
                    }
                </style>
            ';
            $view->getFactory()->startSection('css');
            echo $customCss;
            $view->getFactory()->stopSection();
        });
    }
}
