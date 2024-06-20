<?php
return "
CREATE TABLE time_slots (
    id INT AUTO_INCREMENT PRIMARY KEY,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL
);
INSERT INTO time_slots (start_time, end_time) VALUES ('08:00', '08:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('09:00', '09:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('10:00', '10:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('11:00', '11:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('12:00', '12:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('13:00', '13:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('14:00', '14:30');
INSERT INTO time_slots (start_time, end_time) VALUES ('15:00', '15:30');
";
