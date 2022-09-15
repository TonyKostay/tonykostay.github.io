CREATE TABLE reviews(
    review_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT ,
    review_userId TEXT NOT NULL,
    review_text TEXT NOT NULL
);