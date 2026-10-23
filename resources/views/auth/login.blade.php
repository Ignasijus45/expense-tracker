@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">

        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 mb-3">Log in</h1>

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" value="{{ old('email') }}" class="form-control">
                        @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input name="password" type="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Log in</button>
                </form>

                <p class="text-center mt-3 mb-0">No account? <a href="/register">Register</a></p>
            </div>
        </div>

    </div>
</div>

@endsection