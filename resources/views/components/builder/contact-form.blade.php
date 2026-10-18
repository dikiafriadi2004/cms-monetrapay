{{-- Contact Form Component --}}
@php
    $heading = $content['heading'] ?? 'Contact Us';
    $text = $content['text'] ?? 'Send us a message and we\'ll get back to you soon';
    $formAction = $content['form_action'] ?? route('contact.submit');
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900">{{ $heading }}</h2>
                <p class="text-lg text-gray-600">{{ $text }}</p>
            </div>
            
            <form action="{{ $formAction }}" method="POST" class="space-y-6 bg-gray-50 p-8 rounded-2xl">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">Pesan *</label>
                    <textarea id="message" name="message" required rows="5" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"></textarea>
                </div>
                
                <button type="submit" class="w-full px-8 py-4 bg-primary text-white rounded-lg font-semibold hover:bg-primary-dark transition-all transform hover:scale-105">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
</section>
