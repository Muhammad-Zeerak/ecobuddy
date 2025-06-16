-- ==========================
--  ecoUsertypes
-- ==========================
INSERT INTO ecoUsertypes (usertype, name) VALUES (1, 'Manager');
INSERT INTO ecoUsertypes (usertype, name) VALUES (2, 'User');

-- ==========================
--  ecoUser
-- ==========================

-- echo password_hash("MySuperStr0ngP@ss!Manager", PASSWORD_DEFAULT);
INSERT INTO ecoUser (username, password, userType)
VALUES ('admin', '$2y$10$mS.VNGbdAIaU2xL62PRjOuCezX8Zny2HkjFhEOZzTXnhjuXi1dyyK', 1);

-- echo password_hash("MySuperStr0ngP@ss!User", PASSWORD_DEFAULT);
INSERT INTO ecoUser (username, password, userType)
VALUES ('lee', '$2y$10$2/1NQreml38Z4YyqsplI.uYJwOAXc7.Ly/sg275jYL6H5nwHjOdZ6', 2);

-- ==========================
--  ecoCategories
-- ==========================
INSERT INTO ecoCategories (name) VALUES ('Recycling Bins');
INSERT INTO ecoCategories (name) VALUES ('e-Scooters');
INSERT INTO ecoCategories (name) VALUES ('Bike Share Stations');
INSERT INTO ecoCategories (name) VALUES ('Public EV Charging Stations');
INSERT INTO ecoCategories (name) VALUES ('Battery Recycling Points');
INSERT INTO ecoCategories (name) VALUES ('Community Compost Bins');
INSERT INTO ecoCategories (name) VALUES ('Solar-Powered Benches');
INSERT INTO ecoCategories (name) VALUES ('Green Roofs');
INSERT INTO ecoCategories (name) VALUES ('Public Water Refill Stations');
INSERT INTO ecoCategories (name) VALUES ('Waste Oil Collection Points');
INSERT INTO ecoCategories (name) VALUES ('Book Swap Stations');
INSERT INTO ecoCategories (name) VALUES ('Pollinator Gardens');
INSERT INTO ecoCategories (name) VALUES ('E-Waste Collection Bins');
INSERT INTO ecoCategories (name) VALUES ('Clothing Donation Bins');
INSERT INTO ecoCategories (name) VALUES ('Community Tool Libraries');

-- ==========================
--  ecoFacilities
-- ==========================
INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Salford Uni Scooters', 2, 'E-scooters near Maxwell Hall', '1', 'Crescent', 'Greater Manchester', 'Salford', 'M54WT', -2.3467836, 53.4874723, 1);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('MediaCity Recycling Center', 1, 'Complete recycling station with separate bins for different materials', '12', 'Broadway', 'Greater Manchester', 'Salford', 'M50 2EQ', -2.2967376, 53.4716544, 1);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Piccadilly Gardens EV Charging', 4, 'Four electric vehicle charging stations with rapid chargers', '', 'Piccadilly', 'Greater Manchester', 'Manchester', 'M1 1RG', -2.2374698, 53.4808031, 2);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Peel Park Bike Share', 3, 'Bike sharing dock with 12 bicycles available', '', 'The Crescent', 'Greater Manchester', 'Salford', 'M5 4WT', -2.2713863, 53.4860973, 2);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Central Library Battery Drop', 5, 'Battery recycling collection point in the main entrance', 'St Peters Square', 'Oxford Road', 'Greater Manchester', 'Manchester', 'M2 5PD', -2.2445121, 53.4788306, 1);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Spinningfields Solar Bench', 7, 'Smart bench with solar panels and USB charging ports', '1', 'Hardman Square', 'Greater Manchester', 'Manchester', 'M3 3EB', -2.2532869, 53.4797077, 1);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Arndale Water Refill', 9, 'Free water bottle refill station near the food court', '', 'Market Street', 'Greater Manchester', 'Manchester', 'M4 3AQ', -2.2422668, 53.4834329, 2);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Deansgate Community Garden', 12, 'Urban pollinator garden with native plant species to support local bees', '145', 'Deansgate', 'Greater Manchester', 'Manchester', 'M3 3WE', -2.2484413, 53.4768312, 1);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Trafford Centre Clothing Bank', 14, 'Donation bins for used clothes and textiles', 'The Trafford Centre', 'Trafford Park', 'Greater Manchester', 'Manchester', 'M17 8AA', -2.3471758, 53.4668113, 2);

INSERT INTO ecoFacilities (title, category, description, houseNumber, streetName, county, town, postcode, lng, lat, contributor)
VALUES ('Salford Quays Tool Library', 15, 'Community tool sharing service for DIY and gardening equipment', '23', 'The Quays', 'Greater Manchester', 'Salford', 'M50 3AB', -2.2917259, 53.4725635, 1);

-- ==========================
--  ecoFacilityStatus
-- ==========================
INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (1, 2, 'Not working at the moment, all scooters are gone');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (2, 1, 'Bins are nearly full, needs collection soon');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (3, 2, 'Two charging points out of order, the other two working fine');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (4, 1, 'Only 3 bikes left available for rental');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (5, 2, 'Fully operational, plenty of space for more batteries');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (6, 1, 'USB ports working great, bench in excellent condition');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (7, 2, 'Water pressure is low, but still functional');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (8, 1, 'Garden is flourishing with many butterflies and bees');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (9, 2, 'Donation bin is almost full, needs emptying');

INSERT INTO ecoFacilityStatus (facilityId, userId, statusComment)
VALUES (10, 1, 'All tools available for borrowing, well maintained');