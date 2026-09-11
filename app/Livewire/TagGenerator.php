<?php

namespace App\Livewire;

use App\Actions\GenerateCompactTagsPdf;
use App\Actions\GenerateExpiryTagsPdf;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts::app')]
#[Title('Expiry Tag Generator')]
class TagGenerator extends Component
{
    public string $title = 'A.S BAKERS';

    public string $address = 'RACHNATOWN, MAIN BAZAR, FAISALABAD';

    public string $manufacturedAt = '';

    public string $expiresAt = '';

    public string $compactTitle = 'A.S Bakers';

    public string $compactAddress = 'Faisalabad';

    public string $compactManufacturedAt = '';

    public string $compactExpiresAt = '';

    public function mount(): void
    {
        $this->manufacturedAt = today()->toDateString();
        $this->expiresAt = today()->addWeek()->toDateString();
        $this->compactManufacturedAt = today()->toDateString();
        $this->compactExpiresAt = today()->addMonth()->toDateString();
    }

    /**
     * @return array<string, list<string>>
     */
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:26'],
            'address' => ['required', 'string', 'max:70'],
            'manufacturedAt' => ['required', 'date_format:Y-m-d'],
            'expiresAt' => ['required', 'date_format:Y-m-d', 'after_or_equal:manufacturedAt'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'expiresAt.after_or_equal' => 'The expiry date must be on or after the manufacturing date.',
        ];
    }

    public function downloadPdf(GenerateExpiryTagsPdf $generateExpiryTagsPdf): StreamedResponse
    {
        /** @var array{title: string, address: string, manufacturedAt: string, expiresAt: string} $tag */
        $tag = $this->validate($this->rules());

        return $generateExpiryTagsPdf->handle($tag);
    }

    public function downloadCompactPdf(GenerateCompactTagsPdf $generateCompactTagsPdf): StreamedResponse
    {
        /** @var array{compactTitle: string, compactAddress: string, compactManufacturedAt: string, compactExpiresAt: string} $validated */
        $validated = $this->validate([
            'compactTitle' => ['required', 'string', 'max:24'],
            'compactAddress' => ['required', 'string', 'max:30'],
            'compactManufacturedAt' => ['required', 'date_format:Y-m-d'],
            'compactExpiresAt' => ['required', 'date_format:Y-m-d', 'after_or_equal:compactManufacturedAt'],
        ], [
            'compactExpiresAt.after_or_equal' => 'The expiry date must be on or after the manufacturing date.',
        ]);

        return $generateCompactTagsPdf->handle([
            'title' => $validated['compactTitle'],
            'address' => $validated['compactAddress'],
            'manufacturedAt' => $validated['compactManufacturedAt'],
            'expiresAt' => $validated['compactExpiresAt'],
        ]);
    }

    public function render(): View
    {
        return view('livewire.tag-generator');
    }
}
