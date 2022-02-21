<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Http\Response;

use function Pest\Laravel\get;

it('reciveis a HTTP OK on the home page', function () {
    get(
        uri: route(name:'home')
    )->assertStatus(
        status: Response::HTTP_OK
    );
});
