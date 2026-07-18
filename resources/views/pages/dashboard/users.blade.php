<?php

use App\Enums\Locale;
use App\Models\Article;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

new #[Title('Dashboard - Users')] class extends Component {
    use WithPagination;

    #[Url(as: 'role')]
    public string $filterRole = '';

    #[Url(as: 'verified')]
    public string $filterVerified = '';

    #[Url(as: 'search')]
    public string $filterSearch = '';

    public function mount(): void
    {
        if (
            ! auth()
                ->user()
                ?->isAdmin()
        ) {
            abort(403);
        }
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->filterRole, fn ($q) => $q->where('role', $this->filterRole))
            ->when($this->filterVerified === '1', fn ($q) => $q->whereNotNull('email_verified_at'))
            ->when($this->filterVerified === '0', fn ($q) => $q->whereNull('email_verified_at'))
            ->when(
                $this->filterSearch,
                fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->filterSearch}%")->orWhere(
                        'email',
                        'like',
                        "%{$this->filterSearch}%",
                    );
                }),
            )
            ->withCount('articles')
            ->orderBy('name')
            ->paginate(15);
    }

    #[Computed]
    public function locales()
    {
        return Locale::cases();
    }

    public function changeRole(User $user, string $role): void
    {
        abort_unless(
            auth()
                ->user()
                ?->isAdmin(),
            403,
        );

        if ($user->id === auth()->id() && $role !== 'admin') {
            Flux::toast(__('You cannot remove your own admin role.'), variant: 'danger');
            return;
        }

        $user->role = $role;
        $user->save();

        Flux::toast(__('Role updated to :role', ['role' => ucfirst($role)]), variant: 'success');
    }

    public function verifyEmail(User $user): void
    {
        abort_unless(
            auth()
                ->user()
                ?->isAdmin(),
            403,
        );

        $user->email_verified_at = now();
        $user->save();

        Flux::toast(__('Email verified successfully'), variant: 'success');
    }

    public function deleteUser(User $user): void
    {
        abort_unless(
            auth()
                ->user()
                ?->isAdmin(),
            403,
        );

        if ($user->id === auth()->id()) {
            Flux::toast(__('You cannot delete your own account.'), variant: 'danger');
            return;
        }

        $articleCount = $user->articles()->count();

        if ($articleCount > 0) {
            Flux::toast(
                __(
                    'Cannot delete :name: they still own :count article(s). Reassign or delete their articles first.',
                    [
                        'name' => $user->name,
                        'count' => $articleCount,
                    ],
                ),
                variant: 'danger',
            );
            return;
        }

        $user->delete();

        Flux::toast(__('User deleted successfully'), variant: 'success');
    }

    public ?int $currentOwnerUserId = null;
    public string $changeOwnerUserId = '';

    public function openChangeOwner(User $user): void
    {
        $this->authorize('administer', Article::class);
        $this->currentOwnerUserId = $user->id;
        $this->changeOwnerUserId = (string) $user->id;
        $this->modal('change-owner')->show();
    }

    public function applyChangeOwner(): void
    {
        $this->authorize('administer', Article::class);

        $currentOwner = User::findOrFail($this->currentOwnerUserId);

        $articles = $currentOwner->articles;

        foreach ($articles as $article) {
            $article->user_id = (int) $this->changeOwnerUserId;
            $article->save();
        }

        $this->modal('change-owner')->close();
        $this->currentOwnerUserId = null;
        $this->changeOwnerUserId = '';

        Flux::toast(__('Owner updated successfully'), variant: 'success');
    }

    public function resetFilters(): void
    {
        $this->reset();
        $this->resetPage();
    }
};
?>

