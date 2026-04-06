<?php

return [
    'name' => ['required' => 'Nomo estas deviga'],
    'email' => [
        'required' => 'Retadreso estas deviga',
        'email' => 'Bonvolu provizi validan retadreson',
    ],
    'message' => [
        'required' => 'Mesaĝo estas deviga',
        'min' => 'Mesaĝo devas havi almenaŭ :min signojn',
    ],
    // ... add other keys as needed
];
