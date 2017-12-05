<?php
/**
 * User data class.
 */
class User {

    const DB_TAB_USERS = "users";

    private $id, $name, $passwordhash, $email, $group;

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
    }

    public function dao_update() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("UPDATE `%s` SET `name`=:name, `passwordhash`=:passwordhash, `email`=:email, `group`=:group WHERE `id`=:id",
                $db->table(User::DB_TAB_USERS)));

        $name = $this->getName();
        $passwordhash = $this->getPasswordhash();
        $email = $this->getEmail();
        $groupid = $this->getGroup()->getId();

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":name", $name, PDO::PARAM_STR);
        $stmt->bindParam(":passwordhash", $passwordhash, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":group", $groupid, PDO::PARAM_INT);

        $stmt->execute();
    }

    public static function dao_getById(int $id): User {
        $db = Database::getInstance();
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id", $db->table(User::DB_TAB_USERS)));

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($result) || $result == false) {
            return null;
        }

        $user = new User();
        $user->setId($id);
        $user->setName($result["name"]);
        $user->setPasswordhash($result["passwordhash"]);
        $user->setEmail($result["email"]);
        $user->setGroup(UserGroup::get($result["group"]));
        return $user;
    }

}