<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ public_path('css/expiry-tags.css') }}">
        <title>Expiry Tags</title>
    </head>
    <body>
        <main class="tag-sheet">
            @foreach (range(0, 7) as $row)
                @foreach (range(0, 3) as $column)
                    <section class="tag tag-column-{{ $column }} tag-row-{{ $row }}">
                        <div class="tag-title">{{ $title }}</div>
                        <div class="tag-address">{{ $address }}</div>
                        <div class="tag-label tag-manufactured-label">Mfg Date</div>
                        <div class="tag-date tag-manufactured-date">{{ $manufacturedAt }}</div>
                        <div class="tag-label tag-expiry-label">Exp Date</div>
                        <div class="tag-date tag-expiry-date">{{ $expiresAt }}</div>
                    </section>
                @endforeach
            @endforeach
        </main>
    </body>
</html>
