<div class="overflow-hidden">
    <div x-data="{
            is_sent: @entangle('is_sent').live
        }">
        <div class="w-[200%] min-h-full grid grid-cols-2 transition-all duration-500 ease-in-out"
             :class="is_sent ? '-translate-x-1/2' : 'transalte-x-0'">
            <div class="p-4">
                <form wire:submit="save" class="space-y-5 w-full mt-16">
                    <flux:field>
                        <flux:label badge="erforderlich">Name</flux:label>

                        <flux:input wire:model="name" type="text" required/>

                        <flux:error name="name"/>
                    </flux:field>

                    <flux:field>
                        <flux:label badge="erforderlich">E-Mail-Adresse</flux:label>

                        <flux:input wire:model="email" type="email" required/>

                        <flux:error name="email"/>
                    </flux:field>

                    <flux:field>
                        <x-button>speichern</x-button>
                    </flux:field>
                </form>
            </div>
            <div class="bg-logo text-white p-4 flex flex-col justify-center items-center">
                <div class="max-w-xs text-center">
                    <h5 class="text-2xl font-bold">vielen dank</h5>
                    <p class="!text-sm font-thin">
                        Bitte prüfen Sie ihren Posteingang,
                        wir haben Ihnen ein E-Mail zum Bestätigen Ihrer Anmeldung gesendet.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


