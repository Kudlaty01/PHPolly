<div>
    <div class="alert alert-danger">{{ error }}</div>
    <form method="post" action="/login">
        <div class="form-group row"><label class="col-md-2 col-form-label" for="login">Login: </label><input class="col-md-3 form-control" name="login" id="login" value="{{ login }}"/></div>
        <div class="form-group row"><label class="col-md-2 col-form-label" for="password">Password:</label><input class="col-md-3 form-control" type="password" name="password" id="password"
                                                           value="{{ password }}"/></div>
        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
</div>