<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - Hadirin</title>
        <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                       @if ($message = Session::get('error'))
                                       <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                          <strong>Kesalahan!</strong> {{ $message }}
                                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                       </div>
                                       @endif
                                        <form method="POST" action="{{route('admin.loginAction')}}">
                                            @csrf
                                            <div class="form-floating mb-3">
                                                <input class="form-control @error('email') is-invalid @enderror" name="email" id="inputEmail" type="text" value="{{ old('email') }}" />
                                                <label for="inputEmail">Email address</label>
                                                @error('email')
                                                <div class="invalid-feedback">
                                                   {{$message}}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control @error('password') is-invalid @enderror" name="password" id="inputPassword" type="password" value="{{ old('password') }}" />
                                                <label for="inputPassword">Password</label>
                                                 @error('password')
                                                   <div class="invalid-feedback">
                                                      {{$message}}
                                                   </div>
                                                 @enderror
                                            </div>
                                             <button type="submit" class="btn btn-primary w-100">Login</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
