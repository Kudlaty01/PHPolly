<div>

    <div data-bind="visible: error">
        Error!
        <div data-bind="text: error"></div>
    </div>
    <div data-bind="text: question"></div>
    <ul data-bind="foreach: answers">
        <li><span data-bind="text: answerText"></span><button data-bind="click: $parent.vote, visible: !total()">Vote</button><span data-bind="visible: $parent.answered">Total votes: <span data-bind="text: total"></span></span> </li>
    </ul>
    <button data-bind="click: getPoll">Next poll</button>


    <span>{{ msg }}</span>
</div>

<script type="text/javascript">
    Frontend.init();
</script>