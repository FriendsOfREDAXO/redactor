# Redactor Editor

Bindet den WYSIWYG-Editor [Redactor](http://imperavi.com/redactor/) in aktueller Version im Backend ein.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/redactor/assets/redactor.png)

## Plugins 

- alignment
- blockquote
- bold
- cleaner
- clip
- deleted
- format; format[p|h1]; format[Sher=p.sher|Lock=p.lock]
- h1
- h2
- h3
- h4
- h5
- h6
- hr
- html
- image
- indent
- italic
- linkEmail
- linkExternal
- linkInternal
- linkMedia
- linkTelephone
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
- fontcolor
- fontfamily
- fontsize
- fullscreen
- limiter (via Profil aktivieren)
- properties
- specialchars
- table
- textdirection
- video
- widget


## Profil 

Ein Profil wird entsprechend wie folgt angelegt: 

```
html,undo,|,redo,h1,h2,h3,h4,bold,italic,|,image,blockquote,lists[indent],ol,ul,linkExternal,linkInternal,hr,linkYForm[rex_yform_test=last_name|rex_yform_news=title],table,widget
```

## Modulbeispiel

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
