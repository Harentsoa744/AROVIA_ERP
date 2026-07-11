-- Migration pour ajouter la colonne photo_profil aux tables employes et utilisateurs

-- Ajouter photo_profil à la table employes
ALTER TABLE employes ADD COLUMN photo_profil VARCHAR(255) DEFAULT NULL;

-- Ajouter photo_profil à la table utilisateurs  
ALTER TABLE utilisateurs ADD COLUMN photo_profil VARCHAR(255) DEFAULT NULL;
