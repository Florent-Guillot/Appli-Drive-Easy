-- ============================================================
-- DriveEasy — Script SQL complet
-- Base de données : driveeasy
-- Encodage : utf8mb4_general_ci
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Table : vehicule
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vehicule` (
  `id_vehicule`   INT(11)       NOT NULL AUTO_INCREMENT,
  `marque`        VARCHAR(100)  NOT NULL,
  `modele`        VARCHAR(100)  NOT NULL,
  `type`          VARCHAR(50)   NOT NULL,
  `capacite`      INT(11)       NOT NULL,
  `prix_jour`     DECIMAL(6,2)  NOT NULL,
  `boite`         TINYINT(1)    NOT NULL COMMENT '0 = manuelle, 1 = automatique',
  `disponibilite` VARCHAR(50)   NOT NULL,
  `description`   TEXT,
  `image`         VARCHAR(255),
  PRIMARY KEY (`id_vehicule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table : reservation
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `reservation` (
  `id_reservation` INT(11)       NOT NULL AUTO_INCREMENT,
  `nom`            VARCHAR(100)  NOT NULL,
  `prenom`         VARCHAR(100)  NOT NULL,
  `telephone`      VARCHAR(20)   NOT NULL,
  `date_debut`     DATE          NOT NULL,
  `date_fin`       DATE          NOT NULL,
  `montant_ttc`    FLOAT         NOT NULL,
  `age_client`     INT(11)       NOT NULL,
  `id_vehicule`    INT(11)       NOT NULL,
  PRIMARY KEY (`id_reservation`),
  KEY `id_vehicule` (`id_vehicule`),
  CONSTRAINT `reservation_ibfk_1`
    FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule` (`id_vehicule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Données de test — 8 véhicules de collection
-- ------------------------------------------------------------
INSERT INTO `vehicule` (`marque`, `modele`, `type`, `capacite`, `prix_jour`, `boite`, `disponibilite`, `description`, `image`) VALUES
('Ferrari',        '308 GTS',          'Cabriolet', 2, 450.00, 0, 'Disponible',     'Icône des années 80, la Ferrari 308 GTS est un cabriolet mid-engine à moteur V8 de 2,9L. Propulsée à 255 ch, elle incarne le mythe du cheval cabré dans toute sa splendeur italienne.', 'ferrari_308.jpg'),
('Jaguar',         'E-Type V12',        'Cabriolet', 2, 380.00, 0, 'Disponible',     'Surnommée la plus belle voiture du monde par Enzo Ferrari lui-même, la Jaguar E-Type est un chef-d\'œuvre de design et de performance britannique.', 'jaguar_etype.jpg'),
('Porsche',        '911 Carrera 3.0',   'Sportive',  2, 320.00, 0, 'Disponible',     'La Porsche 911 Carrera de 1977, avec son fameux flat-six 3.0L atmosphérique, est l\'expression ultime du plaisir de conduire à l\'allemande.', 'porsche_911.jpg'),
('Alfa Romeo',     'Spider Duetto',     'Cabriolet', 2, 180.00, 0, 'Non disponible', 'Rendu célèbre par Le Lauréat, l\'Alfa Romeo Spider Duetto est l\'archétype du roadster italien : lignes sensuelles, mélodie du moteur, dolce vita garantie.', 'alfa_spider.jpg'),
('Mercedes-Benz',  '280 SL Pagode',     'Coupé',     2, 290.00, 0, 'Disponible',     'La Mercedes 280 SL Pagode (1968) est une élégance intemporelle sur roues. Son toit amovible en forme de pagode et son six cylindres suave en font une pièce de collection absolue.', 'mercedes_280sl.jpg'),
('Aston Martin',   'DB6 Vantage',       'Berline',   4, 520.00, 0, 'Disponible',     'L\'Aston Martin DB6 : la voiture de James Bond dans toute sa magnificence. Six cylindres en ligne, carrosserie sculptée à la main, raffinement britannique à l\'état pur.', 'astonmartin_db6.jpg'),
('Lamborghini',    'Countach LP400',    'Sportive',  2, 680.00, 0, 'Non disponible', 'La Lamborghini Countach est le symbole absolu des années 70-80. Ses lignes angulaires et son V12 rugissant de 375 ch ont défini à jamais l\'idée de supercar.', 'lambo_countach.jpg'),
('Maserati',       'Merak SS',          'Coupé',     2, 250.00, 0, 'Disponible',     'Le Maserati Merak SS réunit le design de Giorgetto Giugiaro et un V6 biturbo de 2,9L. Une GT italienne alliant charisme et performance avec une discrétion de bon ton.', 'maserati_merak.jpg');

COMMIT;
