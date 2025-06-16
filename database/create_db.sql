-- ==========================
--  ecoUsertypes
-- ==========================
CREATE TABLE IF NOT EXISTS ecousertypes
( id integer PRIMARY KEY autoincrement, usertype int, name varchar(50) NOT NULL UNIQUE );

-- ==========================
--  ecoUser
-- ==========================
CREATE TABLE IF NOT EXISTS ecouser
( id integer PRIMARY KEY autoincrement, username varchar(50) NOT NULL UNIQUE, password varchar(255) NOT NULL, usertype int NOT NULL, FOREIGN KEY (usertype) REFERENCES ecousertypes(id) );

-- ==========================
--  ecoCategories
-- ==========================
CREATE TABLE IF NOT EXISTS ecocategories
( id integer PRIMARY KEY autoincrement, name varchar(50) NOT NULL UNIQUE );

-- ==========================
--  ecoFacilities
-- ==========================
CREATE TABLE IF NOT EXISTS ecofacilities
( id integer PRIMARY KEY autoincrement, title varchar(50) NOT NULL, category int, description varchar(150) NOT NULL, housenumber varchar(50), streetname varchar(50), county varchar(50), town varchar(50), postcode varchar(7) NOT NULL, lng float NOT NULL, lat float NOT NULL, photo varchar(255), contributor int, FOREIGN KEY (category) REFERENCES ecocategories(id), FOREIGN KEY (contributor) REFERENCES ecouser(id) );

-- ==========================
--  ecoFacilityStatus
-- ==========================
CREATE TABLE IF NOT EXISTS ecofacilitystatus
( id integer PRIMARY KEY autoincrement, facilityid int NOT NULL, userId INT NOT NULL, statuscomment varchar(100) NOT NULL, FOREIGN KEY (facilityid) REFERENCES ecofacilities(id), FOREIGN KEY (userId) REFERENCES ecoUser(id) );