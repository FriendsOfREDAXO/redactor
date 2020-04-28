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

$files = [
    'vendor/redactor/redactor.css' => 'vendor/redactor/redactor.css',
    'vendor/redactor/redactor.js' => 'vendor/redactor/redactor.js',
    'vendor/redactor/_plugins/counter/counter.js' => 'plugins/redactor_counter.js',
    'vendor/redactor/_plugins/fontcolor/fontcolor.js' => 'plugins/redactor_fontcolor.js',
    'vendor/redactor/_plugins/fontfamily/fontfamily.js' => 'plugins/redactor_fontfamily.js',
    'vendor/redactor/_plugins/fontsize/fontsize.js' => 'plugins/redactor_fontsize.js',
    'vendor/redactor/_plugins/fullscreen/fullscreen.js' => 'plugins/redactor_fullscreen.js',
    'vendor/redactor/_plugins/limiter/limiter.js' => 'plugins/redactor_limiter.js',
    'vendor/redactor/_plugins/properties/properties.js' => 'plugins/redactor_properties.js',
    'vendor/redactor/_plugins/specialchars/specialchars.js' => 'plugins/redactor_specialchars.js',
    'vendor/redactor/_plugins/table/table.js' => 'plugins/redactor_table.js',
    'vendor/redactor/_plugins/textdirection/textdirection.js' => 'plugins/redactor_textdirection.js',
    'vendor/redactor/_plugins/video/video.js' => 'plugins/redactor_video.js',
    'vendor/redactor/_plugins/widget/widget.js' => 'plugins/redactor_widget.js',
];

foreach (rex_i18n::getLocales() as $locale) {
    $localeShort = substr($locale, 0, 2);

    $dir = 'vendor/redactor/_langs/';
    if(file_exists($addon->getPath($dir.$locale.'.js'))) {
        $files[$dir.$locale.'.js'] = 'vendor/redactor/langs/'.$localeShort.'.js';
    } elseif(file_exists($addon->getPath($dir.$localeShort.'.js'))) {
        $files[$dir.$localeShort.'.js'] = 'vendor/redactor/langs/'.$localeShort.'.js';
    }
}
foreach ($files as $source => $destination) {
    rex_file::copy($addon->getPath($source), $addon->getAssetsPath($destination));
}


$cacheFile = $addon->getCachePath('profiles.js');
if (file_exists($cacheFile)) {
    rex_file::delete($cacheFile);
}

$cacheFile = $addon->getCachePath('plugins.js');
if (!file_exists($cacheFile)) {
    rex_file::delete($cacheFile);
}



rex_sql_table::get(rex::getTable('redactor_profile'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('name', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('description', 'text'))
    ->ensureColumn(new rex_sql_column('min_height', 'int(5)'))
    ->ensureColumn(new rex_sql_column('max_height', 'int(5)'))
    ->ensureColumn(new rex_sql_column('plugin_counter', 'bool'))
    ->ensureColumn(new rex_sql_column('plugin_limiter', 'varchar(191)'))
    ->ensureColumn(new rex_sql_column('plugins', 'text'))
    ->ensureIndex(new rex_sql_index('name', ['name'], rex_sql_index::UNIQUE))
    ->ensure();
