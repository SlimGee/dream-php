<div class="container mt-5">
    <div class="row">
        <div class="col-md-4 mx-auto">
            <div class="card">
                <div class="card-header bg-orange text-white">
                    <span class="h4">Register</span>
                </div>                         
                <div class="card-body">
                    {form_for 'user', users_create_path, 'f', ['method':'post']}
                        <div class="form-group">
                            {f.email_field 'email', ['class':'form-control', 'placeholder':'Email Address']}
                        </div>
                        <div class="form-group">
                            {f.password_field 'password', ['class':'form-control', 'placeholder':'Password']}
                        </div>
                        <div class="form-group">
                            {f.password_field 'password_confirmation', ['class':'form-control', 'placeholder':'Confirm Password']}
                        </div>
                        <div class="form-group">
                            {f.submit 'Register', ['class':'btn btn-outline-orange btn-block']}
                        </div>
                    {end_form}
                </div>
            </div>
        </div>
    </div>
</div>
