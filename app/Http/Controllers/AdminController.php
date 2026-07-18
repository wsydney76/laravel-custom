<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function articles()
    {
        $articles = Article::paginate(12);
        return view('admin.articles', compact('articles'));
    }

    public function users()
    {
        $users = User::paginate(12);
        return view('admin.users', compact('users'));
    }
}
