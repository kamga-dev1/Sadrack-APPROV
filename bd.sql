-- ============================================================
--  BASE DE DONNÉES : GESTION D'APPROVISIONNEMENT FOURNISSEUR
--  Stack : PHP + Node.js + MySQL
--  Version : 1.0 | Février 2026
-- ============================================================

-- Créer et sélectionner la base de données
CREATE DATABASE IF NOT EXISTS gestion_approvisionnement
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_approvisionnement;

-- ============================================================
--  DÉSACTIVER LES CONTRAINTES LE TEMPS DE LA CRÉATION
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;


-- ============================================================
--  TABLE : UTILISATEURS
-- ============================================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id_utilisateur  INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    nom             VARCHAR(100)      NOT NULL,
    email           VARCHAR(150)      NOT NULL UNIQUE,
    mot_de_passe    VARCHAR(255)      NOT NULL,             -- Hash bcrypt
    role            ENUM('admin','gestionnaire','magasinier') NOT NULL DEFAULT 'magasinier',
    statut          ENUM('actif','inactif')                 NOT NULL DEFAULT 'actif',
    derniere_connexion DATETIME                             NULL,
    created_at      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_utilisateur),
    INDEX idx_email (email),
    INDEX idx_role  (role)
) ENGINE=InnoDB COMMENT='Comptes utilisateurs du système';


-- ============================================================
--  TABLE : FOURNISSEURS
-- ============================================================
CREATE TABLE IF NOT EXISTS fournisseurs (
    id_fournisseur      INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    code                VARCHAR(20)   NOT NULL UNIQUE,      -- Ex: FOUR-001
    nom                 VARCHAR(150)  NOT NULL,
    contact_nom         VARCHAR(100)  NULL,
    telephone           VARCHAR(30)   NULL,
    email               VARCHAR(150)  NULL,
    adresse             TEXT          NULL,
    conditions_paiement VARCHAR(100)  NULL,                 -- Ex: 30 jours net
    delai_livraison_moyen INT UNSIGNED NULL COMMENT 'En jours',
    note                TINYINT UNSIGNED NULL COMMENT 'Note sur 5',
    statut              ENUM('actif','inactif','suspendu')  NOT NULL DEFAULT 'actif',
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_fournisseur),
    INDEX idx_code    (code),
    INDEX idx_statut  (statut)
) ENGINE=InnoDB COMMENT='Fournisseurs enregistrés dans le système';


-- ============================================================
--  TABLE : CATEGORIES D'ARTICLES (référentiel)
-- ============================================================
CREATE TABLE IF NOT EXISTS categories (
    id_categorie  INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nom           VARCHAR(100)  NOT NULL UNIQUE,
    description   TEXT          NULL,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_categorie)
) ENGINE=InnoDB COMMENT='Catégories d articles';


-- ============================================================
--  TABLE : ARTICLES
-- ============================================================
CREATE TABLE IF NOT EXISTS articles (
    id_article              INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_fournisseur          INT UNSIGNED      NOT NULL,
    id_categorie            INT UNSIGNED      NULL,
    reference               VARCHAR(50)       NOT NULL UNIQUE,  -- Ex: ART-0042
    nom                     VARCHAR(200)      NOT NULL,
    description             TEXT              NULL,
    unite                   VARCHAR(20)       NOT NULL DEFAULT 'pièce', -- pièce, kg, litre...
    prix_achat              DECIMAL(12,2)     NOT NULL DEFAULT 0.00,
    stock_actuel            DECIMAL(12,3)     NOT NULL DEFAULT 0.000,
    seuil_minimum           DECIMAL(12,3)     NOT NULL DEFAULT 0.000,
    delai_approvisionnement INT UNSIGNED      NULL COMMENT 'En jours',
    statut                  ENUM('actif','inactif','discontinué') NOT NULL DEFAULT 'actif',
    created_at              DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_article),
    INDEX idx_reference      (reference),
    INDEX idx_fournisseur    (id_fournisseur),
    INDEX idx_categorie      (id_categorie),
    INDEX idx_stock_alerte   (stock_actuel, seuil_minimum),
    CONSTRAINT fk_article_fournisseur
        FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id_fournisseur)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_article_categorie
        FOREIGN KEY (id_categorie) REFERENCES categories(id_categorie)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Catalogue des articles gérés';


