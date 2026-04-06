<div>
    <section class="py-16 md:py-24">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-display font-bold text-content-primary mb-4">
                    {{ __('contact.title') }}
                </h1>
                <p class="text-content-secondary max-w-2xl mx-auto">
                    {{ __('contact.description') }}
                </p>
            </div>

            <!-- Contact Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <a href="mailto:andrewgotham@mail.ru" class="group flex items-center gap-4 p-5 bg-stage-900 border border-stage-700 rounded-xl hover:border-spotlight-500/50 transition-all">
                    <div class="w-12 h-12 rounded-full bg-spotlight-950 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-spotlight-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs uppercase tracking-wider">{{ __('contact.email_label') }}</p>
                        <p class="text-content-primary font-medium">andrewgotham@mail.ru</p>
                    </div>
                </a>

                <a href="tel:+79918739137" class="group flex items-center gap-4 p-5 bg-stage-900 border border-stage-700 rounded-xl hover:border-spotlight-500/50 transition-all">
                    <div class="w-12 h-12 rounded-full bg-spotlight-950 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-spotlight-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs uppercase tracking-wider">{{ __('contact.phone_label') }}</p>
                        <p class="text-content-primary font-medium">+7 (991) 873-9137</p>
                    </div>
                </a>

                <a href="vk.com/AndrewGotham" target="_blank" rel="noopener" class="group flex items-center gap-4 p-5 bg-stage-900 border border-stage-700 rounded-xl hover:border-spotlight-500/50 transition-all">
                    <div class="w-12 h-12 rounded-full bg-spotlight-950 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-spotlight-500" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 17.97L4.58 13.216l15.31-9.87-4.102 15.314-3.844-7.69z"/></svg>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs uppercase tracking-wider">{{ __('contact.vk_label') }}</p>
                        <p class="text-content-primary font-medium">Andrew Gotham</p>
                    </div>
                </a>

                <a href="https://t.me/AndrewGotham" target="_blank" rel="noopener" class="group flex items-center gap-4 p-5 bg-stage-900 border border-stage-700 rounded-xl hover:border-spotlight-500/50 transition-all">
                    <div class="w-12 h-12 rounded-full bg-spotlight-950 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-spotlight-500" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 17.97L4.58 13.216l15.31-9.87-4.102 15.314-3.844-7.69z"/></svg>
                    </div>
                    <div>
                        <p class="text-content-muted text-xs uppercase tracking-wider">{{ __('contact.telegram_label') }}</p>
                        <p class="text-content-primary font-medium">@AndrewGotham</p>
                    </div>
                </a>
            </div>

            <!-- Success Message -->
            @session('status')
            <div class="mb-8 p-4 bg-success/10 border border-success/20 text-success rounded-lg flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ $value }}</span>
            </div>
            @endsession

            <!-- Contact Form -->
            <form wire:submit="submit" class="bg-stage-900 border border-stage-700 rounded-xl p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-2">
                            {{ __('fields.name') }} <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="name" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500" placeholder="{{ __('contact.placeholders.name') }}">
                        @error('name') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-2">
                            {{ __('fields.email') }} <span class="text-error">*</span>
                        </label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500" placeholder="{{ __('contact.placeholders.email') }}">
                        @error('email') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-2">{{ __('fields.phone') }}</label>
                    <input type="tel" wire:model="phone" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500" placeholder="+7 (___) ___-__-__">
                    @error('phone') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-2">
                        {{ __('fields.request_type') }} <span class="text-error">*</span>
                    </label>
                    <select wire:model="request_type" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary focus:outline-none focus:ring-2 focus:ring-spotlight-500 cursor-pointer">
                        <option value="general">{{ __('contact.types.general') }}</option>
                        <option value="booking">{{ __('contact.types.booking') }}</option>
                        <option value="collaboration">{{ __('contact.types.collaboration') }}</option>
                        <option value="other">{{ __('contact.types.other') }}</option>
                    </select>
                    @error('request_type') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-2">
                        {{ __('fields.subject') }} <span class="text-error">*</span>
                    </label>
                    <input type="text" wire:model="subject" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500" placeholder="{{ __('contact.placeholders.subject') }}">
                    @error('subject') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-2">
                        {{ __('fields.message') }} <span class="text-error">*</span>
                    </label>
                    <textarea wire:model="message" rows="6" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-spotlight-500 resize-vertical" placeholder="{{ __('contact.placeholders.message') }}"></textarea>
                    <div class="text-right text-xs text-content-muted mt-1">
                        <span x-text="message?.length ?? 0">0</span>/2000
                    </div>
                    @error('message') <span class="text-error text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-2">{{ __('fields.preferred_language') }}</label>
                    <select wire:model="preferred_language" class="w-full px-4 py-3 bg-stage-800 border border-stage-700 rounded-lg text-content-primary focus:outline-none focus:ring-2 focus:ring-spotlight-500 cursor-pointer">
                        <option value="ru">Русский</option>
                        <option value="en">English</option>
                        <option value="eo">Esperanto</option>
                    </select>
                </div>

                <!-- Honeypot -->
                <input type="text" wire:model="website" class="hidden" tabindex="-1" autocomplete="off">

                <div class="pt-4">
                    <button type="submit" wire:loading.attr="disabled" class="w-full md:w-auto inline-flex items-center justify-center px-8 py-4 bg-spotlight-500 hover:bg-spotlight-400 text-stage-950 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-spotlight-500 focus:ring-offset-2 focus:ring-offset-stage-950 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove>{{ __('contact.submit') }}</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-stage-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('contact.sending') }}...
                        </span>
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-xs text-content-muted">
                {{ __('contact.legal_notice') }}
            </p>
        </div>
    </section>
</div>
