# Fix SQL escape characters in donnees_test_clean.sql
with open(r'c:\Users\Harena\Documents\MmeBaovola\AROVIA_upgrade\donnees_test_clean.sql', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace \' with '' (standard SQL escape for apostrophe)
content = content.replace("\\'", "''")

with open(r'c:\Users\Harena\Documents\MmeBaovola\AROVIA_upgrade\donnees_test_clean.sql', 'w', encoding='utf-8') as f:
    f.write(content)

print('SQL escape characters fixed in donnees_test_clean.sql')