-- ============================================================
--  TABLE : COMMANDES
-- ============================================================
CREATE TABLE IF NOT EXISTS commandes (
    id_commande             INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_fournisseur          INT UNSIGNED      NOT NULL,
    id_utilisateur          INT UNSIGNED      NOT NULL,     -- Créateur de la commande
    numero_commande         VARCHAR(30)       NOT NULL UNIQUE, -- Ex: CMD-2026-0001
    date_commande           DATE              NOT NULL,
    date_prevue_livraison   DATE              NULL,
    statut                  ENUM(
                                'brouillon',
                                'en_attente',
                                'confirmée',
                                'expédiée',
                                'reçue_partielle',
                                'reçue_totale',
                                'annulée'
                            )                 NOT NULL DEFAULT 'en_attente',
    montant_total_ht        DECIMAL(14,2)     NOT NULL DEFAULT 0.00,
    taux_tva                DECIMAL(5,2)      NOT NULL DEFAULT 19.25, -- Taux TVA en %
    montant_tva             DECIMAL(14,2)     NOT NULL DEFAULT 0.00,
    montant_total_ttc       DECIMAL(14,2)     NOT NULL DEFAULT 0.00,
    notes                   TEXT              NULL,
    created_at              DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_commande),
    INDEX idx_numero         (numero_commande),
    INDEX idx_fournisseur    (id_fournisseur),
    INDEX idx_statut         (statut),
    INDEX idx_date_commande  (date_commande),
    CONSTRAINT fk_commande_fournisseur
        FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id_fournisseur)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_commande_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Bons de commande fournisseurs';


-- ============================================================
--  TABLE : LIGNES DE COMMANDE
-- ============================================================
CREATE TABLE IF NOT EXISTS lignes_commande (
    id_ligne_commande   INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_commande         INT UNSIGNED      NOT NULL,
    id_article          INT UNSIGNED      NOT NULL,
    quantite            DECIMAL(12,3)     NOT NULL,
    prix_unitaire       DECIMAL(12,2)     NOT NULL,
    total_ht            DECIMAL(14,2)     NOT NULL,         -- quantite * prix_unitaire
    created_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_ligne_commande),
    INDEX idx_commande   (id_commande),
    INDEX idx_article    (id_article),
    UNIQUE KEY uq_commande_article (id_commande, id_article), -- Un article une fois par commande
    CONSTRAINT fk_lc_commande
        FOREIGN KEY (id_commande) REFERENCES commandes(id_commande)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_lc_article
        FOREIGN KEY (id_article) REFERENCES articles(id_article)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Détail des lignes d une commande';


-- ============================================================
--  TABLE : RÉCEPTIONS
-- ============================================================
CREATE TABLE IF NOT EXISTS receptions (
    id_reception        INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_commande         INT UNSIGNED      NOT NULL,
    id_utilisateur      INT UNSIGNED      NOT NULL,         -- Magasinier réceptionneur
    date_reception      DATE              NOT NULL,
    statut              ENUM('en_cours','complète','partielle','litige') NOT NULL DEFAULT 'complète',
    observation         TEXT              NULL,
    created_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_reception),
    INDEX idx_commande   (id_commande),
    INDEX idx_date       (date_reception),
    CONSTRAINT fk_reception_commande
        FOREIGN KEY (id_commande) REFERENCES commandes(id_commande)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_reception_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Réceptions physiques des livraisons';


