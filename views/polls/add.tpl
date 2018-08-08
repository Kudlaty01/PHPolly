<div>
    <form method="post" action="/polls/add">
        <div><label for="question">Question: </label><input name="question" id="question" value="{{ question }}"/></div>
        <div><label for="expiration_date">Expiration date:</label><input type="datetimea" name="expiration_date"
                                                                         id="expiration_date"
                                                                         value="{{ expirationDate }}"/></div>
        <button type="submit">Submit</button>
    </form>
</div>