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

            if ('redaxo' in this.opts && 'linkYForm' in this.opts.redaxo) {
                this.yform = app.opts.redaxo.linkYForm;
            } else {
                return false;
            }
        },

        // public
        start: function () {
            let dropdown = {};

            $.each(this.yform, function(i, data) {
                dropdown[i] = {
                    title: data[0],
                    api: 'plugin.linkYForm.open',
                    args: {
                        table: data[0],
                        label: data[1]
                    }
                };
            });

            let obj = {
                title: redactorTranslations.linkYForm_title,
                api: 'plugin.linkYForm.open'
            };

            let button = this.toolbar.addButton('linkYForm', obj);
            button.setDropdown(dropdown);
        },

        open: function (data) {
            let that = this;
            let eventFired = false;
            let pool = newPoolWindow('index.php?page=yform/manager/data_edit&table_name=' + data.table + '&rex_yform_manager_opener[id]=1&rex_yform_manager_opener[field]=' + data.label + '&rex_yform_manager_opener[multiple]=0');
            $(pool).on('rex:YForm_selectData', function (event, id, label) {
                event.preventDefault();
                pool.close();

                if (!eventFired) {
                    label = label.replace(new RegExp(that.opts.redaxo.regex.id, 'gi'), "$1");
                    let options = {
                        url: data.table.split('_').join('-') + '://' + id,
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
