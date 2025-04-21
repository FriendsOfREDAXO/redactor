/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
(function ($R) {
    $R.add('plugin', 'linkInternal', {
        init: function (app) {
            this.app = app;
            this.opts = app.opts;
            this.toolbar = app.toolbar;
            this.insertion = app.insertion;
            this.selection = app.selection;
        },

        // public
        start: function () {
            let obj = {
                title: redactorTranslations.linkInternal_title,
                observe: 'link',
                api: 'plugin.linkInternal.open'
            };

            let button = this.toolbar.addButton('linkInternal', obj);
            button.setIcon('<i class="rex-icon rex-icon-open-linkmap"></i>');
        },

        open: function () {
            let that = this;
            let linkMap = openLinkMap('', '&clang=' + rex.redactor_rex_clang_getCurrentId);
            $(linkMap).on('rex:selectLink', function (event, url, label) {
                event.preventDefault();
                linkMap.close();
                label = label.replace(new RegExp(that.opts.redaxo.regex.id, 'gi'), "$1");
                let options = {
                    url: url,
                    label: label
                };
                that._insert(options);
            });
        },

        // private
        _insert: function (data) {
            if (this.selection.getText() !== '') {
                data.label = this.selection.getText();
            }
            this.insertion.insertRaw('<a href="' + data.url + '">' + data.label + '</a>');
        }
    });
})(Redactor);
