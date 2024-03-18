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


/* Tablesets aktualisieren */
if (rex_addon::get('yform')->isAvailable() && !rex::isSafeMode()) {
    rex_yform_manager_table_api::importTablesets(rex_file::get(rex_path::addon($addon->getName(), 'install/rex_redactor.tableset.json')));
    rex_yform_manager_table::deleteCache();
}


/*
// Vendor-Dateien kopieren und Übersetzungen anpassen
// JS-Variable "redactorTranslations.vendor_" wird hinzugefügt
$filesCopyAndModify = [
    'vendor/redactor/plugins/counter/counter.js' => 'plugins/redactor_counter.js',
    'vendor/redactor/plugins/fontcolor/fontcolor.js' => 'plugins/redactor_fontcolor.js',
    'vendor/redactor/plugins/fontfamily/fontfamily.js' => 'plugins/redactor_fontfamily.js',
    'vendor/redactor/plugins/fontsize/fontsize.js' => 'plugins/redactor_fontsize.js',
    'vendor/redactor/plugins/fullscreen/fullscreen.js' => 'plugins/redactor_fullscreen.js',
    'vendor/redactor/plugins/properties/properties.js' => 'plugins/redactor_properties.js',
    'vendor/redactor/plugins/specialchars/specialchars.js' => 'plugins/redactor_specialchars.js',
    'vendor/redactor/plugins/table/table.js' => 'plugins/redactor_table.js',
    'vendor/redactor/plugins/textdirection/textdirection.js' => 'plugins/redactor_textdirection.js',
    'vendor/redactor/plugins/video/video.js' => 'plugins/redactor_video.js',
    'vendor/redactor/plugins/widget/widget.js' => 'plugins/redactor_widget.js',
];

foreach (rex_i18n::getLocales() as $locale) {
    $localeShort = substr($locale, 0, 2);

    $dir = 'vendor/redactor/langs/';
    if(file_exists($addon->getPath($dir.$locale.'.js'))) {
        $filesCopy[$dir.$locale.'.js'] = 'vendor/redactor/langs/'.$localeShort.'.js';
    } elseif(file_exists($addon->getPath($dir.$localeShort.'.js'))) {
        $filesCopy[$dir.$localeShort.'.js'] = 'vendor/redactor/langs/'.$localeShort.'.js';
    }
}
foreach ($filesCopy as $source => $destination) {
    rex_file::copy($addon->getPath($source), $addon->getAssetsPath($destination));
}
foreach ($filesCopyAndModify as $source => $destination) {
    $fileContent = rex_file::get($addon->getPath($source));

    preg_match_all('/this\.lang\.get\(\'([a-zA-Z-_]*)\'\)/', $fileContent, $matches, PREG_SET_ORDER);
    $search = [];
    $replace = [];
    foreach ($matches as $match) {
        $search[] = '/'.preg_quote($match[0]).'/';
        $replace[] = 'redactorTranslations.vendor_'.str_replace('-', '_', $match[1]);
    }
    $fileContent = preg_replace($search, $replace, $fileContent);
    rex_file::put($addon->getAssetsPath($destination), $fileContent);
}
*/

$cacheFile = $addon->getCachePath('profiles.js');
if (file_exists($cacheFile)) {
    rex_file::delete($cacheFile);
}

$cacheFile = $addon->getCachePath('plugins.js');
if (!file_exists($cacheFile)) {
    rex_file::delete($cacheFile);
}
