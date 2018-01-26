<?php
/**
 * User approval data class.
 */
class UserApproval {

    const DB_TAB_USER_APPROVAL = "user_approval";

    const STATE_OPEN = 0;
    const STATE_ACCEPTED = 1;
    const STATE_REJECTED = 2;

    private $id, $user, $opened, $closed, $state;

    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function getOpened(): DateTime {
        return $this->opened;
    }

    public function setOpened(DateTime $opened) {
        $this->opened = $opened;
    }

    public function getClosed(): ?DateTime {
        return $this->closed;
    }

    public function setClosed(?DateTime $closed) {
        $this->closed = $closed;
    }

    public function getState(): int {
        return $this->state;
    }

    public function setState(int $state) {
        $this->state = $state;
    }


    /**
     * Creates the approval in database.
     */
    public function dao_create() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("INSERT INTO `%s` (`user_id`, `opened`, `closed`, `state`) VALUES (:user_id, :opened, :closed, :state)",
                $db->table(UserApproval::DB_TAB_USER_APPROVAL)));

        $user_id = $this->getUser()->getId();
        $opened = $this->getOpened()->format(Database::DATE_FORMAT);
        if (empty($this->getClosed())) {
            $closed = null;
        } else {
            $closed = $this->getClosed();
        }
        $state = $this->getState();

        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam(":opened", $opened, PDO::PARAM_STR);
        $stmt->bindParam(":closed", $closed, PDO::PARAM_STR);
        $stmt->bindParam(":state", $state, PDO::PARAM_INT);

        $stmt->execute();

        $this->setId($db->getPdo()->lastInsertId());
    }

    /**
     * Updates the approval in database.
     */
    public function dao_update() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("UPDATE `%s` SET `user_id`=:user_id, `opened`=:opened, `closed`=:closed, `state`=:state WHERE `id`=:id",
                $db->table(UserApproval::DB_TAB_USER_APPROVAL)));

        $id = $this->getId();
        $user_id = $this->getUser()->getId();
        $opened = $this->getOpened()->format(Database::DATE_FORMAT);
        if (empty($this->getClosed())) {
            $closed = null;
        } else {
            $closed = $this->getClosed();
        }
        $state = $this->getState();

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
        $stmt->bindParam(":opened", $opened, PDO::PARAM_STR);
        $stmt->bindParam(":closed", $closed, PDO::PARAM_STR);
        $stmt->bindParam(":state", $state, PDO::PARAM_INT);

        $stmt->execute();
    }

    /**
     * Gets an approval by its id.
     *
     * @return null|UserApproval
     */
    public static function dao_getById(): ?UserApproval {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id", $db->table(UserApproval::DB_TAB_USER_APPROVAL)));

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToUserApproval($result);
    }

    /**
     * Gets a list of open approvals, ordered by the date of opening.
     *
     * @param int $n how many
     * @param int $offset offset
     * @return array
     */
    public static function dao_getOpen(int $n, int $offset): array {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `closed` IS NULL ORDER BY `opened` LIMIT :n OFFSET :offset"));

        $stmt->bindParam(":n", $n, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        $approvals = array();

        while(!empty($result = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($approvals, self::dao_dataToUserApproval($result));
        }

        return $approvals;
    }

    /**
     * Gets a list of closed approvals, ordered by the date of closure.
     *
     * @param int $n how many
     * @param int $offset offset
     * @return array
     */
    public static function dao_getClosed(int $n, int $offset): array {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `closed` IS NOT NULL ORDER BY `closed` LIMIT :n OFFSET :offset"));

        $stmt->bindParam(":n", $n, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();

        $approvals = array();

        while(!empty($result = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($approvals, self::dao_dataToUserApproval($result));
        }

        return $approvals;
    }

    /**
     * Converts an array of data fetched associatively from the database to UserApproval.
     *
     * @param $data - the fetched data
     * @return null|UserApproval
     */
    private static function dao_dataToUserApproval($data): ?UserApproval {
        if (empty($data) || $data == false) {
            return null;
        }

        $userApproval = new UserApproval();
        $userApproval->setId($data["id"]);
        $userApproval->setUser(User::get($data["user_id"]));
        $userApproval->setOpened(DateTime::createFromFormat(Database::DATE_FORMAT, $data["opened"]));
        if (!empty($data["closed"])) {
            $userApproval->setClosed(DateTime::createFromFormat(Database::DATE_FORMAT, $data["closed"]));
        }
        $userApproval->setState($data["state"]);

        return $userApproval;
    }
}