-- ============================================================
--  TABLE : LIGNES DE RÉCEPTION
-- ============================================================
CREATE TABLE IF NOT EXISTS lignes_reception (
    id_ligne_reception  INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_reception        INT UNSIGNED      NOT NULL,
    id_article          INT UNSIGNED      NOT NULL,
    quantite_commandee  DECIMAL(12,3)     NOT NULL,
    quantite_recue      DECIMAL(12,3)     NOT NULL,
    ecart               DECIMAL(12,3)     AS (quantite_commandee - quantite_recue) STORED,
    commentaire         VARCHAR(255)      NULL,
    created_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_ligne_reception),
    INDEX idx_reception  (id_reception),
    INDEX idx_article    (id_article),
    CONSTRAINT fk_lr_reception
        FOREIGN KEY (id_reception) REFERENCES receptions(id_reception)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_lr_article
        FOREIGN KEY (id_article) REFERENCES articles(id_article)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Détail des articles réceptionnés';


-- ============================================================
--  TABLE : PAIEMENTS
-- ============================================================
CREATE TABLE IF NOT EXISTS paiements (
    id_paiement         INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_fournisseur      INT UNSIGNED      NOT NULL,
    id_commande         INT UNSIGNED      NULL,             -- Peut être sans commande (avoir...)
    id_utilisateur      INT UNSIGNED      NOT NULL,
    numero_facture      VARCHAR(50)       NOT NULL,
    date_facture        DATE              NOT NULL,
    montant             DECIMAL(14,2)     NOT NULL,
    date_echeance       DATE              NOT NULL,
    statut              ENUM('en_attente','payé','en_retard','annulé') NOT NULL DEFAULT 'en_attente',
    mode_paiement       ENUM('virement','chèque','espèces','autre') NULL,
    date_paiement       DATE              NULL,             -- Date effective du règlement
    reference_paiement  VARCHAR(100)      NULL,             -- Numéro de virement, chèque...
    notes               TEXT              NULL,
    created_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_paiement),
    INDEX idx_fournisseur   (id_fournisseur),
    INDEX idx_commande      (id_commande),
    INDEX idx_statut        (statut),
    INDEX idx_echeance      (date_echeance),
    CONSTRAINT fk_paiement_fournisseur
        FOREIGN KEY (id_fournisseur) REFERENCES fournisseurs(id_fournisseur)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_paiement_commande
        FOREIGN KEY (id_commande) REFERENCES commandes(id_commande)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_paiement_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB COMMENT='Paiements et factures fournisseurs';


