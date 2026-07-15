<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    use WithoutModelEvents;

    // php artisan db:seed --class=ArticleSeeder
    public function run(): void
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        Article::factory(20)->create(['user_id' => $user->id]);
    }
}