<div>
    <x-layouts::dashboard
        :heading="__('Users')"
        :subheading="__('Manage all registered users and their roles')"
    >
        <div class="mb-4 flex gap-3">
            <flux:select wire:model.live="filterRole">
                <flux:select.option value="">{{ __('All roles') }}</flux:select.option>
                <flux:select.option value="admin">{{ __('Admin') }}</flux:select.option>
                <flux:select.option value="member">{{ __('Member') }}</flux:select.option>
            </flux:select>

            <flux:select wire:model.live="filterVerified">
                <flux:select.option value="">
                    {{ __('All verification statuses') }}
                </flux:select.option>
                <flux:select.option value="1">{{ __('Verified') }}</flux:select.option>
                <flux:select.option value="0">{{ __('Unverified') }}</flux:select.option>
            </flux:select>

            <flux:input
                type="search"
                wire:model.live.debounce.300ms="filterSearch"
                :placeholder="__('Search by name or email')"
            />

            <flux:button
                class="mt-1"
                icon="x-circle"
                variant="ghost"
                square
                :disabled="!$this->filterRole && !$this->filterVerified && !$this->filterSearch"
                tooltip="{{ __('Reset filters') }}"
                size="sm"
                wire:click="resetFilters"
            ></flux:button>
        </div>

        @if ($this->users->isEmpty())
            <flux:callout variant="warning">
                <flux:callout.heading>
                    {{ __('No users found') }}
                </flux:callout.heading>
                <flux:callout.text>
                    {{ __('No users were found for the selected filters.') }}
                </flux:callout.text>
            </flux:callout>
        @endif

        <flux:table :paginate="$this->users">
            @foreach ($this->users as $user)
                <flux:table.row wire:key="user-{{ $user->id }}">
                    {{-- Name & Email --}}
                    <flux:table.cell>
                        <div class="flex items-center gap-3">
                            <flux:avatar size="sm" :name="$user->name" color="auto" />
                            <div>
                                <flux:text class="font-medium">
                                    {{ $user->name }}
                                    @if ($user->id === auth()->id())
                                        <flux:badge size="sm" color="blue" class="ml-1">
                                            {{ __('You') }}
                                        </flux:badge>
                                    @endif
                                </flux:text>
                                <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                                    {{ $user->email }}
                                </flux:text>
                            </div>
                        </div>
                    </flux:table.cell>

                    {{-- Role --}}
                    <flux:table.cell>
                        <flux:badge
                            :color="$user->role === 'admin' ? 'amber' : 'zinc'"
                            :icon="$user->role === 'admin' ? 'shield-check' : 'user'"
                        >
                            {{ ucfirst($user->role) }}
                        </flux:badge>
                    </flux:table.cell>

                    {{-- Email Verified --}}
                    <flux:table.cell>
                        @if ($user->email_verified_at)
                            <flux:badge color="green" icon="check-circle">
                                {{ __('Verified') }}
                            </flux:badge>
                        @else
                            <flux:badge color="red" icon="x-circle">
                                {{ __('Unverified') }}
                            </flux:badge>
                        @endif
                    </flux:table.cell>

                    {{-- Locale --}}
                    <flux:table.cell>
                        <flux:text size="sm">{{ $user->locale->label() }}</flux:text>
                    </flux:table.cell>

                    {{-- Articles --}}
                    <flux:table.cell>
                        <flux:badge
                            :color="$user->articles_count > 0 ? 'blue' : 'zinc'"
                            :href="$user->articles_count > 0 ? route('dashboard.articles', ['user' => $user->id]) : null"
                        >
                            {{ $user->articles_count }}
                        </flux:badge>
                    </flux:table.cell>

                    {{-- Registered --}}
                    <flux:table.cell>
                        <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">
                            {{ $user->created_at?->diffForHumans() }}
                        </flux:text>
                    </flux:table.cell>

                    {{-- Actions --}}
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button
                                icon="ellipsis-horizontal"
                                variant="ghost"
                                size="xs"
                                inset="top bottom"
                            ></flux:button>

                            <flux:menu>
                                @if ($user->articles_count > 0)
                                    <flux:menu.item
                                        icon="newspaper"
                                        :href="route('dashboard.articles', ['user' => $user->id])"
                                    >
                                        {{ __('Manage Articles') }}
                                    </flux:menu.item>

                                    <flux:menu.item
                                        icon="arrows-right-left"
                                        wire:click="openChangeOwner({{ $user->id }})"
                                    >
                                        {{ __('Reassign Articles') }}
                                    </flux:menu.item>

                                    <flux:menu.separator />
                                @endif

                                @if ($user->role !== 'admin')
                                    <flux:menu.item
                                        icon="shield-check"
                                        wire:click="changeRole({{ $user->id }}, 'admin')"
                                        wire:confirm="{{ __('Promote :name to admin?', ['name' => $user->name]) }}"
                                    >
                                        {{ __('Promote to Admin') }}
                                    </flux:menu.item>
                                @else
                                    <flux:menu.item
                                        icon="user"
                                        :disabled="$user->id === auth()->id()"
                                        wire:click="changeRole({{ $user->id }}, 'member')"
                                        wire:confirm="{{ __('Demote :name to member?', ['name' => $user->name]) }}"
                                    >
                                        {{ __('Demote to Member') }}
                                    </flux:menu.item>
                                @endif

                                @if (! $user->email_verified_at)
                                    <flux:menu.item
                                        icon="envelope-open"
                                        wire:click="verifyEmail({{ $user->id }})"
                                        wire:confirm="{{ __('Manually verify email for :name?', ['name' => $user->name]) }}"
                                    >
                                        {{ __('Verify Email') }}
                                    </flux:menu.item>
                                @endif

                                @if ($user->id !== auth()->id())
                                    <flux:menu.separator />

                                    <flux:menu.item
                                        icon="trash"
                                        variant="danger"
                                        wire:confirm="{{ __('Are you sure you want to delete :name? This action cannot be undone.', ['name' => $user->name]) }}"
                                        wire:click="deleteUser({{ $user->id }})"
                                    >
                                        {{ __('Delete') }}
                                    </flux:menu.item>
                                @endif
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table>

        <x-dashboard.select-owner
            :heading="__('Reassign Articles')"
            :subheading="__('Select a new owner for articles from this user.')"
            :users="$this->users"
        />
    </x-layouts::dashboard>
</div>

<style>
    td {
        white-space: normal !important;
    }

    button {
        cursor: pointer;
    }
</style>
