<?php
/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


$addon = rex_addon::get('redactor');

if (rex::isBackend() && rex::getUser()) {

    $cacheFile = $addon->getCachePath('profiles.js');
    if (!file_exists($cacheFile)) {
        Redactor::createProfileFiles();
    }

    $cacheFile = $addon->getCachePath('plugins.js');
    if (!file_exists($cacheFile)) {
        Redactor::createPluginFile();
    }


    rex_extension::register('REDACTOR_PAGES', function(\rex_extension_point $ep) {
        $pages = $ep->getSubject();
        if (rex_plugin::get('structure', 'content')->isAvailable()) {
            $pages[] = 'content/edit';
        }
        if (rex_plugin::get('yform', 'manager')->isAvailable()) {
            $pages[] = 'yform/manager/data_edit';
        }
        return $pages;
    });

    rex_extension::register('PACKAGES_INCLUDED', function() use ($addon) {
        $pages = [];
        $pages = rex_extension::registerPoint(new rex_extension_point('REDACTOR_PAGES', $pages));

        if (in_array(rex_be_controller::getCurrentPage(), $pages)) {
            rex_view::addCssFile($addon->getAssetsUrl('vendor/redactor/redactor.css'));
            rex_view::addCssFile($addon->getAssetsUrl('redactor.css'));

            $userLang = rex::getUser()->getLanguage();
            if ('' === trim($userLang)) {
                $userLang = rex::getProperty('lang');
            }

            rex_view::addJsFile($addon->getAssetsUrl('vendor/redactor/redactor.js'));
            rex_view::addJsFile($addon->getAssetsUrl('vendor/redactor/langs/'.substr($userLang, 0, 2).'.js'));
            rex_view::addJsFile($addon->getAssetsUrl('cache/plugins.'.$userLang.'.js'));
            rex_view::addJsFile($addon->getAssetsUrl('cache/profiles.js'));
            rex_view::addJsFile($addon->getAssetsUrl('redactor.js'));

            rex_view::setJsProperty('clang_id', rex_clang::getCurrentId());
        }


    }, rex_extension::LATE);
}
