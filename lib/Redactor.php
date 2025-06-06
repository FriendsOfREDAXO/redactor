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

class Redactor
{

    public static function insertProfile($name, $description, $min_height = 0, $max_height = 0, $plugin_counter = 1, $plugin_limiter = '', $plugins = '', $settings = '')
    {
        $sql = rex_sql::factory();
        $sql->setTable(rex::getTablePrefix().'redactor_profile');
        $sql->setValue('name', $name);
        $sql->setValue('description', $description);
        $sql->setValue('min_height', $min_height);
        $sql->setValue('max_height', $max_height);
        $sql->setValue('plugin_counter', $plugin_counter);
        $sql->setValue('plugin_limiter', $plugin_limiter);
        $sql->setValue('plugins', $plugins);
        $sql->setValue('settings', $settings);

        try {
            $sql->insert();
            return $sql->getLastId();
        } catch (rex_sql_exception $e) {
            return $e->getMessage();
        }
    }

    public static function profileExists($name)
    {
        $sql = rex_sql::factory();
        $profile = $sql->setQuery("SELECT `name` FROM `".rex::getTablePrefix()."redactor_profile` WHERE `name` = ".$sql->escape($name)."")->getArray();
        unset($sql);

        if (!empty($profile)) {
            return true;
        } else {
            return false;
        }
    }

    public static function createProfileFiles()
    {
        $sql = rex_sql::factory();
        $profiles = $sql->getArray('SELECT * FROM `'.rex::getTable('redactor_profile').'`');

        $sprogOpenTag = '';
        if (rex_addon::get('sprog')->isAvailable()) {
            $sprogOpenTag = trim(\Sprog\Wildcard::getOpenTag());
        }

        $redactorProfiles = [];
        foreach ($profiles as $profile) {
            $name = $profile['name'];

            $redactorProfiles[$name]['redaxo']['regex']['id'] = '(.*?)\s\[.*?\]';

            $minHeight = 300;
            if ((int)$profile['min_height'] > 0) {
                $minHeight = (int)$profile['min_height'];
            }
            $maxHeight = 300;
            if ((int)$profile['max_height'] > 0) {
                $maxHeight = (int)$profile['max_height'];
            }

            $redactorProfiles[$name]['minHeight'] = $minHeight.'px';
            $redactorProfiles[$name]['maxHeight'] = $maxHeight.'px';

            $redactorPlugins = [];
            if ('' !== trim($profile['plugins'])) {
                $pattern = '/\[[^]]+\](*SKIP)(*FAIL)|,/';
                $plugins = array_map('trim', preg_split($pattern, trim($profile['plugins'])));
                foreach ($plugins as $plugin) {
                    $plugin = trim($plugin);
                    if (preg_match('/(.*)\[(.*)\]/', $plugin, $matches)) {
                        $parameters = explode('|', $matches[2]);
                        $parameterString = [];
                        foreach ($parameters as $parameter) {
                            if (strpos($parameter, '=') !== false) {
                                list($key, $value) = explode('=', $parameter, 2);
                                if ('clip' === $matches[1] && '' !== $sprogOpenTag && $sprogOpenTag === substr($value, 0, strlen($sprogOpenTag))) {
                                    $value = sprogdown($value);
                                }

                                $parameterString[] = [$key, $value];
                            } else {
                                $parameterString[] = $parameter;
                            }
                        }
                        $redactorProfiles[$name]['redaxo'][$matches[1]] = $parameterString;
                        $redactorPlugins[] = $matches[1];
                    } else {
                        $redactorPlugins[] = $plugin;
                    }
                }
            }
            // insert default settings from package.yml, overwriting user-supplied settings
            $default_settings = rex_addon::get('redactor')->getProperty('editor')['options'];
            foreach ($default_settings as $settingKey => $settingVal) {
                $redactorProfiles[$name][$settingKey] = $settingVal;
            }

            // Parse user-supplied settings. This is a bit hacky but "works for now"
            if ('' !== trim($profile['settings'])) {
                $settings = explode("\n", $profile['settings']);
                foreach ($settings as $setting) {
                    $matches = null;

                    // ignore malformed settings
                    if (preg_match('/(.*):\W?(.*)/', $setting, $matches)) {
                        $settingKey = trim($matches[1]);
                        $settingVal = trim($matches[2]);

                        // determine the dtype of the setting
                        if ($settingVal === 'true') {    // bool
                            $settingVal = true;
                        } elseif ($settingVal === 'false') { // bool
                            $settingVal = false;
                        } elseif (ctype_digit($settingVal)) {   // int
                            $settingVal = intval($settingVal);
                        } elseif (is_numeric($settingVal)) {    // float
                            $settingVal = floatval($settingVal);
                        } elseif (preg_match('/\[(.*)\]/', $settingVal, $matches)) {    // array
                            $settingVal = explode(',', $matches[1]);
                            foreach ($settingVal as $i => $val) {
                                $val = trim($val);

                                // drop surrounding braces for strings
                                if (preg_match('/["\'](.*)["\']/', $val, $matches)) {
                                    $val = $matches[1];
                                }
                                $settingVal[$i] = $val;
                            }
                        } else {
                            // just assume string and leave the val as it is

                            // drop surrounding braces for strings
                            if (preg_match('/["\'](.*)["\']/', $settingVal, $matches)) {
                                $settingVal = $matches[1];
                            }
                        }

                        $redactorProfiles[$name][$settingKey] = $settingVal;
                    }
                }
            }

            if ($profile['plugin_counter']) {
                $redactorPlugins[] = 'counter';
            }

            if ((int)$profile['plugin_limiter'] > 0) {
                $redactorPlugins[] = 'limiter';
                $redactorProfiles[$name]['limiter'] = (int)$profile['plugin_limiter'];
            }

            $redactorProfiles[$name]['buttons'] = [];
            $redactorProfiles[$name]['plugins'] = $redactorPlugins;
        }

        $content = 'redactor_profiles = '.json_encode($redactorProfiles, JSON_PRETTY_PRINT).';';
        $cacheFile = rex_path::addonCache('redactor', 'profiles.js');
        if (false === rex_file::put($cacheFile, $content)) {
            echo rex_view::error(rex_i18n::msg('error_save_profiles'));
        }

        rex_file::copy($cacheFile, rex_addon::get('redactor')->getAssetsPath('cache/profiles.js'));
    }


