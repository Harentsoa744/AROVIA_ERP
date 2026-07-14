#!/usr/bin/env python3
import re

def add_date_livraison_to_sorties(file_path):
    """Add date_livraison column to sorties table definition"""
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Find the sorties table definition and add date_livraison after statut
    pattern = r'(CREATE TABLE sorties \([^)]*statut\s+VARCHAR\(20\)\s+DEFAULT\s+\'A_LIVRER\'\s*--[^)]*\))'
    
    def replace_func(match):
        table_def = match.group(1)
        # Add date_livraison column before the closing parenthesis
        if 'date_livraison' not in table_def:
            table_def = table_def.replace(
                "statut              VARCHAR(20) DEFAULT 'A_LIVRER' -- 'A_LIVRER', 'PRIS', 'ANNULE'",
                "statut              VARCHAR(20) DEFAULT 'A_LIVRER', -- 'A_LIVRER', 'PRIS', 'ANNULE'\n    date_livraison       TIMESTAMP DEFAULT NULL -- Date et heure de livraison prévue"
            )
        return table_def
    
    content = re.sub(pattern, replace_func, content, flags=re.DOTALL)
    
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Added date_livraison column to sorties in {file_path}")

# Apply to all SQL files
files = [
    'all_in_one.sql',
    'fusion.sql',
    'donnees_test_clean.sql'
]

for file in files:
    try:
        add_date_livraison_to_sorties(file)
    except FileNotFoundError:
        print(f"File {file} not found, skipping...")
