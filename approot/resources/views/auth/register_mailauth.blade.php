@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    認証しました。ログインしてください。<br>
					<a href="/login">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
