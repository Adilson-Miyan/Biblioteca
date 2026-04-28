@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-[#3e2b1e] bg-[#1c1816] text-white focus:border-[#b58f5c] focus:ring-[#b58f5c] rounded-lg shadow-sm']) !!}>
