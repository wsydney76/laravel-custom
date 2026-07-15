<flux:brand
    :href="route('home')"
    :name="config('app.name')"
    {{ $attributes }}
>
    <x-slot name="logo">
        <flux:icon icon="bolt" />
    </x-slot>
</flux:brand>
