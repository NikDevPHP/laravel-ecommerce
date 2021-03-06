<?php

namespace Mage2\System;

use Illuminate\Support\Facades\View;
use Mage2\Framework\AdminMenu\Facades\AdminMenu;
use Mage2\Framework\Support\BaseModule;
use Mage2\Framework\Support\Facades\Permission;
use Mage2\Framework\Module\Facades\Module as ModuleFacade;

class Module extends BaseModule {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    //protected $defer = true;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->registerModule();
        $this->registerAdminMenu();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mapWebRoutes();
        $this->registerViewPath();
        $this->registerViewComposer();
        $this->registerPermissions();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    protected function mapWebRoutes() {
        require __DIR__ . '/routes/web.php';
    }

    protected function registerViewPath() {
        View::addLocation(__DIR__ . '/views');
    }

    public function registerAdminMenu() {
        $adminMenu = [ 'system' => [ 'submenu' => [ 'theme' => [
                        'label' => 'Themes',
                        'route' => 'admin.theme.index',
        ]]]];
        AdminMenu::registerMenu('mage2-system', $adminMenu);
        $adminMenu = [ 'system' => [
                'label' => 'System',
                'route' => '#',
                'submenu' => ['module' => [
                        'label' => 'Module',
                        'route' => 'admin.module.index',
                    ]
                ]
        ]];
        AdminMenu::registerMenu('mage2-system', $adminMenu);
    }

    /**
     *  Register Permission for the roles
     *
     * @return void
     */
    protected function registerPermissions() {
        $permissions = [
            ['title' => 'Theme List', 'routes' => 'admin.theme.index'],
            ['title' => 'Theme Upload', 'routes' => "admin.theme.create,admin.theme.store"],
            ['title' => 'Theme Activate', 'routes' => "admin.theme.activate"],
            ['title' => 'Theme Destroy', 'routes' => "admin.theme.destroy"],
        ];

        foreach ($permissions as $permission) {
            Permission::add($permission);
        }
    }

    protected function registerViewComposer() {
        View::composer(['layouts.admin-nav', 'layouts.admin-nav'], 'Mage2\System\ViewComposers\AdminNavComposer');

        View::composer(['layouts.app'], 'Mage2\System\ViewComposers\LayoutAppComposer');
    }

    public function registerModule() {
        ModuleFacade::put($this->getIdentifier(), $this, $type = 'system');
    }

    public function getName() {
        return 'Mage2 System';
    }

    public function getNameSpace() {
        return __NAMESPACE__;
    }

    public function getIdentifier() {
        return 'mage2-system';
    }

}
