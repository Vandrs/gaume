-- Create Table --

CREATE TABLE regions (
  id 	INT 		NOT NULL AUTO_INCREMENT,
  name 	VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

-- Insert Data --

Insert into regions (name) 
	         values ('Norte'),
					('Nordeste'),
                    ('Sudeste'),
					('Sul'),
					('Centro-Oeste');
