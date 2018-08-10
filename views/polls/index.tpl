<div>
    <span>{{ msg }}</span>
    <button class="btn btn-primary" data-bind="click: reload">Reload</button>
    <div>
        <label>
            Page size:
            <select class="form-control" name="page-size"
                    data-bind="value: pageSize, options: pageSizeOptions"></select>
        </label>Page nr: <span data-bind="foreach: pages"><button class="btn btn-secondary btn-sm" data-bind="text: $data+1, click: $parent.changePage"></button></span>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID
            </td>
            <th scope="col">question
            </td>
            <th scope="col">Expiration date
            </td>
            <th scope="col">Action
            </td>
        </tr>
        </thead>
        <tbody data-bind="foreach: polls">
        <tr>
            <th scope="row" data-bind="text: pollId">
            </td>
            <td data-bind="text: question"></td>
            <td data-bind="text: expirationDate"></td>
            <td>
                <a class="btn btn-primary" data-bind="attr: { href: editionUrl }">edit</a>
                <button class="btn btn-danger" data-bind="click: $parent.removeItem">delete</button>
            </td>
        </tr>

        </tbody>
    </table>
    <div>
        <label>
            Page size:
            <select class="form-control" name="page-size"
                    data-bind="value: pageSize, options: pageSizeOptions"></select>
        </label>Page nr: <span data-bind="foreach: pages"><button class="btn btn-secondary btn-sm" data-bind="text: $data+1, click: $parent.changePage"></button></span>
    </div>
</div>
<script type="text/javascript">
    Polls.list();
</script>
