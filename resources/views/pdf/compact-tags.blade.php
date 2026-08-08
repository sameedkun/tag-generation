<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} compact tags</title>
    <link rel="stylesheet" href="{{ public_path('css/compact-tags.css') }}">
</head>
<body>
    <main class="tag-sheet">
        @for ($row = 0; $row < 13; $row++)
            @for ($column = 0; $column < 4; $column++)
                <section class="tag tag-row-{{ $row }} tag-column-{{ $column }}">
                    <p class="tag-title">{{ $title }}</p>
                    <p class="tag-address">{{ $address }}</p>
                    <p class="tag-date-line tag-manufactured">
                        <span>Mfg Date</span>
                        <span>{{ $manufacturedAt }}</span>
                    </p>
                    <p class="tag-date-line tag-expiry">
                        <span>Exp Date.</span>
                        <span>{{ $expiresAt }}</span>
                    </p>
                </section>
            @endfor
        @endfor
    </main>
</body>
</html>
