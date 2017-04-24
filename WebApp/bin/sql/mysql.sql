CREATE TABLE films (
	     code MEDIUMINT NOT NULL AUTO_INCREMENT,
	     title CHAR(30) NOT NULL,
	     PRIMARY KEY (code)
);

INSERT INTO films (title) VALUES
    ('Suicide Squad'),('Sausage Party'),('Morgan'),
    ('War Dog'),('Bad Moms'),('Yoga Hosers');
