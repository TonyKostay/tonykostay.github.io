<?php


namespace App;
use SQLite3;

class ReviewStore {
    private const OFFSET = 20;
    private SQLite3 $db;
    public function __construct(){
        $this->db = DataBase::getData();
    }

    public function getReviewById(int $reviewId){
        $query = $this->db->prepare("SELECT * FROM reviews           
                                    WHERE review_id = :id");
        $query->bindValue(':id', $reviewId, SQLITE3_INTEGER);
        $result = $query->execute();
        $result = $result->fetchArray(SQLITE3_ASSOC);

        if ($result === false){
            return null;
        }
        return $result;
    }

    public function getAllReviews(int $page): array {
        $limit = self::OFFSET;

        if ($page <= 1) {
            $offset = 0;
        } else {
            $offset = ($page-1)*$limit;
        }

        $count = $this->db->query('SELECT COUNT(*) as cnt FROM reviews')->fetchArray(SQLITE3_ASSOC);

        $count = $count['cnt'];
        $result = $this->db->query("SELECT * FROM reviews
                                    ORDER BY review_id
                                    LIMIT {$limit} OFFSET {$offset} ");

        $row = $result->fetchArray(SQLITE3_ASSOC);

        $rows = array();
        while ($row !== false){
            $rows[]=$row;
            $row = $result->fetchArray(SQLITE3_ASSOC);
        }
        $data = ['count'=> $count, 'rows'=>$rows , 'limit'=>$limit ];

        return $data;
    }

    public function addNewReview(string $userId, string $textReview): array {
        $query = $this->db->prepare("INSERT INTO reviews( review_user, review_text)
                                VALUES(:user, :text)");
        $query->bindValue(':user', $userId, SQLITE3_TEXT);
        $query->bindValue(':text', $textReview, SQLITE3_TEXT);
        $query->execute();

        $query = $this->db->prepare("SELECT * FROM reviews           
                                    WHERE review_user = :user AND review_text = :text");
        $query->bindValue(':user', $userId, SQLITE3_TEXT);
        $query->bindValue(':text', $textReview, SQLITE3_TEXT);
        $resultDB = $query->execute();

        $result = $resultDB->fetchArray(SQLITE3_ASSOC);

        return $result;
    }

    public function deleteReview(int $reviewId): bool{
        $query = $this->db->prepare("SELECT * FROM reviews           
                                    WHERE review_id = :id");
        $query->bindValue(':id', $reviewId, SQLITE3_INTEGER);
        $result = $query->execute();
        if (!$result) {
            return false;
        }

        $query = $this->db->prepare("DELETE FROM reviews WHERE review_id = :id");
        $query->bindValue(':id', $reviewId, SQLITE3_INTEGER);
        $result = $query->execute();
        return $result !== false;
    }
}