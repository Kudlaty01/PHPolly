<div>
    <form method="post" action="/login">
        <div><label for="login">Login: </label><input name="login" id="login" value="{{ login }}"/></div>
        <div><label for="password">Password:</label><input type="password" name="password" id="password"
                                                           value="{{ password }}"/></div>
        <button type="submit">Submit</button>
    </form>
</div>