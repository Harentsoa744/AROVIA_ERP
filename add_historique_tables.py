#!/usr/bin/env python3
import re

def add_historique_tables(file_path):
    """Add historique_livraison and historique_contrat tables to SQL file"""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Define the new tables
    historique_livraison = """
-- Historique des changements de statut des livraisons
CREATE TABLE historique_livraison (
    id                  SERIAL PRIMARY KEY,
    livraison_id        INTEGER REFERENCES livraisons(id) ON DELETE CASCADE,
    statut_precedent    VARCHAR(20),
    statut_nouveau      VARCHAR(20) NOT NULL,
    date_changement     TIMESTAMP NOT NULL DEFAULT NOW(),
    utilisateur_id      INTEGER REFERENCES utilisateurs(id),
    commentaire         TEXT
);

"""

    historique_contrat = """
-- Historique des changements de statut des contrats
CREATE TABLE historique_contrat (
    id                  SERIAL PRIMARY KEY,
    contrat_id          INTEGER REFERENCES contrats(id) ON DELETE CASCADE,
    statut_precedent    VARCHAR(20),
    statut_nouveau      VARCHAR(20) NOT NULL,
    date_changement     TIMESTAMP NOT NULL DEFAULT NOW(),
    utilisateur_id      INTEGER REFERENCES utilisateurs(id),
    commentaire         TEXT
);

"""
    
    # Find a good place to insert - after the livraisons table definition
    # Look for the closing of livraisons table
    pattern = r'(CREATE TABLE livraisons \([^)]*\);)'
    
    def replace_func(match):
        return match.group(1) + historique_livraison + historique_contrat
    
    # Check if tables already exist
    if 'CREATE TABLE historique_livraison' in content:
        print(f"historique_livraison already exists in {file_path}")
        return
    
    if 'CREATE TABLE historique_contrat' in content:
        print(f"historique_contrat already exists in {file_path}")
        return
    
    content = re.sub(pattern, replace_func, content, flags=re.DOTALL)
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Added historique tables to {file_path}")

# Apply to all SQL files
files = [
    'all_in_one.sql',
    'fusion.sql'
]

for file in files:
    try:
        add_historique_tables(file)
    except FileNotFoundError:
        print(f"File {file} not found, skipping...")
