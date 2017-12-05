<?php
session_start();

/**
 * A singleton wrapper around PHP's sessions.
 */
class Session {

    private static $instance = null;

    private $user;

    /**
     * Session singleton constructor.
     */
    private function __construct() {
        if ($_SESSION["userid"]) {
            $user = User::dao_getById($_SESSION["userid"]);
        }
    }

    /**
     * Gets the singleton instance of {@link Session}.
     *
     * @return Session the instance
     */
    public static function getInstance(): Session {
        if (Session::$instance == null) {
            Session::$instance = new Session();
        }

        return Session::$instance;
    }

    /**
     * Gets the current signed in user.
     *
     * @return User the signed in user or null if none is signed in
     */
    public function getUser(): User {
        if (empty($this->user)) {
            return null;
        }

        return $this->user;
    }

    /**
     * Sets the current signed in user.
     *
     * @param User $user the user to set
     */
    public function setUser(User $user) {
        $_SESSION["userid"] = $user->getId();
        $this->user = $user;
    }

}