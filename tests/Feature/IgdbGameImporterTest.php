<?php

use App\Models\Company;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Screenshot;
use App\Models\Theme;
use App\Services\IGDB\IgdbGameImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('imports a game correctly', function () {
    $fakeIgdbGame = (object)[
        'name' => 'Test Game',
        'slug' => 'test-game',
        'summary' => 'This is a test game',
        'first_release_date' => now(),
        'cover' => ['url' => '//images.igdb.com/cover.jpg'],
        'involved_companies' => [
            ['company' => ['name' => 'Test Company']]
        ],
        'platforms' => [
            ['name' => 'Test Platform']
        ],
        'genres' => [
            ['name' => 'Test Genre']
        ],
        'themes' => [
            ['name' => 'Test Theme']
        ],
        'screenshots' => [
            ['url' => '//images.igdb.com/screenshot.jpg']
        ]
    ];

    $importer = new IgdbGameImporter();
    $game = $importer->import($fakeIgdbGame);

    expect($game)->toBeInstanceOf(Game::class);
    expect(Game::where('name', 'Test Game')->exists())->toBeTrue();
    expect(Company::where('name', 'Test Company')->exists())->toBeTrue();
    expect(Platform::where('name', 'Test Platform')->exists())->toBeTrue();
    expect(Genre::where('name', 'Test Genre')->exists())->toBeTrue();
    expect(Theme::where('name', 'Test Theme')->exists())->toBeTrue();
    expect(Screenshot::where('game_id', $game->id)->count())->toBe(1);
});