    public static function createPluginFile()
    {
        $dirs = rex_extension::registerPoint(
            new rex_extension_point(
                'REDACTOR_PLUGIN_DIR',
                [rex_addon::get('redactor')->getAssetsPath('plugins')]
            )
        );

        $plugins = [];
        foreach ($dirs as $dir) {
            if (!file_exists($dir)) {
                continue;
            }
            foreach (rex_finder::factory($dir)->filesOnly()->sort() as $file) {
                $plugins[] = rex_file::get($file);
            }
        }

        $langKeys = self::loadPluginLanguageKeys();
        foreach (rex_i18n::getLocales() as $locale) {
            $translations = [];
            foreach ($langKeys as $langKey => $de) {
                $translations[$langKey] = rex_i18n::msgInLocale('redactor_plugin_'.$langKey, $locale);
            }

            $content = 'let redactorLang = "'.substr($locale, 0, 2).'";'."\n\n";
            $content .= 'let redactorTranslations = '.json_encode($translations, JSON_PRETTY_PRINT).';'."\n\n";
            $content .= implode("\n", $plugins);

            $cacheFile = rex_path::addonCache('redactor', 'plugins.'.$locale.'.js');
            if (false === rex_file::put($cacheFile, $content)) {
                echo rex_view::error(rex_i18n::msg('redactor_error_save_profiles'));
            }

            rex_file::copy($cacheFile, rex_addon::get('redactor')->getAssetsPath('cache/plugins.'.$locale.'.js'));
        }
    }


    private static function loadPluginLanguageKeys()
    {
        $dirs = rex_extension::registerPoint(
            new rex_extension_point(
                'REDACTOR_LANG_DIR',
                [rex_addon::get('redactor')->getPath('lang')]
            )
        );

        $msg = [];
        foreach ($dirs as $dir) {
            $file =  $dir.DIRECTORY_SEPARATOR.'de_de.lang';
            if (($content = rex_file::get($file)) && preg_match_all('/^redactor_plugin_([^=\s]+)\h*=\h*(\S.*)(?<=\S)/m', $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $msg[$match[1]] = $match[2];
                }
            }
        }
        return $msg;
    }
}
