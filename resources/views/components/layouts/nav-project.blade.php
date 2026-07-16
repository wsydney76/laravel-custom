<flux:navbar.item :current="request()->routeIs('articles.*')" href="{{ route('articles.index') }}">
    {{ __('Articles') }}
</flux:navbar.item>
