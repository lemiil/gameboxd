<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MarcReichel\IGDBLaravel\Models\Game as IGDBGame;

use App\Services\IgdbGameImporter;

class ImportIgdbGameByName extends Command
{
    protected $signature = 'igdb:importByName {name}';
    protected $description = 'Import igdb game by name';

    public function handle()
    {
        $importer = new IgdbGameImporter();
        $name = $this->argument('name');

        $igdbGame = IGDBGame::where('name', $name)->with([
            'cover', 'screenshots', 'platforms', 'genres', 'themes', 'involved_companies.company',
        ])->first();

        if (!$igdbGame) {
            $this->error("Game not found: $name");
            return 1;
        }

        $importer->import($igdbGame);
        $this->info("$name is imported!");
        return 0;
    }
}
