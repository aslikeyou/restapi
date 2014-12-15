<?

print_r([
    'email' => 'pekhota.alex@gmail.com',
    'private' => hash('sha256', openssl_random_pseudo_bytes(32))
]);