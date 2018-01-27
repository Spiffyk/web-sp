<?php
/**
 * A singleton wrapper around PHP's sessions.
 */
class Session {

    /**
     * Returned by {@link login} on success.
     */
    public const LOGIN_SUCCESS = 0;

    /**
     * Returned by {@link login} when username or password is wrong.
     */
    public const LOGIN_WRONG_CREDENTIALS = 1;

    private static $instance = null;

    private $user, $group;

    /**
     * Session singleton constructor.
     */
    private function __construct() {
        session_start();

        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION["userid"])) {
            $this->user = User::dao_getById($_SESSION["userid"]);
            $this->group = $this->user->getGroup();
        } else {
            $this->group = UserGroup::get(UserGroup::GROUP_ANONYMOUS);
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
     * @return User the signed in user or <code>null</code> if none is signed in
     */
    public function getUser(): ?User {
        if (empty($this->user)) {
            return null;
        }

        return $this->user;
    }

    /**
     * Gets the group of the current user.
     *
     * @return UserGroup the group of the signed in user or {@link UserGroup::GROUP_ANONYMOUS} if no user is signed in
     */
    public function getGroup(): UserGroup {
        return $this->group;
    }

    /**
     * Starts a session with the specified user.
     *
     * @param User $user the user to set
     */
    private function start(User $user) {
        session_start();
        $_SESSION["userid"] = $user->getId();
        $this->user = $user;
        $this->group = $user->getGroup();
    }

    /**
     * Attempts a login with the specified credentials.
     *
     * @param string $name the user name
     * @param string $password the password
     * @return int {@link Session::LOGIN_SUCCESS} if successful, {@link Session::LOGIN_WRONG_CREDENTIALS} if username
     * or password is wrong
     */
    public function login(string $name, string $password): int {
        $user = User::dao_getByName($name);
        if ($user == null) {
            password_verify("", ""); // to ensure that time checks out
            return Session::LOGIN_WRONG_CREDENTIALS;
        }

        if (password_verify($password, $user->getPasswordhash())) {
            $this->start($user);
            return Session::LOGIN_SUCCESS;
        } else {
            return Session::LOGIN_WRONG_CREDENTIALS;
        }
    }

    /**
     * Destroys the current session.
     */
    public function logout() {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_destroy();
            $this->user = null;
            $this->group = UserGroup::get(UserGroup::GROUP_ANONYMOUS);
        }
    }

    /**
     * Checks whether a user is logged in.
     *
     * @return bool <code>true</code> if a user is logged in, otherwise <code>false</code>
     */
    public function isActive(): bool {
        return $this->getUser() != null;
    }

}
