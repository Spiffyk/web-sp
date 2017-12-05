<?php
/**
 * User group data class.
 */
class UserGroup {

    const DB_TABLE_GROUPS = "usergroups";
    const DB_TABLE_PERMISSIONS = "permissions";

    private static $groupsById = array();

    private $id, $name;
    private $permissions = array();



    public static function get(int $id): UserGroup {
        if (empty(UserGroup::$groupsById[$id])) {
            UserGroup::$groupsById[$id] = UserGroup::dao_loadById($id);
        }

        return UserGroup::$groupsById[$id];
    }

    public static function put(UserGroup $group) {
        UserGroup::$groupsById[$group->getId()] = $group;
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

    public function hasPermission(string $permission): bool {
        return !empty($this->permissions[$permission]);
    }

    public function addPermission(string $permission) {
        $this->permissions[$permission] = 1;
    }

    public function removePermission(string $permission) {
        unset($this->permissions[$permission]);
    }



    public function dao_create() {
        $db = Database::getInstance();
        $db->getPdo()->beginTransaction();
        {
            // Create group in DB
            $stmt = $db->getPdo()
                ->prepare(sprintf("INSERT INTO `%s` (`name`) VALUES (:name)",
                    $db->table(UserGroup::DB_TABLE_GROUPS)));

            $stmt->bindParam("name", $name, PDO::PARAM_STR);

            $name = $this->getName();

            $stmt->execute();
            $this->setId($db->getPdo()->lastInsertId());

            // Create permissions in DB
            $stmt = $db->getPdo()
                ->prepare(sprintf("INSERT INTO `%s` (`group`, `permission`) VALUES (:group, :permission)",
                    $db->table(UserGroup::DB_TABLE_PERMISSIONS)));

            $stmt->bindParam("group", $group, PDO::PARAM_INT);
            $stmt->bindParam("permission", $permission, PDO::PARAM_STR);

            $group = $this->getId();
            foreach ($this->permissions as $permission => $val) {
                $stmt->execute();
            }
        }
        $db->getPdo()->commit();
    }

    public function dao_update() {
        $db = Database::getInstance();
        $db->getPdo()->beginTransaction();
        {
            // Save group in DB
            $stmt = $db->getPdo()
                ->prepare(sprintf("UPDATE `%s` SET `name`=:name WHERE `id`=:id",
                    $db->table(UserGroup::DB_TABLE_GROUPS)));

            $stmt->bindParam("name", $name, PDO::PARAM_STR);
            $stmt->bindParam("id", $id, PDO::PARAM_INT);

            $name = $this->getName();
            $id = $this->getId();

            $stmt->execute();

            // Clear permissions in DB
            $stmt = $db->getPdo()
                ->prepare(sprintf("DELETE FROM `%s` WHERE `group`=:group"));

            $stmt->bindParam("group", $group, PDO::PARAM_INT);

            $group = $this->getId();

            $stmt->execute();

            // Create permissions in DB
            $stmt = $db->getPdo()
                ->prepare(sprintf("INSERT INTO `%s` (`group`, `permission`) VALUES (:group, :permission)",
                    $db->table(UserGroup::DB_TABLE_PERMISSIONS)));

            $stmt->bindParam("group", $group, PDO::PARAM_INT);
            $stmt->bindParam("permission", $permission, PDO::PARAM_STR);

            $group = $this->getId();
            foreach ($this->permissions as $permission => $val) {
                $stmt->execute();
            }
        }
        $db->getPdo()->commit();
    }

    private static function dao_loadById(int $id): UserGroup {
        $db = Database::getInstance();

        // Load group from DB
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id",
                $db->table(UserGroup::DB_TABLE_GROUPS)));

        $stmt->bindParam("id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($result) || $result == false) {
            return null;
        }

        $usergroup = new UserGroup();
        $usergroup->setId($id);
        $usergroup->setName($result["name"]);

        // Load permissions from DB
        $stmt = $db->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `group`=:id",
                $db->table(UserGroup::DB_TABLE_PERMISSIONS)));

        $stmt->bindParam("id", $id, PDO::PARAM_INT);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $perm) {
            $usergroup->addPermission($perm["permission"]);
        }

        return $usergroup;
    }

}
