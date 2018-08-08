<div>
    <form method="post" action="{{ action }}">
        <div data-bind="visible: invalid">Poll question must be filled and threre must be at least two non-empty answers!<br/>If date is set, it must match format Y-m-d H:M:S</div>
        <div><label for="question">Question: </label><input name="question" id="question" data-bind="textInput: question"/>
        </div>
        <div><label for="expirationDate">expirationDate: </label><input name="expirationDate" id="expirationDate"
                                                                        data-bind="textInput: expirationDate"/></div>
        <fieldset>
            <legend>Answers</legend>
            <button data-bind="click: addAnswer">Add</button>
            <div data-bind="foreach: answers">
                <div class="poll-answer">
                    <input type="hidden"
                           data-bind='value: answerId, attr: { name:"answers["+$index()+"][answer_id]" }'/>
                    <input id="text" data-bind='textInput: text, attr: { name:"answers["+$index()+"][text]" }'/>
                    <button data-bind="click: $parent.removeAnswer">Remove</button>
                    <div data-bind="visible: invalid">Every answer must have text!</div>
                </div>
            </div>
        </fieldset>

        <button data-bind="disable: invalid" type="submit">Submit</button>
    </form>
</div>
<script type="text/javascript">
    Polls.edit({{ pollData }})
</script>
