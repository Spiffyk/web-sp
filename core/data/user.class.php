<?php
/**
 * User data class.
 */
class User {

    const DB_TAB_USERS = "users";

    private $id, $name, $passwordhash, $email, $group;

    private static $usersById = array();

    public static function get(int $id): ?User {
        if (empty(User::$usersById[$id])) {
            User::$usersById[$id] = User::dao_getById($id);
        }

        return User::$usersById[$id];
    }

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getPasswordhash(): string {
        return $this->passwordhash;
    }

    public function setPasswordhash(string $passwordhash) {
        $this->passwordhash = $passwordhash;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getGroup(): UserGroup {
        return $this->group;
    }

    public function setGroup(UserGroup $group) {
        $this->group = $group;
    }


    /**
     * Creates the user in the database.
     */
    public function dao_create() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("INSERT INTO `%s` (`name`, `passwordhash`, `email`, `group`) VALUES (:name, :passwordhash, :email, :group)",
                $db->table(User::DB_TAB_USERS)));

        $name = $this->getName();
        $passwordhash = $this->getPasswordhash();
        $email = $this->getEmail();
        $groupid = $this->getGroup()->getId();

        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":passwordhash", $passwordhash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":group", $groupid, PDO::PARAM_INT);

        $stmt->execute();

        $this->setId($db->getPdo()->lastInsertId());
        User::$usersById[$this->getId()] = $this;
    }

    /**
     * Updates the user in the database.
     */
    public function dao_update() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("UPDATE `%s` SET `name`=:name, `passwordhash`=:passwordhash, `email`=:email, `group`=:groupid WHERE `id`=:id",
                $db->table(self::DB_TAB_USERS)));

        $id = $this->getId();
        $name = $this->getName();
        $passwordhash = $this->getPasswordhash();
        $email = $this->getEmail();
        $groupid = $this->getGroup()->getId();

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":passwordhash", $passwordhash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":groupid", $groupid, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Gets a user by ID.
     *
     * @param int $id the user ID
     * @return null|User
     */
    public static function dao_getById(int $id): ?User {
        $db = Database::getInstance();
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id", $db->table(User::DB_TAB_USERS)));
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToUser($result);
    }

    /**
     * Gets a user by name.
     *
     * @param string $name the user name
     * @return null|User
     */
    public static function dao_getByName(string $name): ?User {
        $db = Database::getInstance();
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `name`=:name", $db->table(User::DB_TAB_USERS)));
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToUser($result);
    }

    /**
     * Gets the user with the specified e-mail.
     *
     * @param string $email
     * @return null|User
     */
    public static function dao_getUserByEmail(string $email): ?User {
        $db = Database::getInstance();
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `email`=:email",
                $db->table(User::DB_TAB_USERS)));
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToUser($result);
    }

    /**
     * Checks whether the specified e-mail is available.
     *
     * @param string $email
     * @return bool
     */
    public static function dao_isEmailAvailable(string $email): bool {
        $db = Database::getInstance();
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT COUNT(*) as count FROM `%s` WHERE `email`=:email",
                $db->table(User::DB_TAB_USERS)));
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result["count"] == 0);
    }

    /**
     * Converts a data array associatively fetched from the database to a User.
     *
     * @param $data - the data array
     * @return null|User
     */
    private static function dao_dataToUser($data): ?User {
        if (empty($data) || $data == false) {
            return null;
        }

        $user = new User();
        $user->setId($data["id"]);
        $user->setName($data["name"]);
        $user->setPasswordhash($data["passwordhash"]);
        $user->setEmail($data["email"]);
        $user->setGroup(UserGroup::get($data["group"]));
        return $user;
    }

}