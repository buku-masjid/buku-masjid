<?php

use App\Models\Partner;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
})->describe('Display an inspiring quote');

Artisan::command('partner:generate {type_code} {--count=} {--reset}', function () {
    if (app()->environment('production')) {
        $this->error('This command is only for non production!');

        return 0;
    }

    $typeCode = $this->argument('type_code');
    if (!in_array($typeCode, array_keys((new Partner)->getAvailableTypes()))) {
        $this->error("Partner type code does not exists: '{$typeCode}'");

        return 0;
    }
    if ($this->option('reset')) {
        Schema::disableForeignKeyConstraints();
        DB::table('partners')->where('type_code', $typeCode)->delete();
        Schema::enableForeignKeyConstraints();
    }
    config(['app.faker_locale' => 'id_ID']);
    $count = $this->option('count') ?: 1;
    $partnerFactory = factory(Partner::class);
    $partnerFactory->times($count);
    $partnerFactory->create(['type_code' => $typeCode]);
})->describe('Generate fake partner records');

Artisan::command('partner:upgrade-type-levels', function () {
    $partners = DB::table('partners')->get();
    $updatedPartnersCount = 0;
    foreach ($partners as $partner) {
        $newTypeCode = '["'.$partner->type_code.'"]';
        $newLevelCode = $partner->level_code ? '{"'.$partner->type_code.'":"'.$partner->level_code.'"}' : null;
        $updated = DB::table('partners')->where('id', $partner->id)->update([
            'type_code' => $newTypeCode,
            'level_code' => $newLevelCode,
        ]);

        if ($updated) {
            $updatedPartnersCount++;
        }
    }
    $this->comment('Done upgrading '.$updatedPartnersCount.' partners');
})->describe('Ugprade existing partner types and levels');
