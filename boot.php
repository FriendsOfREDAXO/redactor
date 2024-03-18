<?php
/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 */

/** @var rex_addon $this */

$addon = rex_addon::get('redactor');

/* YOrm Model-Klasse initialisieren */
if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_dataset::setModelClass(
        'rex_redactor_profil4',
        FriendsOfRedaxo\Redactor\Redactor::class
    );
}
 


if (rex::isBackend() && rex::getUser()) {

    rex_view::addCssFile(rex_url::assets('addons/project/redactor/redactor.min.css'));
    rex_view::addJsFile(rex_url::assets('addons/project/redactor/redactor.min.js'));

    rex_extension::register('REX_YFORM_SAVED', function (rex_extension_point $ep) {

        /* Alter Code aus Redactor-Addon 2.x */

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

        //        rex_view::addJsFile($addon->getAssetsUrl('vendor/redactor/langs/'.substr($userLang, 0, 2).'.js'));
        //        rex_view::addJsFile($addon->getAssetsUrl('cache/plugins.'.$userLang.'.js'));
        //        rex_view::addJsFile($addon->getAssetsUrl('cache/profiles.js'));


    });

    rex_view::setJsProperty('redactor_rex_clang_getCurrentId', rex_clang::getCurrentId());
    rex_view::setJsProperty('redactor_rex_url_media', '/media/');

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

}
