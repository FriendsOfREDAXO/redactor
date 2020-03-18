# Redactor Editor

## Plugins

- alignment
- blockquote
- bold
- deleted
- h1
- h2
- h3
- h4
- h5
- h6
- hr
- html
- indent
- italic
- linkExternal
- linkInternal
- linkYForm[tableNameA=field|rex_yf_news=title]
- lists[ul|ol|indent|outdent]
- ol
- outdent
- pre 
- quote
- redo
- separator (|)
- style[code|mark|var|kbd|sup|sub]
- sub
- sup
- ul
- underline
- undo


## Redactor Plugins

- counter (via Profil aktivieren)
- limiter (via Profil aktivieren)
- specialchars
- table
- video
- widget


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
