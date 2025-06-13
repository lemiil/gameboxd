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

class ImportIgdbGames extends Command
{
    protected $signature = 'igdb:import {limit=50}';
    protected $description = 'Import games from IGDB';

    public function handle()
    {
        $limit = (int) $this->argument('limit');
        $perPage = 500;
        $imported = 0;

        $startTime = microtime(true);

        $this->info("Importing $limit games from IGDB");

        while ($imported < $limit) {
            $batchSize = min($perPage, $limit - $imported);

            $igdbGames = IGDBGame::with([
                'cover',
                'screenshots',
                'platforms',
                'genres',
                'themes',
                'involved_companies.company',
            ])
                ->limit($batchSize)
                ->offset($imported)
                ->get();


            if ($igdbGames->isEmpty()) {
                $this->warn("No more games!");
                break;
            }

            foreach ($igdbGames as $igdbGame) {
                $this->importGame($igdbGame);
            }

            $imported += $igdbGames->count();
        }


        $endTime = microtime(true);
        $executionTime = floor(($endTime - $startTime) * 100) / 100;

        $this->info("Importing is done!! Total imported: $imported in $executionTime seconds");
        return 0;
    }


    protected function importGame($igdbGame)
    {
        $releaseDate = $igdbGame->first_release_date
            ? $igdbGame->first_release_date->toArray()['formatted']
            : null;

        $game = Game::updateOrCreate(
            [
                'name' => $igdbGame->name,
                'slug' => $igdbGame->slug,
                'summary' => $igdbGame->summary,
                'release_date' => $releaseDate,
                'cover_url' => optional($igdbGame->cover)['url'],
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
