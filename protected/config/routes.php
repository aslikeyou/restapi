<?
Route::GET('main', function() {
    return "Welcome in my little rest app";
});

Route::GET('users', function() {
    $dbh = App::getI()->database->dbh();
    $stmt = $dbh->prepare("SELECT id, email FROM users");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
});

Route::GET('users/id:\d+', function($id) {
    $dbh = App::getI()->database->dbh();
    $stmt = $dbh->prepare("SELECT id, email FROM users WHERE id = :id");
    $stmt->execute([
        ':id' => $id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
});