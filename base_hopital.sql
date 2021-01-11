-- Auteurs : Aurelien Vallet / Tasnim Ouheibi
-- Date : Janvier 2021

-- Script SQL permettant de creer la base de donnee pour l'hopital
-- celle-ci contient la liste des patients, des pays, motifs et sexe

-- Creation Database                                           
CREATE DATABASE IF NOT EXISTS hopital_php;
USE hopital_php;


DROP TABLE IF EXISTS Patients;
DROP TABLE IF EXISTS Pays;
DROP TABLE IF EXISTS Motifs;
DROP TABLE IF EXISTS Sexe;

-- TABLE "Pays"

CREATE TABLE Pays(
    Code VARCHAR(10) NOT NULL,
    Libelle VARCHAR(30),
    CONSTRAINT pk_pays PRIMARY KEY(Code)
);

INSERT INTO Pays VALUES('FR','France');
INSERT INTO Pays VALUES('BE','Belgique');
INSERT INTO Pays VALUES('MA','Maroc');
INSERT INTO Pays VALUES('TN','Tunisie');
INSERT INTO Pays VALUES('DZ','Algerie');


-- table Motifs


CREATE TABLE Motifs (
    Code INT NOT NULL ,
    libellé VARCHAR(30),
    PRIMARY KEY (Code)
);
INSERT INTO Motifs VALUES(1,'Consultation libre');
INSERT INTO Motifs VALUES(2,'URGENCE');
INSERT INTO Motifs VALUES(3,'Prescription');



-- Table sexe
CREATE TABLE Sexe (
    Code ENUM('M','F'),
    libellé VARCHAR(30),
    PRIMARY KEY(Code)
);
INSERT INTO Sexe VALUES(1,'Feminin');
INSERT INTO Sexe VALUES(2,'Masculin');



-- Table Patients

CREATE TABLE Patients (
    Code INT NOT NULL,
    Nom VARCHAR(30),
    Prenom VARCHAR(30),
    Sexe ENUM('M','F'),
    DataNaiss VARCHAR(30),
    NumeroSecSoc VARCHAR(30),
    CodePays VARCHAR(5),
    DatePremEntree VARCHAR(30),
    CodeMotif INT,
    CONSTRAINT pk_patients PRIMARY KEY(Code)
);

ALTER TABLE Patients ADD FOREIGN KEY (CodePays) REFERENCES Pays(Code);
ALTER TABLE Patients ADD FOREIGN KEY (CodeMotif) REFERENCES Motifs(Code);
ALTER TABLE Patients ADD FOREIGN KEY (Sexe) REFERENCES Sexe(Code);

INSERT INTO Patients VALUES(1,'MAALOUL','Ali','M','12/01/1979','','TN','01/02/2018',1);
INSERT INTO Patients VALUES(2,'DUPONT','Veronique','F','27/12/1938','238277502900442', 'FR','05/04/2018',2);
INSERT INTO Patients VALUES(3,'DUPONT','Jean','M','01/04/1985','185045903800855','FR','12/06/2018',3);
INSERT INTO Patients VALUES(4,'EL GUERROUJ','Hicham','M','10/06/1980','','MA','18/08/2018',1);
INSERT INTO Patients VALUES(5,'BELMADI','Djamel','M','27/12/1982','','DZ','26/09/2018',1);