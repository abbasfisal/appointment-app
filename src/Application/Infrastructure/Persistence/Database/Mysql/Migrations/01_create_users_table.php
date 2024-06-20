<?php
return "
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
INSERT INTO users ( name, email, password)values ('ali', 'ali@a.b','1234');
INSERT INTO users ( name, email, password)values ('reza', 'reza@a.b','1234');
INSERT INTO users ( name, email, password)values ('mohammad', 'mohammad@a.b','1234');
";
