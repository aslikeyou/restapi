<?php

class Auth {
    public static function check() {
        // auth
        $email  = Route::getHeader('X_EMAIL');
        $contentHash = Route::getHeader('X_HASH');
        $content     = Route::getHeader('X_CONTENT');

        $dbh = App::getI()->database->dbh();
        $stmt = $dbh->prepare("SELECT email, private FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data === false) {
            Rest::sendResponse('Unauthorized', 401);
        }

        return hash_hmac('sha256', $content, $data['private']) === $contentHash;
    }
}