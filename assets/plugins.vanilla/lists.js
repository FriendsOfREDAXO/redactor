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
    $R.add('plugin', 'lists', {
        init: function (app) {
            this.opts = app.opts;
            this.toolbar = app.toolbar;

            this.listsopts = ['ul', 'ol', 'outdent', 'indent'];

            if ('redaxo' in this.opts && 'lists' in this.opts.redaxo) {
                this.listsopts = app.opts.redaxo.lists;
            }
        },

        // public
        start: function () {

            let dropdown = {
                observe: 'list'
            };

            if (this.listsopts.indexOf('ul') !== -1) {
                dropdown.unorderedlist = {
                    title: redactorTranslations.ul_title,
                    api: 'module.list.toggle',
                    args: 'ul'
                }
            }

            if (this.listsopts.indexOf('ol') !== -1) {
                dropdown.orderedlist = {
                    title: redactorTranslations.ol_title,
                    api: 'module.list.toggle',
                    args: 'ol'
                }
            }

            if (this.listsopts.indexOf('outdent') !== -1) {
                dropdown.outdent = {
                    title: redactorTranslations.outdent_title,
                    api: 'module.list.outdent'
                }
            }

            if (this.listsopts.indexOf('indent') !== -1) {
                dropdown.indent = {
                    title: redactorTranslations.indent_title,
                    api: 'module.list.indent'
                }
            }

            let obj = {
                title: redactorTranslations.lists_title,
                icon: true,
                observe: 'list'
            };

            let button = this.toolbar.addButton('lists', obj);
            button.setDropdown(dropdown);
        }
    });
})(Redactor);
