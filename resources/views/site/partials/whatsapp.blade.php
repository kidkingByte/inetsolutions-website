@php
    $wa = preg_replace('/[^0-9]/', '', site('whatsapp', ''));
    $waMessage = rawurlencode('Hello INET Solutions, I would like to know more about your internet services.');
@endphp
@if($wa)
<a href="https://wa.me/{{ $wa }}?text={{ $waMessage }}" target="_blank" rel="noopener"
   class="fixed bottom-5 right-5 z-50 inline-flex items-center gap-2 rounded-full bg-green-500 px-4 py-3 text-white shadow-lg hover:bg-green-600"
   aria-label="Chat with us on WhatsApp">
    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.34 5.5 0 12.05 0a11.82 11.82 0 018.413 3.488 11.824 11.824 0 013.48 8.414c-.003 6.557-5.334 11.886-11.893 11.886a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.66.99 3.237 1.477 4.9 1.477 5.45 0 9.886-4.434 9.886-9.886 0-2.64-1.025-5.12-2.89-6.99a9.82 9.82 0 00-6.996-2.89c-5.452 0-9.886 4.434-9.886 9.886 0 1.65.486 3.238 1.478 4.9l.954.506.533.966 1.14 4.16 4.26-1.118.505-.967.586-.44z"/></svg>
    <span class="hidden sm:inline text-sm font-semibold">Chat with us</span>
</a>
@endif