-- ============================================================
--  TABLE : MOUVEMENTS DE STOCK
-- ============================================================
CREATE TABLE IF NOT EXISTS mouvements_stock (
    id_mouvement        INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    id_article          INT UNSIGNED      NOT NULL,
    id_utilisateur      INT UNSIGNED      NOT NULL,
    type_mouvement      ENUM('entree','sortie','ajustement','inventaire') NOT NULL,
    quantite            DECIMAL(12,3)     NOT NULL,         -- Toujours positif
    stock_avant         DECIMAL(12,3)     NOT NULL,
    stock_apres         DECIMAL(12,3)     NOT NULL,
    reference_document  VARCHAR(50)       NULL,             -- Ex: REC-001, CMD-001
    id_reception        INT UNSIGNED      NULL,             -- Lien direct si mouvement = réception
    date_mouvement      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
    motif               VARCHAR(255)      NULL,
    PRIMARY KEY (id_mouvement),
    INDEX idx_article       (id_article),
    INDEX idx_date          (date_mouvement),
    INDEX idx_type          (type_mouvement),
    INDEX idx_reception     (id_reception),
    CONSTRAINT fk_mouvement_article
        FOREIGN KEY (id_article) REFERENCES articles(id_article)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_mouvement_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_mouvement_reception
        FOREIGN KEY (id_reception) REFERENCES receptions(id_reception)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB COMMENT='Historique de tous les mouvements de stock';


-- ============================================================
--  RÉACTIVER LES CONTRAINTES
-- ============================================================
SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
--  VUES UTILES
-- ============================================================

-- Vue : Articles en alerte de stock
CREATE OR REPLACE VIEW v_articles_alerte AS
SELECT
    a.id_article,
    a.reference,
    a.nom,
    c.nom            AS categorie,
    f.nom            AS fournisseur,
    a.stock_actuel,
    a.seuil_minimum,
    (a.seuil_minimum - a.stock_actuel) AS manque,
    a.unite,
    a.prix_achat
FROM articles a
LEFT JOIN categories c  ON c.id_categorie  = a.id_categorie
LEFT JOIN fournisseurs f ON f.id_fournisseur = a.id_fournisseur
WHERE a.stock_actuel <= a.seuil_minimum
  AND a.statut = 'actif';


-- Vue : Commandes avec détail fournisseur
CREATE OR REPLACE VIEW v_commandes_detail AS
SELECT
    cmd.id_commande,
    cmd.numero_commande,
    cmd.date_commande,
    cmd.date_prevue_livraison,
    cmd.statut,
    cmd.montant_total_ht,
    cmd.taux_tva,
    cmd.montant_total_ttc,
    f.id_fournisseur,
    f.nom            AS fournisseur_nom,
    f.telephone      AS fournisseur_tel,
    u.nom            AS createur,
    COUNT(lc.id_ligne_commande) AS nb_lignes
FROM commandes cmd
JOIN fournisseurs f ON f.id_fournisseur = cmd.id_fournisseur
JOIN utilisateurs u ON u.id_utilisateur = cmd.id_utilisateur
LEFT JOIN lignes_commande lc ON lc.id_commande = cmd.id_commande
GROUP BY cmd.id_commande;


-- Vue : Paiements en retard ou à échéance proche (7 jours)
CREATE OR REPLACE VIEW v_paiements_urgents AS
SELECT
    p.id_paiement,
    p.numero_facture,
    p.date_facture,
    p.montant,
    p.date_echeance,
    p.statut,
    DATEDIFF(CURDATE(), p.date_echeance) AS jours_retard,
    f.nom AS fournisseur,
    cmd.numero_commande
FROM paiements p
JOIN fournisseurs f ON f.id_fournisseur = p.id_fournisseur
LEFT JOIN commandes cmd ON cmd.id_commande = p.id_commande
WHERE p.statut IN ('en_attente','en_retard')
  AND p.date_echeance <= DATE_ADD(CURDATE(), INTERVAL 7 DAY);


-- Vue : KPIs tableau de bord
CREATE OR REPLACE VIEW v_dashboard_kpis AS
SELECT
    (SELECT COUNT(*) FROM commandes WHERE statut IN ('en_attente','confirmée','expédiée'))
        AS commandes_en_cours,
    (SELECT COUNT(*) FROM v_articles_alerte)
        AS articles_en_alerte,
    (SELECT COUNT(*) FROM paiements WHERE statut = 'en_retard')
        AS paiements_en_retard,
    (SELECT COALESCE(SUM(stock_actuel * prix_achat), 0) FROM articles WHERE statut = 'actif')
        AS valeur_stock_total,
    (SELECT COUNT(*) FROM fournisseurs WHERE statut = 'actif')
        AS fournisseurs_actifs,
    (SELECT COALESCE(SUM(montant_total_ttc), 0) FROM commandes
      WHERE MONTH(date_commande) = MONTH(CURDATE())
        AND YEAR(date_commande) = YEAR(CURDATE()))
        AS commandes_mois_ttc;


-- ============================================================
--  TRIGGERS
-- ============================================================

DELIMITER $$

-- Trigger : Mise à jour automatique du stock après réception
CREATE TRIGGER trg_after_ligne_reception_insert
AFTER INSERT ON lignes_reception
FOR EACH ROW
BEGIN
    DECLARE v_stock_avant DECIMAL(12,3);
    SELECT stock_actuel INTO v_stock_avant FROM articles WHERE id_article = NEW.id_article;

    -- Mise à jour du stock
    UPDATE articles
       SET stock_actuel = stock_actuel + NEW.quantite_recue,
           updated_at   = NOW()
     WHERE id_article = NEW.id_article;

    -- Enregistrement du mouvement de stock
    INSERT INTO mouvements_stock
        (id_article, id_utilisateur, type_mouvement, quantite, stock_avant, stock_apres,
         reference_document, id_reception, date_mouvement)
    SELECT
        NEW.id_article,
        r.id_utilisateur,
        'entree',
        NEW.quantite_recue,
        v_stock_avant,
        v_stock_avant + NEW.quantite_recue,
        CONCAT('REC-', LPAD(NEW.id_reception, 5, '0')),
        NEW.id_reception,
        NOW()
    FROM receptions r WHERE r.id_reception = NEW.id_reception;
END$$


-- Trigger : Recalcul automatique des totaux d'une commande
CREATE TRIGGER trg_after_ligne_commande_insert
AFTER INSERT ON lignes_commande
FOR EACH ROW
BEGIN
    UPDATE commandes
       SET montant_total_ht  = (
               SELECT COALESCE(SUM(total_ht), 0)
               FROM lignes_commande
               WHERE id_commande = NEW.id_commande
           ),
           montant_tva       = montant_total_ht * taux_tva / 100,
           montant_total_ttc = montant_total_ht + (montant_total_ht * taux_tva / 100),
           updated_at        = NOW()
     WHERE id_commande = NEW.id_commande;
END$$

CREATE TRIGGER trg_after_ligne_commande_update
AFTER UPDATE ON lignes_commande
FOR EACH ROW
BEGIN
    UPDATE commandes
       SET montant_total_ht  = (
               SELECT COALESCE(SUM(total_ht), 0)
               FROM lignes_commande
               WHERE id_commande = NEW.id_commande
           ),
           montant_tva       = montant_total_ht * taux_tva / 100,
           montant_total_ttc = montant_total_ht + (montant_total_ht * taux_tva / 100),
           updated_at        = NOW()
     WHERE id_commande = NEW.id_commande;
END$$

CREATE TRIGGER trg_after_ligne_commande_delete
AFTER DELETE ON lignes_commande
FOR EACH ROW
BEGIN
    UPDATE commandes
       SET montant_total_ht  = (
               SELECT COALESCE(SUM(total_ht), 0)
               FROM lignes_commande
               WHERE id_commande = OLD.id_commande
           ),
           montant_tva       = montant_total_ht * taux_tva / 100,
           montant_total_ttc = montant_total_ht + (montant_total_ht * taux_tva / 100),
           updated_at        = NOW()
     WHERE id_commande = OLD.id_commande;
END$$


-- Trigger : Mise à jour statut paiement si date dépassée
CREATE TRIGGER trg_before_paiement_update
BEFORE UPDATE ON paiements
FOR EACH ROW
BEGIN
    IF NEW.statut = 'en_attente' AND NEW.date_echeance < CURDATE() THEN
        SET NEW.statut = 'en_retard';
    END IF;
END$$

DELIMITER ;


-- ============================================================
--  DONNÉES DE DÉMARRAGE (Seed)
-- ============================================================

-- Utilisateurs par défaut (mot de passe : Admin123! en bcrypt)
INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES
('Administrateur',  'admin@gestion.local',       '$2y$12$LzGnFkqxXvT3mJvD9pN2uO5Wc8eH4kR7yBdA1Pq6Xs9Mn3Fj0Te2', 'admin'),
('Jean Gestionnaire','gestionnaire@gestion.local','$2y$12$LzGnFkqxXvT3mJvD9pN2uO5Wc8eH4kR7yBdA1Pq6Xs9Mn3Fj0Te2', 'gestionnaire'),
('Marc Magasinier',  'magasinier@gestion.local',  '$2y$12$LzGnFkqxXvT3mJvD9pN2uO5Wc8eH4kR7yBdA1Pq6Xs9Mn3Fj0Te2', 'magasinier');

-- Catégories de base
INSERT INTO categories (nom, description) VALUES
('Fournitures de bureau',  'Papeterie, stylos, cahiers, cartouches...'),
('Informatique',           'Matériel informatique, périphériques, accessoires'),
('Mobilier',               'Bureaux, chaises, armoires, rangements'),
('Nettoyage & Hygiène',    'Produits d entretien, consommables hygiène'),
('Matières premières',     'Matières premières pour la production'),
('Emballages',             'Boîtes, sachets, film, ruban adhésif');

-- Fournisseurs de démonstration
INSERT INTO fournisseurs (code, nom, contact_nom, telephone, email, adresse, conditions_paiement, delai_livraison_moyen, note, statut) VALUES
('FOUR-001', 'Bureau Plus SARL',       'Alain Dupont',   '+237 699 001 001', 'contact@bureauplus.cm',   'Rue de la Joie, Douala',         '30 jours net',    5,  4, 'actif'),
('FOUR-002', 'Tech Office Cameroun',   'Fatima Ngozi',   '+237 677 002 002', 'info@techoffice.cm',      'Av. Kennedy, Yaoundé',           '45 jours net',    7,  5, 'actif'),
('FOUR-003', 'CleanPro Distribution',  'Paul Essomba',   '+237 655 003 003', 'ventes@cleanpro.cm',      'Zone Industrielle, Douala',       '15 jours net',    3,  3, 'actif'),
('FOUR-004', 'MobiCam Equipements',    'Sophie Biyong',  '+237 691 004 004', 'sophie@mobicam.cm',       'Bd de la Liberté, Bafoussam',    '60 jours net',    14, 4, 'actif'),
('FOUR-005', 'MatPro Industries',      'Hervé Talla',    '+237 670 005 005', 'herve@matpro.cm',         'Route de Bonaberi, Douala',       '30 jours net',    10, 4, 'actif');

-- Articles de démonstration
INSERT INTO articles (id_fournisseur, id_categorie, reference, nom, unite, prix_achat, stock_actuel, seuil_minimum, delai_approvisionnement) VALUES
(1, 1, 'ART-001', 'Rame papier A4 80g',          'rame',   3500.00,  45,   20,  5),
(1, 1, 'ART-002', 'Stylo bille bleu (boîte 50)', 'boîte',  4200.00,  8,    10,  5),
(1, 1, 'ART-003', 'Classeur à levier A4',         'pièce',  1200.00,  30,   15,  5),
(2, 2, 'ART-004', 'Souris USB optique',           'pièce',  5500.00,  12,   5,   7),
(2, 2, 'ART-005', 'Clavier AZERTY filaire',       'pièce',  7800.00,  6,    5,   7),
(2, 2, 'ART-006', 'Cartouche encre noire HP',     'pièce',  12500.00, 3,    8,   7),
(3, 4, 'ART-007', 'Gel hydroalcoolique 1L',       'flacon', 2800.00,  25,   20,  3),
(3, 4, 'ART-008', 'Savon liquide mains 5L',       'bidon',  4500.00,  5,    6,   3),
(4, 3, 'ART-009', 'Chaise de bureau ergonomique', 'pièce',  85000.00, 8,    3,   14),
(5, 5, 'ART-010', 'Carton d emballage 40x30x20', 'pièce',  350.00,   200,  100, 10);


-- ============================================================
--  REQUÊTES UTILES (commentées pour référence)
-- ============================================================

/*
-- Articles en alerte de stock
SELECT * FROM v_articles_alerte;

-- KPIs pour le dashboard
SELECT * FROM v_dashboard_kpis;

-- Commandes en cours avec détails
SELECT * FROM v_commandes_detail WHERE statut NOT IN ('reçue_totale','annulée');

-- Paiements urgents (en retard ou à moins de 7 jours)
SELECT * FROM v_paiements_urgents ORDER BY jours_retard DESC;

-- Historique des mouvements pour un article
SELECT ms.*, a.reference, a.nom
FROM mouvements_stock ms
JOIN articles a ON a.id_article = ms.id_article
WHERE ms.id_article = 1
ORDER BY ms.date_mouvement DESC;

-- Valeur totale du stock par catégorie
SELECT c.nom AS categorie,
       COUNT(a.id_article) AS nb_articles,
       SUM(a.stock_actuel * a.prix_achat) AS valeur_stock
FROM articles a
JOIN categories c ON c.id_categorie = a.id_categorie
WHERE a.statut = 'actif'
GROUP BY c.id_categorie
ORDER BY valeur_stock DESC;
*/
