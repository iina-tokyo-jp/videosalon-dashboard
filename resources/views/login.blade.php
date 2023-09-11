<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>VIDEOSALON LOGIN</title>
</head>
<body>
<div class="container">
    <div class="col-xl-12 d-flex justify-content-center">
        <form autocomplete="off" class="login-form mb-3 mt-0"
              action="{{ route('doLogin') }}" method="POST">
            @csrf

            <input type="password" style="width: 0px; height: 0px; border: none"/>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-transparent"
                             style="border-right: 0px">
                            <img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAEKADAAQAAAABAAAAEAAAAAAXnVPIAAABEElEQVQ4EaVRwVHDQBCTPP7jElKCS6CD0EGgAkIFQAVACXz5MDz4p5RQQhrIRrrzxcbYDB527NldrSSf94B/Bt/eP9sj4lY+q4Ve+wp8qSNiR+JioTjRA8crmRRxPP7dpHDZVL2I6wDu+n66yhyuy3RggJbA5jcTz8yRuJ0yMDZrMiW2YHgC945kQvAGgYNf1+MvZypQl2KQX1VvdLX3Fbk1rm0/AWxUppmxEt8MIvChK02htMpCt0ksIz2Z83OJEn/pSndie0ldWJjFBmR6bU7idozzDnTcB+rYHT6bzDG3EM4Gg/8ss5nMxvspw0r/dMhNf9QynMveT5pJW5O81Ga2iMjgnGqMk3tpn8fw4v4EUpdNh/DVZ7QAAAAASUVORK5CYII="
                                alt=""/>
                        </div>
                    </div>
                    <input type="text" name="email" placeholder="管理者ID" value="{{ old('email') }}"
                           class="form-control" style="border-left: 0px"/>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group input-group-password">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-transparent"
                             style="border-right: 0px">
                            <img
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAEKADAAQAAAABAAAAEAAAAAAXnVPIAAABEklEQVQ4EY1Ty1HDMBDdDSbnUAFOB3TAmYFDdGGgi3SAOwglcAwcImZI+ggduIRcA4yXfTAry3KUWDPyvt339uk3ZjowvPeTPZ0vSOgCNDPtxvQ9d87tUjmnBTR/SfHOPJrfu9st+De/uRJpnsf8M0tNRqnBnooqbgYPI9TAadoZPQMSLm3lWHmoFvMBv/qPKiQDQH8HA5piSc+goeIlFpzC4RWWq3Wtz3V5qgG8ENUP7m4KHHYwtBlNumqJiBEM/tP2KyKfDZ1NMYFbpouyBrqj6tHd1JjA3bY2yxoIcWmyGFvNYmEgjXrOxdKv9bwyUe4p5S3PGkDAIrM/obrlxlED/Q2vc41WD3dw7KZNbDHW/gI1Hk68+Ste4wAAAABJRU5ErkJggg=="
                                alt=""/>
                        </div>
                    </div>
                    <input placeholder="パスワード" name="password" type="password"
                           class="form-control" style="border-left: 0px"/>
                </div>
            </div>
            @if ($errors->any())
                <span class="form-text text-danger">{{ $errors->first() }}</span>
            @endif
            <button type="submit"
                    class="btn btn-submit btn-primary w-100 text-center text-white font-roboto">
                ログイン
            </button>
        </form>
    </div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>

