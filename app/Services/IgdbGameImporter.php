<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Screenshot;
use App\Models\Theme;

class IgdbGameImporter
{
    public function import($igdbGame): Game
    {
        $releaseDate = $igdbGame->first_release_date
            ? $igdbGame->first_release_date->toArray()['formatted']
            : null;

        $coverUrl = optional($igdbGame->cover)['url'];
        if ($coverUrl) {
            $coverUrl = str_replace('t_thumb', 't_cover_big_2x', $coverUrl);
        }

        $game = Game::updateOrCreate(
            [
                'name' => $igdbGame->name,
                'slug' => $igdbGame->slug,
                'summary' => $igdbGame->summary,
                'release_date' => $releaseDate,
                'cover_url' => $coverUrl,
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
                $url = str_replace('t_thumb', 't_720p', $shot['url']);
                Screenshot::updateOrCreate([
                    'game_id' => $game->id,
                    'url' => $url,
                ]);
            }
        }

        return $game;
    }
}
