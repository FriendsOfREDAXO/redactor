# Redactor - Changelog


## 2.3.0 – 12.11.2021

### Features

* Anpassungen für den Dark Mode (R 5.13) ([#33](https://github.com/FriendsOfREDAXO/redactor/pull/33))
* Svensk översättning ([#34](https://github.com/FriendsOfREDAXO/redactor/pull/34))

### Bugfixes

* Dokumentation angepasst ([#36](https://github.com/FriendsOfREDAXO/redactor/pull/36))


## 2.2.0 – 20.09.2021

### Features

* Vendor update 3.5.1 ([@tbaddade](https://github.com/tbaddade))
* Funktion "kopieren" in "duplizieren" umbenannt ([@alxndr-w](https://github.com/alxndr-w))
* Im PlugIn `clip` können Texte via Sprog gepflegt werden ([@tbaddade](https://github.com/tbaddade))
* Vendor PlugIns können jetzt via i18n übersetzt werden ([@tbaddade](https://github.com/tbaddade))
* englische Übersetzung ([@tbaddade](https://github.com/tbaddade))
* ExtensionPoint `REDACTOR_LANG_DIR` aufgenommen, um von außen Übersetzungen zu ermöglichen ([@tbaddade](https://github.com/tbaddade))
* Option zur Erstellung von Profilen aus Modulen hinzugefügt ([@gseilheimer](https://github.com/gseilheimer))

### Bugfixes

* [#26](https://github.com/FriendsOfREDAXO/redactor/commit/2f60e5d351ffc31518ca32a97d1179e83d3b6086) - PlugIn `clip` - setzen von `,` war nicht möglich ([@skerbis](https://github.com/skerbis))
* [#30](https://github.com/FriendsOfREDAXO/redactor/commit/dcdbb0dea8d40817715ee1881e954322eacb0073) - PlugIn `linkMedia` - Pfad wurde falsch gesetzt (Eine REDAXO-Installation in einem Unterordner könnte noch Probleme bereiten, [@tbaddade](https://github.com/tbaddade))
* [#31](https://github.com/FriendsOfREDAXO/redactor/commit/4624cd1265ad3ac36a9ff3de3ae97063b211138e) - PlugIn `html` - Hintergrund wurde ab REDAXO 5.12 falsch dargestellt ([@tbaddade](https://github.com/tbaddade))
