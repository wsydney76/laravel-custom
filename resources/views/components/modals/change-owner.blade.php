<?php

use App\Models\Article;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Flux\Flux;

new class extends Component {
    /** 'article' | 'user' */
    public string $mode = '';

    /** Article ID (mode=article) or source User ID (mode=user) */
    public ?int $subjectId = null;

    /** Target user ID to assign to */
    public string $toUserId = '';

    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    #[Computed]
    public function heading(): string
    {
        return $this->mode === 'user'
            ? __('Reassign All Articles')
            : __('Change owner');
    }

    #[Computed]
    public function subheading(): string
    {
        if ($this->mode === 'user') {
            $name = User::find($this->subjectId)?->name ?? '';
            return __('Reassign all articles by :name to a new owner.', ['name' => $name]);
        }

        return __('Select a new owner for this article.');
    }

    #[On('open-change-owner')]
    public function open(string $mode, int $id, string $currentUserId = ''): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $this->mode      = $mode;
        $this->subjectId = $id;
        $this->toUserId  = $currentUserId;

        $this->modal('change-owner')->show();
    }

    public function apply(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        if ($this->mode === 'article') {
            $article          = Article::findOrFail($this->subjectId);
            $article->user_id = (int) $this->toUserId;
            $article->save();

            Flux::toast(__('Owner updated successfully'), variant: 'success');
        } elseif ($this->mode === 'user') {
            Article::where('user_id', $this->subjectId)
                ->update(['user_id' => (int) $this->toUserId]);

            Flux::toast(__('All articles reassigned successfully'), variant: 'success');
        }

        $this->modal('change-owner')->close();
        $this->mode      = '';
        $this->subjectId = null;
        $this->toUserId  = '';
    }
};
?>

<div>
    <flux:modal name="change-owner" class="min-w-88">
        <flux:heading size="lg">{{ $this->heading }}</flux:heading>
        <flux:subheading class="mt-1 mb-6">{{ $this->subheading }}</flux:subheading>

        <flux:select wire:model="toUserId" :label="__('Owner')">
            <flux:select.option value="">{{ __('Select a user') }}</flux:select.option>
            @foreach ($this->users as $user)
                @if ($this->mode !== 'user' || $user->id !== $this->subjectId)
                    <flux:select.option value="{{ $user->id }}">
                        {{ $user->name }}
                    </flux:select.option>
                @endif
            @endforeach
        </flux:select>

        <div class="mt-6 flex justify-end gap-2">
            <flux:modal.close>
                <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>
            <flux:button
                variant="primary"
                wire:click="apply"
                :disabled="! $this->toUserId"
            >
                {{ __('Apply') }}
            </flux:button>
        </div>
    </flux:modal>
</div>

