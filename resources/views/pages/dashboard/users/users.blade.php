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

                    {{-- Articles --}}
                    <flux:table.cell>
                        <flux:badge :color="$user->articles_count > 0 ? 'blue' : 'zinc'">
                            {{ $user->articles_count }}
                            {{ $user->articles_count == 1 ? __('Article') : __('Articles') }}
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

        <livewire:dashboard.shared.select-user />
    </x-layouts::dashboard>
</div>
