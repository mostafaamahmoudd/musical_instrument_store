@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-md border-gray-300 bg-white text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-slate-400 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500']) }}>
