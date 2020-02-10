<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card">
                <div class="card-header">
                    <span class="h2">Login</span>
                </div>
                <div class="card-body">
                    {form_for 'user', authenticate_path, 'f', ['method':'post']}
                        <div class="form-group">
                            {f.email_field 'email', ['class':'form-control', 'placeholder':'Email Address']}
                        </div>
                        <div class="form-group">
                            {f.password_field 'password', ['class':'form-control']}
                        </div>
                        <div class="form-group">
                            {f.submit 'Login', ['class':'btn btn-outline-success btn-block']}
                        </div>
                    {end_form}
                </div>
            </div>
        </div>
    </div>
</div>
