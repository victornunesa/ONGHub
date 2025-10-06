<?php

use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

uses(TestCase::class);

test('CTU07 – quantidade menor ou igual a 0 não é permitida', function () {
    $data = ['quantidade' => 0];
    $rules = ['quantidade' => 'required|numeric|min:1'];

    $validator = Validator::make($data, $rules);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('quantidade'))->toBe('validation.min.numeric');

});

