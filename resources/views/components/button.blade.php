<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-[#b58f5c] border border-transparent rounded-full font-bold text-sm text-[#1c1816] tracking-widest hover:bg-[#d4b58e] focus:bg-[#d4b58e] active:bg-[#a37c4d] focus:outline-none focus:ring-2 focus:ring-[#b58f5c] focus:ring-offset-2 focus:ring-offset-[#1c1816] disabled:opacity-50 transition ease-in-out duration-150 shadow-md']) }}>
    {{ $slot }}
</button>
