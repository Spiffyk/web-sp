<?php
class Review {

    const DB_TAB_REVIEW = "review";

    private $id, $article, $author, $proposal, $created, $content;



    public function getId(): int{
        return $this->id;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getArticle(): Article {
        return $this->article;
    }

    public function setArticle(Article $article) {
        $this->article = $article;
    }

    public function getAuthor(): User {
        return $this->author;
    }

    public function setAuthor(User $author) {
        $this->author = $author;
    }

    public function getProposal(): int {
        return $this->proposal;
    }

    public function setProposal(int $proposal) {
        $this->proposal = $proposal;
    }

    public function getCreated(): DateTime {
        return $this->created;
    }

    public function setCreated(DateTime $created) {
        $this->created = $created;
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content) {
        $this->content = $content;
    }


    /**
     * Creates the review in the database.
     */
    public function dao_create() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("INSERT INTO `%s` (`article`, `author`, `proposal`, `created`, `content`) VALUES (:article, :author, :proposal, :created, :content)",
                $db->table(self::DB_TAB_REVIEW)));

        $article = $this->getArticle()->getId();
        $author = $this->getAuthor()->getId();
        $proposal = $this->getProposal();
        $created = $this->getCreated()->format(Database::DATE_FORMAT);
        $content = $this->getContent();

        $stmt->bindParam(":article", $article, PDO::PARAM_INT);
        $stmt->bindParam(":author", $author, PDO::PARAM_INT);
        $stmt->bindParam(":proposal", $proposal, PDO::PARAM_INT);
        $stmt->bindParam(":created", $created, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);

        $stmt->execute();

        $this->setId($db->getPdo()->lastInsertId());
    }

    /**
     * Updates the review in the database.
     */
    public function dao_update() {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("UPDATE `%s` SET `article`=:article, `author`=:author, `proposal`=:proposal, `created`=:created, `content`=:content WHERE `id`=:id",
                $db->table(self::DB_TAB_REVIEW)));

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->bindParam(":article", $article, PDO::PARAM_INT);
        $stmt->bindParam(":author", $author, PDO::PARAM_INT);
        $stmt->bindParam(":proposal", $proposal, PDO::PARAM_INT);
        $stmt->bindParam(":created", $created, PDO::PARAM_STR);
        $stmt->bindParam(":content", $content, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * Gets a review by ID.
     *
     * @param int $id the review ID
     * @return null|Review
     */
    public static function dao_getById(int $id): ?Review {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `id`=:id", $db->table(self::DB_TAB_REVIEW)));

        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return self::dao_dataToReview($result);
    }

    /**
     * Gets an array of reviews by the given article id.
     *
     * @param int $artid
     * @return array
     */
    public static function dao_getForArticleId(int $artid): array {
        return self::dao_getForArticle(Article::dao_getById($artid));
    }

    /**
     * Gets an array of reviews by the given article.
     *
     * @param Article $article
     * @return array
     */
    public static function dao_getForArticle(Article $article): array {
        $db = Database::getInstance();
        $stmt = $db
            ->getPdo()
            ->prepare(sprintf("SELECT * FROM `%s` WHERE `article`=:article ORDER BY `created` DESC", $db->table(self::DB_TAB_REVIEW)));

        $artid = $article->getId();

        $stmt->bindParam(":article", $artid, PDO::PARAM_INT);
        $stmt->execute();

        $reviews = array();

        while (!empty($result = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($reviews, self::dao_dataToReview($result, $article));
        }

        return $reviews;
    }

    /**
     * Converts a data array fetched associatively from the database to a Review.
     *
     * @param $data the data array
     * @param Article|null $article the article object to assign
     * @return null|Review
     */
    private static function dao_dataToReview($data, ?Article $article=null): ?Review {
        if (empty($data) || $data == false) {
            return null;
        }

        $review = new Review();
        $review->setId($data["id"]);
        $review->setAuthor(User::get($data["author"]));
        if ($article == null) {
            $review->setArticle(Article::dao_getById($data["article"]));
        } else {
            $review->setArticle($article);
        }
        $review->setProposal($data["proposal"]);
        $review->setCreated(DateTime::createFromFormat(Database::DATE_FORMAT, $data["created"]));
        $review->setContent($data["content"]);

        return $review;
    }
}
