<?php

/**
 * @var KodiCMS\Assets\Contracts\MetaInterface $meta
 * @var KodiCMS\Assets\Contracts\PackageManagerInterface $packages
 *
 * @see http://sleepingowladmin.ru/docs/assets
 */


//$meta
//    ->css('custom', asset('custom.css'))
//    ->js('custom', asset('custom.js'), 'admin-default');

//$packages->add('jquery')
//    ->js(null, asset('libs/jquery.js'));

Meta::addJs('jquery.form.min',    asset('customjs/jquery.form.min.js'),'admin-default');
Meta::addJs('jquery.inputmask.bundle',    asset('js/jquery.inputmask.bundle.js'),'admin-default');
Meta::addJs('custom',    asset('avadmin/custom.js'),'admin-default');
Meta::addCss('custom',    asset('avadmin/custom.css'),'admin-default');

PackageManager::add('stopRefresh')
    ->js('tree',         asset('customjs/stopPageRefresh.js'), ['admin-default'], true);
