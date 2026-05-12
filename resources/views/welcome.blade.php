<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>A Minha Biblioteca</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=merriweather:400,400i,700|playfair-display:400,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-[#1c1816] text-white font-sans selection:bg-[#b58f5c] selection:text-[#1c1816]">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10 w-full bg-[#2d2019]/90 backdrop-blur-md border-b border-[#3e2b1e] shadow-md">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-bold text-gray-300 hover:text-[#b58f5c] focus:outline focus:outline-2 focus:rounded-sm focus:outline-[#b58f5c] transition-colors">Voltar à Receção</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-gray-300 hover:text-[#b58f5c] focus:outline focus:outline-2 focus:rounded-sm focus:outline-[#b58f5c] transition-colors">Entrar</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-6 font-bold text-gray-300 hover:text-[#b58f5c] focus:outline focus:outline-2 focus:rounded-sm focus:outline-[#b58f5c] transition-colors">Registar Leitor</a>
                        @endif
                    @endauth
                </div>
            @endif
            <div class="max-w-4xl mx-auto p-6 sm:p-8 mt-16 text-center">
                <div class="mb-8">
                    <svg class="w-24 h-24 mx-auto text-[#b58f5c] drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>

                <h1 class="text-5xl font-serif font-bold text-[#cba77d] mb-6 drop-shadow-md tracking-wide">
                    Arquivo & Biblioteca
                </h1>
                
                <p class="text-xl text-gray-300 font-serif italic mb-10 max-w-2xl mx-auto leading-relaxed border-l-4 border-[#b58f5c] pl-6 text-left">
                    "Uma biblioteca não é um luxo, mas sim uma das necessidades da vida."<br>
                    <span class="text-sm font-sans font-bold uppercase tracking-widest mt-2 block text-[#b58f5c]">— Henry Ward Beecher</span>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12 text-left">
                    <div class="p-8 bg-[#2d2019] rounded-2xl border border-[#3e2b1e] shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                        <h2 class="text-2xl font-serif text-[#cba77d] mb-3">Catálogo de Obras</h2>
                        <p class="text-gray-400 leading-relaxed mb-4">Explore centenas de livros encriptados num sistema de bases de dados imutável. Navegue por autores ou por obras publicadas pelas maiores editoras de Portugal.</p>
                        <a href="{{ route('catalogo') }}" class="inline-block mt-2 px-6 py-2 border border-[#b58f5c] text-[#b58f5c] font-bold rounded-lg hover:bg-[#b58f5c] hover:text-[#1c1816] transition-colors">Aceder ao Catálogo</a>
                    </div>
                    <div class="p-8 bg-[#2d2019] rounded-2xl border border-[#3e2b1e] shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                        <h2 class="text-2xl font-serif text-[#cba77d] mb-3">Autores Míticos</h2>
                        <p class="text-gray-400 leading-relaxed">Pesquise facilmente quem escreveu os seus livros favoritos num arquivo blindado por autenticação de 2 Fatores.</p>
                    </div>
                </div>
            </div>

            <div class="mt-auto pb-6 text-sm text-gray-500 font-sans tracking-widest uppercase">
                &copy; {{ date('Y') }} — Arquivo Protegido
            </div>
        </div>
    </body>
</html>
