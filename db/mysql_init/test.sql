DROP TABLE IF EXISTS test;

CREATE TABLE test (
    id   int(11)       NOT NULL AUTO_INCREMENT,
    msg  varchar(255)  NOT NULL,
    PRIMARY KEY (id)
); 

INSERT INTO test (msg) values ('add fist message.');
INSERT INTO test (msg) values ('add second message.');
INSERT INTO test (msg) values ('add third message.');