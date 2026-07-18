<?php

use App\Enums\State;
use App\Models\Article;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Flux\Flux;

new #[Title('Dashboard - Articles')] class extends Component {
    use WithPagination;

    #[Url(as: 'user')]
    public string $filterUser = '';
    #[Url(as: 'state')]
    public string $filterState = '';

    #[Url(as: 'search')]
    public string $filterSearch = '';

    public function updatingFilterUser(): void
    {
        $this->resetPage();
    }

    public function updatingFilterState(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    #[Computed]
    public function states()
    {
        return State::cases();
    }

    #[Computed]
    public function articles()
    {
        $this->authorize('administer', Article::class);

        return Article::query()
            ->when($this->filterUser, fn ($q) => $q->where('user_id', $this->filterUser))
            ->when($this->filterState, fn ($q) => $q->where('state', $this->filterState))
            ->when(
                $this->filterSearch,
                fn ($q) => $q->where('title', 'like', "%{$this->filterSearch}%"),
            )
            ->orderByDesc('created_at')
            ->paginate(8);
    }

    public function changeState(Article $article, $state): void
    {
        $this->authorize('update', $article);
        $article->state = $state;
        $article->save();

        Flux::toast(
            __('State changed to :state', ['state' => State::from($state)->label()]),
            variant: 'success',
        );
    }

    public function destroyArticle(Article $article)
    {
        $this->authorize('delete', $article);
        $article->delete();

        Flux::toast(__('Article deleted successfully'), variant: 'success');
    }
};
?>

<div>
    <x-layouts::dashboard
        :heading="__('Articles')"
        :subheading="__('Manage articles for all users and states')"
    >
        <div class="mb-4 flex gap-3">
            <flux:select wire:model.live="filterUser">
                <flux:select.option option value="">
                    {{ __('All users') }}
                </flux:select.option>
                @foreach ($this->users as $user)
                    <flux:select.option value="{{ $user->id }}">
                        {{ $user->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="filterState">
                <flux:select.option value="">{{ __('All states') }}</flux:select.option>
                @foreach ($this->states as $state)
                    <flux:select.option value="{{ $state->value }}">
                        {{ $state->label() }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <flux:input
                type="search"
                wire:model.live.debounce.300ms="filterSearch"
                :placeholder="__('Search by title')"
            />
        </div>

        @if ($this->articles->isEmpty())
            <flux:callout variant="warning">
                <flux:callout.heading>
                    {{ __('No articles found') }}
                </flux:callout.heading>
                <flux:callout.text>
                    {{ __('No articles were found for the selected filters.') }}
                </flux:callout.text>
            </flux:callout>
        @endif

        <flux:table :paginate="$this->articles">
            @foreach ($this->articles as $article)
                <flux:table.row wire:key="article-{{ $article->id }}">
                    <flux:table.cell>
                        <flux:link href="{{ route('articles.show', ['article' => $article]) }}">
                            {{ $article->title }}
                        </flux:link>
                        <flux:text size="sm" class="mt-2">
                            {{ $article->user->name }},
                            {{ $article->formattedDateTime }}
                        </flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$article->state->color()">
                            {{ $article->state->label() }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="start">
                            <flux:button
                                icon="ellipsis-horizontal"
                                variant="ghost"
                                size="xs"
                                inset="top bottom"
                            ></flux:button>

                            <flux:menu>
                                <flux:menu.item
                                    icon="pencil-square"
                                    :href="route('articles.edit', ['article' => $article])"
                                >
                                    Bearbeiten
                                </flux:menu.item>

                                <flux:menu.separator />

                                @foreach ($this->states as $state)
                                    @if ($article->state !== $state)
                                        <flux:menu.item
                                            :icon="$state->icon()"
                                            wire:click="changeState('{{ $article->slug }}', '{{ $state->value }}')"
                                        >
                                            {{ $state->actionLabel() }}
                                        </flux:menu.item>
                                    @endif
                                @endforeach

                                <flux:menu.separator />

                                <flux:menu.item
                                    icon="trash"
                                    variant="danger"
                                    wire:confirm="{{ __('Are you sure you want to delete this article?') }}"
                                    wire:click="destroyArticle('{{ $article->slug }}')"
                                >
                                    {{ __('Delete') }}
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table>
    </x-layouts::dashboard>
</div>

<style>
    td {
        white-space: normal !important;
    }
</style>
