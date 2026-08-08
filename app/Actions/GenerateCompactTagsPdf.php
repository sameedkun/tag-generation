<?php

namespace App\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerateCompactTagsPdf
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
        return Pdf::loadView('pdf.compact-tags', $this->viewData($tag))
            ->setPaper('letter')
            ->setOption('defaultFont', 'Times-Roman')
            ->output();
    }

    /**
     * @param  array{title: string, address: string, manufacturedAt: string, expiresAt: string}  $tag
     */
    public function filename(array $tag): string
    {
        return sprintf(
            '%s-tags_mfg-%s_to_exp-%s.pdf',
            Str::of($tag['title'])->slug()->limit(40, '')->value() ?: 'compact',
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
            'title' => Str::of($tag['title'])->squish()->toString(),
            'address' => Str::of($tag['address'])->squish()->toString(),
            'manufacturedAt' => $this->date($tag['manufacturedAt'])->format('d/m/y'),
            'expiresAt' => $this->date($tag['expiresAt'])->format('d/m/y'),
        ];
    }

    private function date(string $date): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat('!Y-m-d', $date);
    }
}
