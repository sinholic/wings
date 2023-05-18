@include('layouts.head')

<body class="form-membership">

    <!-- begin::preloader-->
    <div class="preloader">
        <div class="preloader-icon"></div>
    </div>
    <!-- end::preloader -->

    <div class="form-wrapper">

        <!-- logo -->
        <div id="logo">
            <img class="logo" src="assets/media/image/logo.png" alt="image" style="width: 250px;">
        </div>
        <!-- ./ logo -->

        <h5>Sign in</h5>

        <!-- form -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" placeholder="Username" required autocomplete="username" autofocus>
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="form-group d-flex justify-content-between">
                <div class="custom-control custom-checkbox">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
                </div>
                <a href="recover-password.html">Reset password</a>
            </div>
            <button class="btn btn-primary btn-block">Sign in</button>
        </form>
        <!-- ./ form -->

    </div>

    <!-- Plugin scripts -->
    <script src="{{ asset('vendors/bundle.js') }}"></script>

    <!-- App scripts -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
