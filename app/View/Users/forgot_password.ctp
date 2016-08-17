<h3>Forgot your password?</h3>

<p>Please enter your email below to request a password reset from our servers.</p>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <form action='/users/forgotPassword' method='post'>
            <label>
                Your Email Address *
            </label>
            <input class='form-control input' type='text' name='data[User][email]' placeholder='yourname@example.com' />
            <input type='submit' value='Request Password Reset' class='btn btn-success btn-lg' />
        </form>
    </div>
</div>