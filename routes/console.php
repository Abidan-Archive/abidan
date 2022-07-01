<?php

use App\Models\Event;
use App\Models\Report;
use App\Models\Dialogue;
use App\Models\Tag;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('eatReport {jsonPath}', function(String $jsonPath) {

    $data = json_decode(file_get_contents($jsonPath), true);

    foreach($data as $i => $e) {
        $event = Event::create($e);

        if ($i > 0) $this->newLine();
        $this->line("Eating $event->name ");
        $this->withProgressBar($e['reports'], function ($r) use ($event) {
            try {
                $report = $event->reports()->create($r);
                $report->refresh();

                foreach($r['dialogues'] as $i => $d) {
                    $d['order'] = $i;
                    $report->dialogues()->create($d);
                }

                foreach($r['tags'] as $t) {
                    $tag = Tag::firstOrCreate(['name' => $t]);
                    $report->tags()->attach($tag);
                }
            } catch (Exception $e) {
                $this->error("Error on ".$r['id']);
            }
        });

    }

})->purpose('Import all the scraped web data');

Artisan::command('killReports', function() {
    // Clear out everything since we're testing
    Event::truncate();
    Report::truncate();
    Dialogue::truncate();
    Tag::truncate();
    $this->info('All dead :)');
});
