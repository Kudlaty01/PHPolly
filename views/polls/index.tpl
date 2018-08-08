<div>
    <span>{{ msg }}</span>
    <button data-bind="click: reload">Reload</button>
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
                <a data-bind="attr: { href: editionUrl }">edit</a>
                <a href="#" data-bind="click: $parent.removeItem">delete</a>
            </td>
        </tr>

        </tbody>
    </table>
</div>
<script type="text/javascript">
    Polls.list();
</script>
