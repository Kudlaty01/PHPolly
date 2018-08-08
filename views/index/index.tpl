<div>

    <div class="alert alert-danger" data-bind="visible: error">
        <h4 class="alert-heading">Error!</h4>
        <div data-bind="text: error"></div>
    </div>
    <blockquote data-bind="text: question"></blockquote>
    <ul data-bind="foreach: answers">
        <li><span class="col-8" data-bind="text: answerText"></span><button class="btn btn-success btn-sm" data-bind="click: $parent.vote, visible: !total()">Vote</button><span data-bind="visible: $parent.answered">Total votes: <span data-bind="text: total"></span></span></li>
    </ul>
    <button class="btn btn-primary" data-bind="click: getPoll">Next poll</button>


    <span>{{ msg }}</span>
</div>

<script type="text/javascript">
    Frontend.init();
</script>