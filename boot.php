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
