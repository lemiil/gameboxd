<?php

namespace App\Console\Commands;

use App\Services\IGDB\IGDBGameImporter;
use Illuminate\Console\Command;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;

class ImportIGDBGames extends Command
{
    protected $signature = 'igdb:import {limit=500}';
    protected $description = 'Import games from IGDB';

    public function handle()
    {
        $importer = new IgdbGameImporter();
        $limit = (int) $this->argument('limit');
        $perPage = 500;
        $imported = 0;
        $startTime = microtime(true);

        $this->info("Importing $limit games from IGDB");

        while ($imported < $limit) {
            $batchSize = min($perPage, $limit - $imported);

            $igdbGames = IGDBGame::with([
                'cover', 'screenshots', 'platforms', 'genres', 'themes', 'involved_companies.company',
            ])
                ->limit($batchSize)
                ->offset($imported)
                ->get();

            if ($igdbGames->isEmpty()) {
                $this->warn("No more games!");
                break;
            }

            foreach ($igdbGames as $igdbGame) {
                $game = $importer->import($igdbGame);
                $this->info("Imported: {$game->name}");
            }

            $imported += $igdbGames->count();
        }

        $time = round(microtime(true) - $startTime, 2);
        $this->info("Importing done! Total imported: $imported in {$time} seconds");
        return 0;
    }
}
