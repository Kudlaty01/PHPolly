<div>
    <h1>Polls</h1>
    <span>{{ msg }}</span>
    <button data-bind="click: reload">Reload</button>
    <table>
        <thead>
        <tr>
            <td>ID</td>
            <td>question</td>
            <td>Expiration date</td>
            <td>Action</td>
        </tr>
        </thead>
        <tbody data-bind="foreach: polls">
        <tr>
            <td data-bind="text: pollId"></td>
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
