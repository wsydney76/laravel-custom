<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Dashboard - Notifications')] class extends Component {
    #[Computed]
    public function notifications()
    {
        return auth()
            ->user()
            ->unreadNotifications()
            ->latest()
            ->paginate(10);
    }

    public function markAsRead(string $id): void
    {
        $notification = auth()
            ->user()
            ->notifications()
            ->findOrFail($id);
        $notification->markAsRead();
        unset($this->notifications);
    }
};
?>

<div>
    <x-layouts::dashboard
        :heading="__('Notifications')"
        :subheading="__('Manage your notifications.')"
    >
        <div class="space-y-4">
            @forelse ($this->notifications() as $notification)
                @switch($notification->type)
                    @case('App\Notifications\ArticleCreated')
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp

                        <flux:card class="flex items-start gap-4 p-4">
                            <div
                                class="flex shrink-0 items-center justify-center rounded-full bg-sky-100 p-2 dark:bg-sky-900"
                            >
                                <flux:icon
                                    name="document-text"
                                    class="size-5 text-sky-600 dark:text-sky-400"
                                />
                            </div>

                            <div class="flex flex-1 flex-col gap-1">
                                <div class="flex items-start justify-between gap-2">
                                    <flux:heading size="sm">
                                        <a
                                            href="{{ route('articles.edit', $data['article_slug']) }}"
                                            class="hover:text-sky-700 hover:underline"
                                        >
                                            {{ $data['article_title'] }}
                                        </a>
                                    </flux:heading>
                                </div>

                                <flux:text size="sm">
                                    {{ __('New article published by :name', ['name' => $data['creator_name']]) }}
                                </flux:text>

                                <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">
                                    <span
                                        title="{{ $notification->created_at->toFormattedDateString() }}"
                                    >
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </flux:text>
                            </div>

                            @if ($isUnread)
                                <flux:button
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    variant="ghost"
                                    size="sm"
                                    icon="check"
                                    title="{{ __('Mark as read') }}"
                                />
                            @endif
                        </flux:card>

                        @break
                    @case('App\Notifications\NewUserRegistered')
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp

                        <flux:card class="flex items-start gap-4 p-4">
                            <div
                                class="flex shrink-0 items-center justify-center rounded-full bg-emerald-100 p-2 dark:bg-emerald-900"
                            >
                                <flux:icon
                                    name="user-plus"
                                    class="size-5 text-emerald-600 dark:text-emerald-400"
                                />
                            </div>

                            <div class="flex flex-1 flex-col gap-1">
                                <div class="flex items-start justify-between gap-2">
                                    <flux:heading size="sm">
                                        <a
                                            href="{{ route('dashboard.users') }}"
                                            class="hover:text-emerald-700 hover:underline"
                                        >
                                            {{ $data['name'] }}
                                        </a>
                                    </flux:heading>
                                </div>

                                <flux:text size="sm">
                                    {{ __('New user registered: :email', ['email' => $data['email']]) }}
                                </flux:text>

                                <flux:text size="sm" class="text-zinc-400 dark:text-zinc-500">
                                    <span
                                        title="{{ $notification->created_at->toFormattedDateString() }}"
                                    >
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </flux:text>
                            </div>

                            @if ($isUnread)
                                <flux:button
                                    wire:click="markAsRead('{{ $notification->id }}')"
                                    variant="ghost"
                                    size="sm"
                                    icon="check"
                                    title="{{ __('Mark as read') }}"
                                />
                            @endif
                        </flux:card>

                        @break
                    @default
                        <!-- Handle other types of notifications -->
                @endswitch
            @empty
                <flux:card class="p-6 text-center">
                    <flux:text>{{ __('You have no unread notifications.') }}</flux:text>
                </flux:card>
            @endforelse

            {{ $this->notifications()->links() }}
        </div>
    </x-layouts::dashboard>
</div>
