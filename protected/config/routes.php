<?

Route::GET('main', function() {

    return [
        'title' => 'Welcome in my simple rest api',
        'routes' => [
            '/users' => [
                'method' => 'GET',
                'auth' => 'required'
            ],
            '/users/id:\d+' => [
                'method' => 'GET',
                'auth' => 'required',
                'params' => [
                    'id' => 'Integer value for pk in database',
                    'example' => 1,
                    'pattern' => '\d+'
                ]
            ]
        ]
    ];
});

Route::GET('users', ['before' => ['Auth', 'check'], function() {
    $dbh = App::getI()->database->dbh();
    $stmt = $dbh->prepare("SELECT id, email FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}]);

Route::GET('users/id:\d+', ['before' => ['Auth', 'check'], function($id) {
    $dbh = App::getI()->database->dbh();
    $stmt = $dbh->prepare("SELECT id, email FROM users WHERE id = :id");
    $stmt->execute([
        ':id' => $id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}]);