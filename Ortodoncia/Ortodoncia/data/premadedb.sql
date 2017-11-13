CREATE TABLE Comments (
    username VARCHAR(50) NOT NULL,
	comments VARCHAR(50) NOT NULL,
	commentNumber VARCHAR(50) NOT NULL PRIMARY KEY
	
);

INSERT INTO Comments(username, comments, commentNumber)
VALUES  ('lpotes', 'Test Comment', 1),
		('Joey', 'how you doing?', 2),
		('lpotes', 'Remember your Appointment', 3);
		
CREATE TABLE Users (
	fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    username VARCHAR(50) NOT NULL PRIMARY KEY,
	email VARCHAR(50) NOT NULL,
    passwrd VARCHAR(50) NOT NULL,
	doctor VARCHAR(50) NOT NULL,
	gender VARCHAR(50) NOT NULL,
	utipo VARCHAR(50) NOT NULL
    
);

INSERT INTO Users(fName, lName, username, email, passwrd, doctor, gender, utipo)
VALUES  ('Luis', 'Potes', 'lpotes', 'potesluis@gmail.com', 'potes','House', 'M', 'Paciente'),
('Drake', 'Ramorey', 'joey', 'joey@gmail.com', 'joey','', 'M', 'Doctor');