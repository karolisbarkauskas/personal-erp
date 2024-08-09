/**
 * @copyright C MB "Parduodantys sprendimai" 2020
 *
 * This Software is the property of Parduodantys sprendimai, MB
 * and is protected by copyright law - it is NOT Freeware.
 *
 * Any unauthorized use of this software without a valid license key
 * is a violation of the license agreement and will be prosecuted by
 * civil and criminal law.
 *
 * Contact MB "Parduodantys sprendimai":
 * E-mail: info@onesoft.io
 * http://www.onesoft.io
 *
 */

let App = (function () {
    App.cms = function () {
        $("#cms-table").DataTable({
            sScrollY: "800px",
            searchDelay: 1000,
            processing: true,
            serverSide: true, ordering: false,
            stateSave: true,
            lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
            pageLength: 25,
            ajax: {
                url: '/admin/page/table'
            },
            initComplete: function (settings, json) {
                App.handleDeleteButton();
            },
            columnDefs: [
                {
                    targets: 0,
                    title: 'Title',
                    className: 'cell-detail',
                    render: function (data, type, full, meta) {
                        return `${full.title}`;
                    },
                },
                {
                    targets: 1,
                    title: 'Active',
                    className: 'cell-detail',
                    render: function (data, type, full, meta) {
                        return `${full.active ? "<span class=\"badge badge-success\">Active</span>" : "<span  class=\"badge badge-danger\">Inactive</span>"}`;
                    },
                },
                {
                    targets: 2,
                    title: 'Actions',
                    className: 'cell-detail',
                    render: function (data, type, full, meta) {
                        return `<a href="/admin/page/${full.id}/edit" class="badge badge-info">Edit</a> <a id="deleteBundleButton-${full.id}" href="#"
                            data-delete-button="/admin/page/${full.id}"
                            class="badge badge-danger">Remove</a>`;
                    },
                }
            ]
        });
    };
    return App;
})(App || {});
