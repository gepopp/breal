<div>
    @php
        use Illuminate\Support\Facades\Route;
    @endphp
    <div class="flex flex-col sm:flex-row sm:space-x-8">
        @if($sidebar)
            <x-contact-form-sidebar/>
        @endif

        <div x-data="{ is_sent: @entangle('is_sent').live }" @class(["overflow-hidden", "sm:w-2/3" => $sidebar]) wire:key="contact-form">
            {{--                        <p @click="is_sent = !is_sent">toggle</p>--}}
            <div class="w-[200%] min-h-full grid grid-cols-2 gap-x-4 pl-2 transition-all duration-500 ease-in-out"
                 :class="is_sent ? '-translate-x-1/2' : 'transalte-x-0'">


                <form wire:submit="save"
                      class="space-y-4 mt-24 sm:mt-0 grid grid-cols-1 md:grid-cols-2 gap-y-4 md:gap-x-4">
                    <flux:input wire:model="data.firstname" label="{{ __('contact.form.firstname') }}" required badge="{{ __('form.required') }}"/>
                    <flux:input wire:model="data.lastname" label="{{ __('contact.form.lastname') }}" required badge="{{ __('form.required') }}"/>
                    <flux:input wire:model="data.email" label="{{ __('form.email') }}" type="email" required badge="{{ __('form.required') }}"/>
                    <flux:input wire:model="data.phone" label="{{ __('contact.form.phone') }}" type="tel" required badge="{{ __('form.required') }}"/>

                    <div class="md:col-span-2 space-y-4">
                        @if($address)
                            <flux:input wire:model="data.address" description="{{ __('contact.form.address_description') }}" label="{{ __('contact.form.property_address') }}" required badge="{{ __('form.required') }}"/>
                        @endif
                        <flux:input wire:model="data.subject" label="{{ __('contact.form.subject') }}" required badge="{{ __('form.required') }}"/>
                        <flux:editor wire:model="data.message" label="{{ __('contact.form.message') }}" required badge="{{ __('form.required') }}"/>
                    </div>


                    <div class="col-span-full" wire:key="contact-form-upload-container">
                        <flux:label>{{ __('contact.form.upload_label') }}</flux:label>
                        <livewire:dropzone
                                wire:key="dropzone"
                                wire:model="uploads"
                                :rules="['mimes:png,jpeg,pdf','max:5000']"
                                :multiple="true"/>
                        <flux:error name="uploads"/>
                    </div>

                    <div class="md:col-span-2 space-y-4">
                        <flux:checkbox wire:model="data.terms"
                                       label="{{ __('contact.form.terms_label') }}"/>
                    </div>


                    <div x-data="{ isLoading: false }"
                         x-on:upload.window="isLoading = $event.detail"
                         wire:ignore
                    >
                        @if($address)
                            <div :class="isLoading ? 'opacity-0' : 'opacity-100'" class="transition-all duration-500 ease-in-out delay-[1s]">
                                <x-button>{{ __('contact.form.submit') }}</x-button>
                            </div>
                        @else
                            <flux:button type="button" variant="primary" x-on:click="$flux.modal('damage').show()">{{ __('contact.form.submit') }}</flux:button>
                        @endif

                    </div>

                    <flux:modal name="damage" class="md:w-96">
                        <div class="space-y-6">
                            <div>
                                <flux:heading size="lg">{{ __('contact.form.damage_heading') }}</flux:heading>
                                <flux:text class="mt-2">{{ __('contact.form.damage_description') }}</flux:text>
                            </div>
                            <flux:input label="{{ __('contact.form.address') }}" badge="{{ __('form.required') }}" wire:model="data.address"/>
                            <div class="flex items-center">
                                <flux:spacer />
                                <flux:button type="button" wire:click="submitWithoutAddress" size="xs" variant="ghost">{{ __('contact.form.no_damage') }}</flux:button>
                                <flux:button type="button" wire:click="submitWithAddress" variant="primary">{{ __('contact.form.submit') }}</flux:button>
                            </div>
                        </div>
                    </flux:modal>

                </form>

                <x-contact-form-sent/>
            </div>
        </div>
    </div>
</div>
