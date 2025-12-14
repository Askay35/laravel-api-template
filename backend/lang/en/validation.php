<?php

return [
    'name' => [
        'required' => 'Name is required',
        'string' => 'Name must be a string',
        'max' => 'Name must not be more than :max characters',
        'min' => 'Name must be at least :min characters',
    ],
    'email' => [
        'required' => 'Email is required',
        'email' => 'Invalid email format',
        'unique' => 'Email is already registered',
    ],
    'password' => [
        'required' => 'Password is required',
        'min' => 'Password must be at least :min characters',
        'confirmed' => 'Passwords do not match',
    ],
];
