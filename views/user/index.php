<div class="row m-3 bg-secondary">
    <div class="col-md-1"></div>
    <div class="col-md-4 m-3 p-1 bg-light rounded-lg">
        <form method="POST" action="/user/login">
            <h1 class="text-center">Sign in to TODO LIST</h1>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-4 m-3 p-1 bg-light rounded-lg">
        <form method="POST" action="/user/register">
            <h1 class="text-center">Sign up to TODO LIST</h1>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Sign up</button>
        </form>
    </div>
</div>