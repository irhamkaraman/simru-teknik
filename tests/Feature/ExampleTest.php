<?php

test('the application returns a successful response', function () {
    $response = $this->get('/rooms');

    $response->assertStatus(200);
});
