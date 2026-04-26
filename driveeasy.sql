SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `montant_ttc` float NOT NULL,
  `age` int(11) NOT NULL,
  `id_vehicule` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reservation` (`id_reservation`, `nom`, `prenom`, `telephone`, `date_debut`, `date_fin`, `montant_ttc`, `age`, `id_vehicule`) VALUES
(6, 'Florent', 'sfqfs', '0609928215', '2026-04-26', '2026-04-27', 0, 18, 3),
(9, 'Florent', 'sfqfs', '0609928215', '2026-04-26', '2026-04-29', 1140, 18, 2);

CREATE TABLE `vehicule` (
  `id_vehicule` int(11) NOT NULL,
  `marque` varchar(100) NOT NULL,
  `modele` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `capacite` int(11) NOT NULL,
  `prix_jour` decimal(6,2) NOT NULL,
  `montant_ttc` int(11) NOT NULL,
  `boite` tinyint(1) NOT NULL COMMENT '0 = manuelle, 1 = automatique',
  `disponibilite` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `vehicule` (`id_vehicule`, `marque`, `modele`, `type`, `capacite`, `prix_jour`, `montant_ttc`, `boite`, `disponibilite`, `description`, `image`) VALUES
(1, 'Shelby', 'Cobra 427', 'Cabriolet', 2, 520.00, 520, 0, 'Disponible', 'Icône absolue des années 60, la Shelby Cobra 427 est un roadster brutal animé par un V8 américain surdimensionné. Légère, sauvage et mythique, elle incarne l’âge d’or des muscle cars de compétition.', 'vehicule1.png'),
(2, 'Chevrolet', 'Corvette C1', 'Cabriolet', 2, 380.00, 380, 0, 'Disponible', 'Symbole du rêve américain, la Corvette C1 de 1958 séduit par ses doubles optiques, ses chromes généreux et son style rock’n’roll. Un cabriolet iconique qui respire la liberté des sixties.', 'vehicule2.png'),
(3, 'Fiat', 'Barchetta', 'Cabriolet', 2, 180.00, 180, 0, 'Disponible', 'Petit roadster italien plein de charme, la Fiat Barchetta séduit par sa légèreté, son style néo‑rétro et son moteur vif. Une voiture plaisir idéale pour les balades ensoleillées.', 'vehicule3.png'),
(4, 'Ferrari', '360 Spider', 'Cabriolet sportif', 2, 620.00, 620, 1, 'Non disponible', 'Supercar emblématique des années 2000, la Ferrari 360 Spider associe un V8 atmosphérique mélodieux à une ligne sculptée. Un cabriolet d’exception pour vivre l’émotion Ferrari cheveux au vent.', 'vehicule4.png'),
(5, 'Porsche', '911 (901)', 'Coupé', 2, 350.00, 350, 0, 'Disponible', 'Première génération de la légendaire 911, ce modèle de 1965 offre un flat‑6 atmosphérique au caractère unique. Élégante, précise et intemporelle, elle représente l’ADN Porsche à l’état pur.', 'vehicule5.png');
tion`

ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_vehicule` (`id_vehicule`);

ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id_vehicule`);

ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `vehicule`
  MODIFY `id_vehicule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule` (`id_vehicule`);
COMMIT;
