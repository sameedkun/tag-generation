<div class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
    <div class="grid items-start gap-8 lg:grid-cols-2">
        <x-ui.card variant="sectioned">
            <x-ui.card-header>
                <x-ui.card-title class="text-xl">{{ __('Expiry tags (4 × 8)') }}</x-ui.card-title>
                <x-ui.card-description>
                    {{ __('Enter the information once and download a print-ready sheet of 32 tags.') }}
                </x-ui.card-description>
            </x-ui.card-header>

            <x-ui.card-content>
                <form wire:submit="downloadPdf" class="flex flex-col gap-5">
                    <x-ui.field :data-invalid="$errors->has('title')">
                        <x-ui.field-label for="title">{{ __('Title') }}</x-ui.field-label>
                        <x-ui.input
                            id="title"
                            wire:model.live.debounce.250ms="title"
                            maxlength="26"
                            placeholder="A.S BAKERS"
                            autocomplete="organization"
                            :aria-invalid="$errors->has('title')"
                        />
                        <x-ui.field-error :messages="$errors->get('title')" />
                    </x-ui.field>

                    <x-ui.field :data-invalid="$errors->has('address')">
                        <x-ui.field-label for="address">{{ __('Address') }}</x-ui.field-label>
                        <x-ui.textarea
                            id="address"
                            wire:model.live.debounce.250ms="address"
                            maxlength="70"
                            :rows="3"
                            :max-rows="3"
                            placeholder="RACHNATOWN, MAIN BAZAR, FAISALABAD"
                            autocomplete="street-address"
                            :aria-invalid="$errors->has('address')"
                        />
                        <x-ui.field-error :messages="$errors->get('address')" />
                    </x-ui.field>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.field :data-invalid="$errors->has('manufacturedAt')">
                            <x-ui.field-label>{{ __('Manufacturing date') }}</x-ui.field-label>
                            <x-ui.date-picker
                                id="manufactured-at"
                                wire:model.live="manufacturedAt"
                                :placeholder="__('Choose manufacturing date')"
                                caption-layout="dropdown"
                                week-start="monday"
                                width="w-full"
                            />
                            <x-ui.field-error :messages="$errors->get('manufacturedAt')" />
                        </x-ui.field>

                        <x-ui.field :data-invalid="$errors->has('expiresAt')">
                            <x-ui.field-label>{{ __('Expiry date') }}</x-ui.field-label>
                            <x-ui.date-picker
                                id="expires-at"
                                wire:model.live="expiresAt"
                                :min="$manufacturedAt"
                                :placeholder="__('Choose expiry date')"
                                caption-layout="dropdown"
                                week-start="monday"
                                width="w-full"
                            />
                            <x-ui.field-error :messages="$errors->get('expiresAt')" />
                        </x-ui.field>
                    </div>

                    <x-ui.button type="submit" size="lg" class="w-full sm:w-fit">
                        <x-ui.icon name="download" wire:loading.remove wire:target="downloadPdf" />
                        <x-ui.spinner wire:loading wire:target="downloadPdf" />
                        <span wire:loading.remove wire:target="downloadPdf">{{ __('Download PDF') }}</span>
                        <span wire:loading wire:target="downloadPdf">{{ __('Generating PDF...') }}</span>
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card variant="sectioned">
            <x-ui.card-header>
                <x-ui.card-title class="text-xl">{{ __('Compact tags (4 × 13)') }}</x-ui.card-title>
                <x-ui.card-description>
                    {{ __('Create 52 evenly sized tags on a US Letter sheet with corrected margins and spacing.') }}
                </x-ui.card-description>
            </x-ui.card-header>

            <x-ui.card-content>
                <form wire:submit="downloadCompactPdf" class="flex flex-col gap-5">
                    <x-ui.field :data-invalid="$errors->has('compactTitle')">
                        <x-ui.field-label for="compact-title">{{ __('Title') }}</x-ui.field-label>
                        <x-ui.input
                            id="compact-title"
                            wire:model.live.debounce.250ms="compactTitle"
                            maxlength="24"
                            placeholder="EID Bakers"
                            autocomplete="organization"
                            :aria-invalid="$errors->has('compactTitle')"
                        />
                        <x-ui.field-error :messages="$errors->get('compactTitle')" />
                    </x-ui.field>

                    <x-ui.field :data-invalid="$errors->has('compactAddress')">
                        <x-ui.field-label for="compact-address">{{ __('Address') }}</x-ui.field-label>
                        <x-ui.textarea
                            id="compact-address"
                            wire:model.live.debounce.250ms="compactAddress"
                            maxlength="30"
                            :rows="3"
                            :max-rows="3"
                            placeholder="Faisalabad"
                            autocomplete="street-address"
                            :aria-invalid="$errors->has('compactAddress')"
                        />
                        <x-ui.field-error :messages="$errors->get('compactAddress')" />
                    </x-ui.field>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-ui.field :data-invalid="$errors->has('compactManufacturedAt')">
                            <x-ui.field-label>{{ __('Manufacturing date') }}</x-ui.field-label>
                            <x-ui.date-picker
                                id="compact-manufactured-at"
                                wire:model.live="compactManufacturedAt"
                                :placeholder="__('Choose manufacturing date')"
                                caption-layout="dropdown"
                                week-start="monday"
                                width="w-full"
                            />
                            <x-ui.field-error :messages="$errors->get('compactManufacturedAt')" />
                        </x-ui.field>

                        <x-ui.field :data-invalid="$errors->has('compactExpiresAt')">
                            <x-ui.field-label>{{ __('Expiry date') }}</x-ui.field-label>
                            <x-ui.date-picker
                                id="compact-expires-at"
                                wire:model.live="compactExpiresAt"
                                :min="$compactManufacturedAt"
                                :placeholder="__('Choose expiry date')"
                                caption-layout="dropdown"
                                week-start="monday"
                                width="w-full"
                            />
                            <x-ui.field-error :messages="$errors->get('compactExpiresAt')" />
                        </x-ui.field>
                    </div>

                    <x-ui.button type="submit" size="lg" class="w-full sm:w-fit">
                        <x-ui.icon name="download" wire:loading.remove wire:target="downloadCompactPdf" />
                        <x-ui.spinner wire:loading wire:target="downloadCompactPdf" />
                        <span wire:loading.remove wire:target="downloadCompactPdf">{{ __('Download 4 × 13 PDF') }}</span>
                        <span wire:loading wire:target="downloadCompactPdf">{{ __('Generating PDF...') }}</span>
                    </x-ui.button>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
