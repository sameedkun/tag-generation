<?php

namespace App\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerateExpiryTagsPdf
{
    /**
     * @param  array{title: string, address: string, manufacturedAt: string, expiresAt: string}  $tag
     */
    public function handle(array $tag): StreamedResponse
    {
        $content = $this->content($tag);

        return response()->streamDownload(
            static function () use ($content): void {
                echo $content;
            },
            $this->filename($tag),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @param  array{title: string, address: string, manufacturedAt: string, expiresAt: string}  $tag
     */
    public function content(array $tag): string
    {
        return Pdf::loadView('pdf.expiry-tags', $this->viewData($tag))
            ->setPaper('a4')
            ->setOption('defaultFont', 'Helvetica')
            ->output();
    }

    /**
     * @param  array{title: string, address: string, manufacturedAt: string, expiresAt: string}  $tag
     */
    public function filename(array $tag): string
    {
        return sprintf(
            'expiry-tags_mfg-%s_to_exp-%s.pdf',
            $this->date($tag['manufacturedAt'])->format('Y-m-d'),
            $this->date($tag['expiresAt'])->format('Y-m-d'),
        );
    }

    /**
     * @param  array{title: string, address: string, manufacturedAt: string, expiresAt: string}  $tag
     * @return array{title: string, address: string, manufacturedAt: string, expiresAt: string}
     */
    private function viewData(array $tag): array
    {
        return [
            'title' => Str::of($tag['title'])->squish()->upper()->toString(),
            'address' => Str::of($tag['address'])->squish()->upper()->toString(),
            'manufacturedAt' => $this->date($tag['manufacturedAt'])->format('d/m/y'),
            'expiresAt' => $this->date($tag['expiresAt'])->format('d/m/y'),
        ];
    }

    private function date(string $date): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('!Y-m-d', $date);
    }
}
