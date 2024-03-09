<form action="{{ route('password.update') }}" class="mb-4" method="post">
    @csrf
    @method('put')
    <div class="row w-100">
        <h1 class="fs-5 fw-bold">Change password</h1>
        <div class="col-md-6 my-2">
            <fieldset class="w-100">
                <legend>Current Password</legend>
                <input type="password" name="current_password" class="w-100 w-md-90 setting-input">
            </fieldset>
            @error('current_password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6 my-2">
            <fieldset class="w-100">
                <legend>New Password</legend>
                <input type="password" name="password" class="w-100 w-md-90 setting-input">
            </fieldset>
            @error('password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="col-md-6 my-2">
            <fieldset class="w-100">
                <legend>Confirm Password</legend>
                <input type="password" name="password_confirmation" class="w-100 w-md-90 setting-input">
            </fieldset>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <button type="submit" class="anchor-button rounded-2 text-white" style="background-color:#105082">Change Password</button>
        </div>
    </div>
</form>
