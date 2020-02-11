/**
 * This file is part of the redactor package.
 *
 * @author (c) Friends Of REDAXO
 * @author <friendsof@redaxo.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * = = = = = = = = = = = = = = = = = = = = =
 * Don't use this
 * = = = = = = = = = = = = = = = = = = = = =
 */
(function ($R) {
    $R.add('plugin', 'linkYForm', {
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
                api: 'plugin.linkYForm.open'
            };

            let button = this.toolbar.addButton('linkYForm', obj);
            button.setIcon('<i class="rex-icon rex-icon-open-linkmap"></i>');
        },

        open: function () {
            let that = this;
            let eventFired = false;
            let pool = newPoolWindow('index.php?page=yform/manager/data_edit&table_name=rex_yform_test&rex_yform_manager_opener[id]=1&rex_yform_manager_opener[field]=last_name&rex_yform_manager_opener[multiple]=0');
            $(pool).on('rex:YForm_selectData', function (event, id, label) {
                event.preventDefault();
                pool.close();

                if (!eventFired) {
                    let options = {
                        url: 'rex-yform-test://' + id,
                        label: label
                    };
                    that._insert(options);
                    eventFired = true;
                }
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
