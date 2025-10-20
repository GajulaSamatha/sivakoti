<div class="row">
    {{-- Name Field --}}
    <div class="col-md-6 form-group mb-3">
        <label for="name">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Email Field --}}
    <div class="col-md-6 form-group mb-3">
        <label for="email">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    {{-- Password Field --}}
    <div class="col-md-6 form-group mb-3">
        <label for="password">Password @if(isset($user->id)) (Leave blank to keep current) @endif</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
               @if(!isset($user->id)) required @endif>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Password Confirmation Field --}}
    <div class="col-md-6 form-group mb-3">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
               @if(!isset($user->id)) required @endif>
    </div>
</div>

{{-- Roles Checkboxes --}}
<div class="form-group mb-3">
    <label>Roles</label>
    <div class="@error('roles') border border-danger p-2 @enderror">
        @foreach($roles as $id => $name)
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="role_{{ $id }}" name="roles[]" value="{{ $id }}"
                       {{-- Check if the role ID is in old input or the user's current roles --}}
                       {{ (is_array(old('roles')) && in_array($id, old('roles'))) || (isset($userRoles) && in_array($id, $userRoles)) ? 'checked' : '' }}>
                <label class="form-check-label" for="role_{{ $id }}">{{ $name }}</label>
            </div>
        @endforeach
    </div>
    @error('roles')
        <small class="text-danger d-block mt-1">{{ $message }}</small>
    @enderror
</div>

<button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
<a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary">Cancel</a>