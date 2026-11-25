<!DOCTYPE html>
<html lang="en" class="bg-ink">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex, nofollow">
    <title>Sign in · KryptoExtract CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Inter:wght@400;500;600&family=Playfair+Display:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-ink px-5">
    <div class="w-full max-w-sm">
        <a href="{{ route('home') }}" class="block text-center font-display text-[40px] font-extrabold leading-none">Krypto<span class="text-gold">Extract</span></a>
        <p class="meta mt-2 text-center">Newsroom CMS</p>
        <form method="post" class="mt-10 space-y-5 border border-line bg-card p-7">
            @csrf
            <div><label for="email" class="meta">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input mt-2"></div>
            <div><label for="password" class="meta">Password</label><input id="password" name="password" type="password" required class="input mt-2"></div>
            <label class="flex items-center gap-2 text-sm text-fog"><input type="checkbox" name="remember" class="accent-[#C9A44C]"> Keep me signed in</label>
            @error('email')<p class="text-sm text-down" role="alert">{{ $message }}</p>@enderror
            <button class="btn-gold w-full">Sign in</button>
        </form>
        <p class="mt-6 text-center text-[12px] text-mute">Demo login: admin@kryptoextract.test / password. Change this before launch.</p>
    </div>
</body>
</html>
