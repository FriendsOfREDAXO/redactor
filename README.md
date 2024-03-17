# Redactor Editor für REDAXO (WIP)

Ermöglicht die Nutzung des WYSIWYG-Editors [Redactor](http://imperavi.com/redactor/) in Version 4.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/redactor/assets/redactor.png)

## Profil

Ein Profil wird entsprechend wie folgt angelegt:

```
html,undo,|,redo,h1,h2,h3,h4,bold,italic,|,image,blockquote,lists[indent],ol,ul,linkExternal,linkInternal,hr,linkYForm[rex_yform_test=last_name|rex_yform_news=title],table,widget
```

Weitere Einstellungen können hinterlegt werden, dazu die Parameter des Vendors beachten: <https://imperavi.com/redactor/docs/settings/overview/>

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

## Installation

Zunächst die Assets der kostenpflichtigen Redactor-Version in den Project-Ordner kopieren: `assets/addons/project/redactor/*`.
