<?php

use App\Actions\GenerateCompactTagsPdf;
use App\Actions\GenerateExpiryTagsPdf;
use App\Livewire\TagGenerator;
use Livewire\Livewire;

test('the tag generator page renders', function () {
    $response = $this->get('/');

    $response
        ->assertSuccessful()
        ->assertSee('Expiry tags (4 × 8)')
        ->assertSee('Compact tags (4 × 13)')
        ->assertDontSee('Print preview');
});

test('the tag generator starts with useful defaults', function () {
    Livewire::test(TagGenerator::class)
        ->assertSet('title', 'A.S BAKERS')
        ->assertSet('address', 'RACHNATOWN, MAIN BAZAR, FAISALABAD')
        ->assertSet('manufacturedAt', today()->toDateString())
        ->assertSet('expiresAt', today()->addWeek()->toDateString())
        ->assertSet('compactTitle', 'EID Bakers')
        ->assertSet('compactAddress', 'Faisalabad')
        ->assertSet('compactManufacturedAt', today()->toDateString())
        ->assertSet('compactExpiresAt', today()->addMonth()->toDateString());
});

test('the expiry date cannot be before the manufacturing date', function () {
    Livewire::test(TagGenerator::class)
        ->set('manufacturedAt', '2026-08-15')
        ->set('expiresAt', '2026-08-14')
        ->call('downloadPdf')
        ->assertHasErrors(['expiresAt' => 'after_or_equal']);
});

test('a pdf downloads with its manufacturing and expiry dates in the filename', function () {
    Livewire::test(TagGenerator::class)
        ->set('title', 'A.S BAKERS')
        ->set('address', 'RACHNATOWN, MAIN BAZAR, FAISALABAD')
        ->set('manufacturedAt', '2026-08-08')
        ->set('expiresAt', '2026-08-15')
        ->call('downloadPdf')
        ->assertHasNoErrors()
        ->assertFileDownloaded('expiry-tags_mfg-2026-08-08_to_exp-2026-08-15.pdf');
});

test('the generated document is a pdf built from 32 tags', function () {
    $viewData = [
        'title' => 'A.S BAKERS',
        'address' => 'RACHNATOWN, MAIN BAZAR, FAISALABAD',
        'manufacturedAt' => '08/08/26',
        'expiresAt' => '15/08/26',
    ];

    $content = app(GenerateExpiryTagsPdf::class)->content([
        'title' => 'A.S BAKERS',
        'address' => 'RACHNATOWN, MAIN BAZAR, FAISALABAD',
        'manufacturedAt' => '2026-08-08',
        'expiresAt' => '2026-08-15',
    ]);

    expect($content)->toStartWith('%PDF-')
        ->and(substr_count(view('pdf.expiry-tags', $viewData)->render(), '<section class="tag '))->toBe(32);
});

test('the compact expiry date cannot be before its manufacturing date', function () {
    Livewire::test(TagGenerator::class)
        ->set('compactManufacturedAt', '2026-08-15')
        ->set('compactExpiresAt', '2026-08-14')
        ->call('downloadCompactPdf')
        ->assertHasErrors(['compactExpiresAt' => 'after_or_equal']);
});

test('the compact pdf downloads with its title and dates in the filename', function () {
    Livewire::test(TagGenerator::class)
        ->set('compactTitle', 'EID Bakers')
        ->set('compactAddress', 'Faisalabad')
        ->set('compactManufacturedAt', '2026-08-08')
        ->set('compactExpiresAt', '2026-09-08')
        ->call('downloadCompactPdf')
        ->assertHasNoErrors()
        ->assertFileDownloaded('eid-bakers-tags_mfg-2026-08-08_to_exp-2026-09-08.pdf');
});

test('the compact document is a letter pdf built from 52 evenly sized tags', function () {
    $tag = [
        'title' => 'EID Bakers',
        'address' => 'Faisalabad',
        'manufacturedAt' => '2026-08-08',
        'expiresAt' => '2026-09-08',
    ];

    $content = app(GenerateCompactTagsPdf::class)->content($tag);
    $view = view('pdf.compact-tags', [
        'title' => 'EID Bakers',
        'address' => 'Faisalabad',
        'manufacturedAt' => '08/08/26',
        'expiresAt' => '08/09/26',
    ])->render();

    expect($content)->toStartWith('%PDF-')
        ->and(substr_count($view, '<section class="tag '))->toBe(52)
        ->and(substr_count($view, 'tag-row-'))->toBe(52)
        ->and(substr_count($view, 'tag-column-'))->toBe(52);
});
