# Redactor Editor

Bindet den WYSIWYG-Editor [Redactor](http://imperavi.com/redactor/) im Backend ein.

> "Ultra-modern, ✨ AI-powered editor with powerful API capabilities, a wide range of settings, good documentation and tons of integration examples."

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/redactor/assets/redactor.png)

## Funktionen

In Arbeit

### Eingabe
Der in der Profilverwaltung erstellte Selector wird der Textarea als css-class zugeordnet. 

```html
<textarea class="form-control redactor-editor--full" name="REX_INPUT_VALUE[1]">REX_VALUE[1]</textarea>
```

### Ausgabe
```html
REX_VALUE[id="1" output="html"]
```

## Hinweise 

### Plugin linkYForm

Um die fiktiven generierten Urls wie `rex-yf-news://1` zu ersetzen, muss folgendes Skript in die `boot.php` des `project` AddOns.
Dazu müsste der Code für die Urls angepasst werden. 

```php
\rex_extension::register('OUTPUT_FILTER', function(\rex_extension_point $ep) {
    return preg_replace_callback(
        '@(rex-yf-(news|person))://(\d+)(?:-(\d+))?/?@i',
        function ($matches) {
            // table = $matches[1]
            // id = $matches[3]
            $url = '';
            switch ($matches[1]) {
                case 'rex-yf-news':
                    // Beispiel, falls die Urls via Url-AddOn generiert werden 
                    $object = News::get($matches[3]);
                    if ($object) {
                        $url = $object->getUrl();
                        
                        // die getUrl Methode könnte so aussehen
                        // public function getUrl()
                        // {
                        //     return rex_getUrl('', '', ['news-id' => $this->id]);
                        // }
                    }
                    break;
                case 'rex-yf-person':
                    // ein anderes Beispiel 
                    $url = '/index.php?person='.$matches[3];
                    break;
            }
            return $url;
        },
        $ep->getSubject()
    );
}, rex_extension::NORMAL);
```

## Lizenz


## Credits

**Projekt-Leads**  
[Alexander Walther](https://github.com/alxndr-w)
WIP