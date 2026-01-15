create database "fitcoachpro-mvc";

\c "fitcoachpro-mvc" --=> pour connecter a la base de donne

CREATE TABLE users(
    id serial PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role VARCHAR(100) NOT NULL
);

CREATE TABLE client (
    id serial PRIMARY KEY,
    id_user INT NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(10),
    FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE coach (
    id serial PRIMARY KEY,
    id_user INT NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    telephone VARCHAR(10) DEFAULT NULL,
    experience_en_annee INT,
    photo VARCHAR(100),
    bio TEXT,
  	FOREIGN KEY (id_user) REFERENCES users(id)
);

CREATE TABLE certification (
    id serial PRIMARY KEY,
    id_coach INT NOT NULL,
    nom_certif VARCHAR(100),
    annee int check (annee between 1900 and extract(year from current_date)),
    etablissement VARCHAR(100),
    FOREIGN KEY (id_coach) REFERENCES coach(id)
);

CREATE TABLE specialite (
    id serial PRIMARY KEY,
    nom_specialite VARCHAR(100),
    description TEXT DEFAULT NULL
);

CREATE TABLE specialite_coach (
    id_coach INT,
    id_specialite INT,
    PRIMARY KEY (id_coach, id_specialite),
    FOREIGN KEY (id_coach) REFERENCES coach(id),
   	FOREIGN KEY (id_specialite) REFERENCES specialite(id)
);

CREATE TABLE disponibilite (
    id serial PRIMARY KEY,
    id_coach INT ,
    date DATE NOT NULL,
    heure_debut TIME ,
    heure_fin TIME ,
    FOREIGN KEY (id_coach) REFERENCES coach(id)
);


CREATE TABLE reservation (
    id serial PRIMARY KEY,
    id_client INT NOT NULL,
    id_coach INT NOT NULL,
    id_disponibilite INT NOT NULL,
    heure_debut TIME ,
    heure_fin TIME ,
    objectif text DEFAULT Null,
    date DATE NOT NULL,
    status VARCHAR(100) DEFAULT 'en_attente',
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_coach) REFERENCES coach(id),
   	FOREIGN KEY (id_disponibilite) REFERENCES disponibilite(id)
);

CREATE TABLE avis (
    id serial PRIMARY KEY,
    id_client INT NOT NULL,
    id_reservation INT NOT NULL,
    content TEXT,
    note INT CHECK (note BETWEEN 1 AND 5),
    FOREIGN KEY (id_client) REFERENCES client(id),
    FOREIGN KEY (id_reservation) REFERENCES reservation(id)
);



--INSERER LES DONNEE

-- INSERT USERS

INSERT INTO users (email, password, role) VALUES
('client1@gmail.com', 'pass123', 'client'),
('client2@gmail.com', 'pass123', 'client'),
('coach1@gmail.com', 'pass123', 'coach'),
('coach2@gmail.com', 'pass123', 'coach');


-- INSERT CLIENTS
INSERT INTO client (id_user, nom, prenom, telephone) VALUES
(1, 'El Amrani', 'Sara', '0611111111'),
(2, 'Bennani', 'Yassine', '0622222222');


-- INSERT COACHS

INSERT INTO coach (id_user, nom, prenom, telephone, experience_en_annee, photo, bio) VALUES
(3, 'Haddad', 'Karim', '0633333333', 5, 'karim.jpg', 'Coach fitness certifié'),
(4, 'Fassi', 'Omar', '0644444444', 8, 'omar.jpg', 'Coach musculation et cardio');


-- INSERT SPECIALITES

INSERT INTO specialite (nom_specialite, description) VALUES
('Musculation', 'Entraînement de force'),
('Cardio', 'Amélioration de l’endurance'),
('Fitness', 'Remise en forme générale');


-- INSERT SPECIALITE_COACH
INSERT INTO specialite_coach (id_coach, id_specialite) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 2);


-- INSERT CERTIFICATIONS

INSERT INTO certification (id_coach, nom_certif, annee, etablissement) VALUES
(1, 'Diplôme Coach Fitness', 2020, 'Institut Sport Maroc'),
(2, 'Certificat Musculation', 2018, 'Académie Sport Pro');


-- INSERT DISPONIBILITES
INSERT INTO disponibilite (idcoach, date, heure_debut, heure_fin) VALUES
(1, '2025-01-10', '09:00:00', '11:00:00'),
(1, '2025-01-11', '14:00:00', '16:00:00'),
(2, '2025-01-10', '10:00:00', '12:00:00'); 


-- INSERT RESERVATIONS
INSERT INTO reservation (
    id_client, id_coach, id_disponibilite,
    heure_debut, heure_fin, objectif, date, status
) VALUES
(1, 1, 1, '09:00:00', '10:00:00', 'Perte de poids', '2025-01-10', 'en_attente'),
(2, 2, 3, '10:00:00', '11:00:00', 'Prise de masse', '2025-01-10', 'acceptee');


-- INSERT AVIS
INSERT INTO avis (id_client, id_reservation, content, note) VALUES
(2, 2, 'Très bon coach, séance efficace', 5);



-- ajouter column prix
ALTER TABLE coach 
ADD COLUMN prix float;

-- ajouter le champs disponible
ALTER TABLE disponibilite
add COLUMN disponible boolean DEFAULT true

-- ajouter le champs email au client
ALTER TABLE client
add column email varchar(100);



-- GROUP_CONCAT(s.nom_specialite SEPARATOR ', ') => STRING_AGG(s.nom_specialite, ', ')
