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

namespace FriendsOfRedaxo\Redactor;

class Redactor extends \rex_yform_manager_dataset
{

    /* TO DO: Redactor-Attribute automatisch auslesen */
    public static function getAttributes(int $profile_id) :string
    {

        $profile = self::get($profile_id);

        return 'data-redactor';

    }

    public static function createProfileFiles()
    {

        $sql = rex_sql::factory();
        $profiles = self::query()->find();

        $sprogOpenTag = '';
        if (rex_addon::get('sprog')->isAvailable()) {
            $sprogOpenTag = trim(\Sprog\Wildcard::getOpenTag());
        }

        foreach ($profiles as $profile) {
            $name = $profile->getValue('name');

            $redactorProfiles[$name]['redaxo']['regex']['id'] = '(.*?)\s\[.*?\]';

            $minHeight = 300;
            if ((int)$profile->getMinHeight() > 0) {
                $minHeight = (int)$profile->getMinHeight();
            }
            $maxHeight = 300;
            if ((int)$profile->getMaxHeight() > 0) {
                $maxHeight = (int)$profile->getMaxHeight();
            }

            $redactorProfiles[$name]['minHeight'] = $minHeight.'px';
            $redactorProfiles[$name]['maxHeight'] = $maxHeight.'px';

            $redactorPlugins = [];
            if ('' !== trim($profile->getPlugins())) {
                $pattern = '/\[[^]]+\](*SKIP)(*FAIL)|,/';
                $plugins = array_map('trim', preg_split($pattern, trim($profile->getPlugins())));
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
            if ('' !== trim($profile->getSettings())) {
                $settings = explode("\n", $profile->getSettings());
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

            if ($profile->getPluginCounter() === '1') {
                $redactorPlugins[] = 'counter';
            }

            if ((int)$profile->getPluginLimiter() > 0) {
                $redactorPlugins[] = 'limiter';
                $redactorProfiles[$name]['limiter'] = (int)$profile->getPluginLimiter();
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


    
    /* Name */
    /** @api */
    public function getName() : ?string
    {
        return $this->getValue("name");
    }
    /** @api */
    public function setName(mixed $value) : self
    {
        $this->setValue("name", $value);
        return $this;
    }

    /* Beschreibung */
    /** @api */
    public function getDescription(bool $asPlaintext = false) : ?string
    {
        if($asPlaintext) {
            return strip_tags($this->getValue("description"));
        }
        return $this->getValue("description");
    }
    /** @api */
    public function setDescription(mixed $value) : self
    {
        $this->setValue("description", $value);
        return $this;
    }
            
    /* Mindesthöhe */
    /** @api */
    public function getMinHeight() : ?string
    {
        return $this->getValue("min_height");
    }
    /** @api */
    public function setMinHeight(mixed $value) : self
    {
        $this->setValue("min_height", $value);
        return $this;
    }

    /* Maximalhöhe */
    /** @api */
    public function getMaxHeight() : ?string
    {
        return $this->getValue("max_height");
    }
    /** @api */
    public function setMaxHeight(mixed $value) : self
    {
        $this->setValue("max_height", $value);
        return $this;
    }

    /* Statusleiste anzeigen */
    /** @api */
    public function getPluginCounter(bool $asBool = false) : mixed
    {
        if($asBool) {
            return (bool) $this->getValue("plugin_counter");
        }
        return $this->getValue("plugin_counter");
    }
    /** @api */
    public function setPluginCounter(int $value = 1) : self
    {
        $this->setValue("plugin_counter", $value);
        return $this;
    }
            
    /* Zeichenanzahl */
    /** @api */
    public function getPluginLimiter() : ?string
    {
        return $this->getValue("plugin_limiter");
    }
    /** @api */
    public function setPluginLimiter(mixed $value) : self
    {
        $this->setValue("plugin_limiter", $value);
        return $this;
    }

    /* Plugins */
    /** @api */
    public function getPlugins(bool $asPlaintext = false) : ?string
    {
        if($asPlaintext) {
            return strip_tags($this->getValue("plugins"));
        }
        return $this->getValue("plugins");
    }
    /** @api */
    public function setPlugins(mixed $value) : self
    {
        $this->setValue("plugins", $value);
        return $this;
    }
            
    /* Settings */
    /** @api */
    public function getSettings(bool $asPlaintext = false) : ?string
    {
        if($asPlaintext) {
            return strip_tags($this->getValue("settings"));
        }
        return $this->getValue("settings");
    }
    /** @api */
    public function setSettings(mixed $value) : self
    {
        $this->setValue("settings", $value);
        return $this;
    }



}
