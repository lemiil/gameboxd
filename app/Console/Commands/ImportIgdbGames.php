<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;
use App\Models\Game;
use App\Models\Company;
use App\Models\Platform;
use App\Models\Genre;
use App\Models\Theme;
use App\Models\Screenshot;
use Carbon\Carbon;


//TODO need to add rate limit, because api is baka. also need somehow managed games that already imported, and, of course, some fixes... i love my work
class ImportIgdbGames extends Command
{
    protected $signature = 'igdb:import {limit=50}';
    protected $description = 'Import games from IGDB using marcreichel/igdb-laravel';

    public function handle()
    {
        $limit = (int) $this->argument('limit');

        $this->info("Importing $limit games from IGDB");

        $igdbGames = IGDBGame::with([
            'cover',
            'artworks',
            'screenshots',
            'platforms',
            'genres',
            'themes',
            'involved_companies.company',
        ])
            ->limit($limit)
            ->get();

        foreach ($igdbGames as $igdbGame) {
            $this->importGame($igdbGame);
        }

        $this->info("Importing is done!!");
        return 0;
    }

    protected function importGame($igdbGame)
    {
        $releaseDate = $igdbGame->first_release_date
            ? Carbon::createFromTimestamp($igdbGame->first_release_date)->toDateString()
            : null;

        $game = Game::updateOrCreate(
            ['name' => $igdbGame->name],
            [
                'release_date' => $releaseDate,
                'cover_url' => optional($igdbGame->cover)['url'],
                'artwork_url' => isset($igdbGame->artworks[0]['url']) ? $igdbGame->artworks[0]['url'] : null,
            ]
        );

        if ($igdbGame->involved_companies) {
            $companyIds = collect($igdbGame->involved_companies)
                ->map(fn($c) => Company::firstOrCreate(['name' => $c['company']['name']])->id)
                ->toArray();
            $game->companies()->sync($companyIds);
        }

        if ($igdbGame->platforms) {
            $platformIds = collect($igdbGame->platforms)
                ->map(fn($p) => Platform::firstOrCreate(['name' => $p['name']])->id)
                ->toArray();
            $game->platforms()->sync($platformIds);
        }

        if ($igdbGame->genres) {
            $genreIds = collect($igdbGame->genres)
                ->map(fn($g) => Genre::firstOrCreate(['name' => $g['name']])->id)
                ->toArray();
            $game->genres()->sync($genreIds);
        }

        if ($igdbGame->themes) {
            $themeIds = collect($igdbGame->themes)
                ->map(fn($t) => Theme::firstOrCreate(['name' => $t['name']])->id)
                ->toArray();
            $game->themes()->sync($themeIds);
        }

        if ($igdbGame->screenshots) {
            foreach ($igdbGame->screenshots as $shot) {
                Screenshot::updateOrCreate([
                    'game_id' => $game->id,
                    'url' => $shot['url'],
                ]);
            }
        }

        $this->info("Imported: {$game->name}");
    }
}
