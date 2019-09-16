<?php

namespace App\Modules;

/** * Сервис провайдер для подключения модулей */

class ModulesServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        //получаем список модулей, которые надо подгрузить
        $modules = config("module.modules");
        if ($modules) {
            foreach ($modules as $module) {
                //Подключаем роуты для модуля
                if (file_exists(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Routes/routes.php')) {
                    $this->loadRoutesFrom(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Routes/routes.php');
                }
                //Загружаем View
                //view('Test::admin')
                if (is_dir(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Views')) {
                    $this->loadViewsFrom(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Views', $module);
                }

//Подгружаем миграции
                if (is_dir(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Migration')) {
                    $this->loadMigrationsFrom(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Migration');
                }
//Подгружаем переводы
//trans('Test::messages.welcome')
                if (is_dir(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Lang')) {
                    $this->loadTranslationsFrom(__DIR__ . 'ModulesServiceProvider.php/' . $module . '/Lang', $module);
                }
            }
        }
    }

    public function register()
    {

    }
}