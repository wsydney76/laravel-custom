# Laravel Custom Starter

This is a basic Laravel Custom Starter project that allows you to experiment with Laravel features and functionalities in a safe environment. 

You can use this project to test out new ideas, learn Laravel, prototype applications, or get familiar developing with AI support.

* Uses Livewire, Flux, Fortify, Vite, Tailwind, SQLite.
* Authentication and account management are from Laravel's Livewire starter kit.

## Installation

Git clone this repository and run the following commands under DDEV:

```bash
bash setup/install <project_name>
```

Creates an initial admin user with the following credentials:
* Email: `admin@example.com`
* Password: `kirby-tutorial`

Or set up your development environment manually, and manually run the commands from `setup/install`:    

```bash
composer install &&
composer run setup &&
artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
artisan migrate:fresh --seed &&
```

Adjust the `.env` file to your needs.

## Thanks

Thanks Aylin, Lucy, Lori for this amazing workshop.
