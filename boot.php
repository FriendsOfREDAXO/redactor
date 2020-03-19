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

    rex_extension::register('PACKAGES_INCLUDED', function() use ($addon) {

        $userLang = rex::getUser()->getLanguage();
        if ('' === trim($userLang)) {
            $userLang = rex::getProperty('lang');
        }

        $cacheFile = $addon->getCachePath('profiles.js');
        if (!file_exists($cacheFile)) {
            Redactor::createProfileFiles();
        }

        $cacheFile = $addon->getCachePath('plugins.'.$userLang.'.js');
        if (!file_exists($cacheFile)) {
            Redactor::createPluginFile();
        }

        rex_view::addCssFile($addon->getAssetsUrl('vendor/redactor/redactor.css'));
        rex_view::addCssFile($addon->getAssetsUrl('redactor.css'));

        rex_view::addJsFile($addon->getAssetsUrl('vendor/redactor/redactor.js'));
        rex_view::addJsFile($addon->getAssetsUrl('vendor/redactor/langs/'.substr($userLang, 0, 2).'.js'));
        rex_view::addJsFile($addon->getAssetsUrl('cache/plugins.'.$userLang.'.js'));
        rex_view::addJsFile($addon->getAssetsUrl('cache/profiles.js'));
        rex_view::addJsFile($addon->getAssetsUrl('redactor.js'));

        rex_view::setJsProperty('redactor_rex_clang_getCurrentId', rex_clang::getCurrentId());
        rex_view::setJsProperty('redactor_rex_url_media', rex_url::media());
        if (rex_addon::get('mediapool')->isAvailable()) {
            rex_view::setJsProperty('redactor_rex_media_getImageTypes', rex_media::getImageTypes());
        }

        $imageUrlPath = rex_url::media();
        if (rex_addon::get('media_manager')->isAvailable()) {
            $imageUrlPath = 'index.php?rex_media_type=redactorImage&rex_media_file=';

            if (rex_addon::get('yrewrite')->isAvailable()) {
                $imageUrlPath = '/media/redactorImage/';
            }
        }
        rex_view::setJsProperty('redactor_imageUrlPath', $imageUrlPath);

    }, rex_extension::LATE);
}
