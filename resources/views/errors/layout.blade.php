<!DOCTYPE html>
<html lang="hy">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>@yield('code') · Քրեագետ</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Armenian:wght@400;600&family=Noto+Serif+Armenian:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/site.css') }}?v=10">
<style>
.err{min-height:100vh;display:grid;place-items:center;text-align:center;padding:40px 16px;background:linear-gradient(160deg,var(--g900),var(--g700));color:#c9d6cd}
.err img{width:120px;height:120px;background:#fff;border-radius:50%;padding:6px;margin:0 auto 24px}
.err .code{font-family:var(--serif);font-size:clamp(3.5rem,12vw,6rem);color:var(--gold2);line-height:1}
.err h1{color:#fff;font-size:clamp(1.3rem,4vw,1.8rem);margin:.6em 0 .3em}
.err p{margin:.2em 0;max-width:520px}
.err .alt{font-size:.9rem;color:#9fb3a6}
.err .btn{margin-top:28px}
</style>
</head>
<body>
<main class="err"><div>
  <img src="{{ asset('images/logo.png') }}" alt="Քրեագետ">
  <div class="code">@yield('code')</div>
  <h1>@yield('hy')</h1>
  <p class="alt">@yield('ru')</p>
  <p class="alt">@yield('en')</p>
  <a href="{{ url('/') }}" class="btn btn-gold">Գլխավոր էջ · Главная · Home</a>
</div></main>
</body>
</html>
