<?php
class Article {

    const STATE_SAVED = 0;
    const STATE_AWAITING_REVIEW = 1;
    const STATE_ACCEPTED = 2;
    const STATE_REJECTED = 3;

    const DB_TAB_ARTICLES = "articles";

    private $id, $author, $created, $modified, $state, $title, $abstract, $file;

    /**
     * @return int the article id
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id the article id
     */
    public function setId(int $id) {
        $this->id = $id;
    }

    /**
     * @return User the author of the article
     */
    public function getAuthor(): User {
        return $this->author;
    }

    /**
     * @param User $author the author of the article
     */
    public function setAuthor(User $author) {
        $this->author = $author;
    }

    /**
     * @return DateTime the date and time of article creation
     */
    public function getCreated(): DateTime {
        return $this->created;
    }

    /**
     * @param DateTime $created the date and time of article creation
     */
    public function setCreated(DateTime $created) {
        $this->created = $created;
    }

    /**
     * @return DateTime the date and time of last article modification
     */
    public function getModified(): ?DateTime {
        return $this->modified;
    }

    /**
     * @param DateTime $modified the date and time of last article modification
     */
    public function setModified(?DateTime $modified) {
        $this->modified = $modified;
    }

    /**
     * @return int the state of the article
     */
    public function getState(): int {
        return $this->state;
    }

    /**
     * @param int $state
     */
    public function setState(int $state) {
        $this->state = $state;
    }

    /**
     * @return string the title of the article
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title the title of the article
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @return string the content of the article
     */
    public function getAbstract(): string {
        return $this->abstract;
    }

    /**
     * @param string $abstract the content of the article
     */
    public function setAbstract(string $abstract) {
        $this->abstract = $abstract;
    }

    /**
     * @return string the path to the PDF file
     */
    public function getFile(): string {
        return $this->file;
    }

    /**
     * @param string $file the path to the PDF file
     */
    public function setFile(string $file) {
        $this->file = $file;
    }


    /**
     * Creates the article in the database.
     */
    public function dao_create() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("INSERT INTO `%s` (`author_id`, `created`, `modified`, `state`, `title`, `abstract`, `file`) VALUES (:author_id, :created, :modified, :state, :title, :abstract, :file)",
                $db->table(Article::DB_TAB_ARTICLES)));

        $author_id = $this->getAuthor();
        $created = $this->getCreated()->format(Database::DATE_FORMAT);
        if (empty($this->getModified())) {
            $modified = null;
        } else {
            $modified = $this->getModified()->format(Database::DATE_FORMAT);
        }
        $state = $this->getState();
        $title = $this->getTitle();
        $abstract = $this->getAbstract();
        $file = $this->getFile();

        $stmt->bindParam(":author_id", $author_id, PDO::PARAM_INT);
        $stmt->bindParam(":created", $created, PDO::PARAM_STR);
        $stmt->bindParam(":modified", $modified, PDO::PARAM_STR);
        $stmt->bindParam(":state", $state, PDO::PARAM_INT);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":abstract", $abstract, PDO::PARAM_STR);
        $stmt->bindParam(":file", $file, PDO::PARAM_STR);

        $stmt->execute();

        $this->setId($db->getPdo()->lastInsertId());
    }

    /**
     * Updates the article in the database.
     */
    public function dao_update() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("UPDATE `%s` SET `author_id`=:author_id, `created`=:created, `modified`=:modified, `state`=:state, `title`=:title, `abstract`=:abstract, `file`=:file WHERE `id`=:id",
                $db->table(Article::DB_TAB_ARTICLES)));

        $id = $this->getId();
        $author_id = $this->getAuthor();
        $created = $this->getCreated()->format(Database::DATE_FORMAT);
        if (empty($this->getModified())) {
            $modified = null;
        } else {
            $modified = $this->getModified()->format(Database::DATE_FORMAT);
        }
        $state = $this->getState();
        $title = $this->getTitle();
        $abstract = $this->getAbstract();
        $file = $this->getFile();

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":author_id", $author_id, PDO::PARAM_INT);
        $stmt->bindParam(":created", $created, PDO::PARAM_STR);
        $stmt->bindParam(":modified", $modified, PDO::PARAM_STR);
        $stmt->bindParam(":state", $state, PDO::PARAM_INT);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":abstract", $abstract, PDO::PARAM_STR);
        $stmt->bindParam(":file", $file, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Gets an article by ID.
     *
     * @param int $id the article ID
     * @return null|Article
     */
    public static function dao_getById(int $id): ?Article {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id", $db->table(Article::DB_TAB_ARTICLES)));

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToArticle($result);
    }

    /**
     * Gets the articles ordered by the date of creation.
     *
     * @param int $n how many
     * @param int $offset the offset
     * @return array
     */
    public static function dao_getNewest(int $n, int $offset): array {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` ORDER BY `created` DESC LIMIT :n OFFSET :offset",
                $db->table(Article::DB_TAB_ARTICLES)));

        $stmt->bindParam(":n", $n, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();


        $articles = array();

        while (!empty($result = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($articles, self::dao_dataToArticle($result));
        }

        return $articles;
    }

    /**
     * Converts a data array fetched associatively from the database to an Article.
     *
     * @param $data the data array
     * @return null|Article
     */
    private static function dao_dataToArticle($data) : ?Article {
        if (empty($data) || $data == false) {
            return null;
        }

        $article = new Article();
        $article->setId($data["id"]);
        $article->setAuthor(User::get($data["author"]));
        $article->setCreated(DateTime::createFromFormat(Database::DATE_FORMAT, $data["created"]));
        if (empty($data["modified"])) {
            $article->setModified(null);
        } else {
            $article->setModified(DateTime::createFromFormat(Database::DATE_FORMAT, $data["modified"]));
        }
        $article->setState($data["state"]);
        $article->setTitle($data["title"]);
        $article->setAbstract($data["abstract"]);
        $article->setFile($data["file"]);

        return $article;
    }
}
