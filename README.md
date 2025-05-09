<img src="https://github.com/user-attachments/assets/f85cdeab-e722-41fc-9ed2-13620e5655af" alt="Popis obrázku" height="300">


## Popis aplikace
**ShareMoney** je webová aplikace umožňující správu sdílených financí mezi více uživateli.  
Mohla by se hodit pro spolubydlení, výlety s kamarády, páry nebo kolegy, kteří sdílí výdaje.

Každý uživatel může:
- Založit nový společný účet
- Připojit se k existujícímu účtu pokud ho admin přidá

Účty mají přiřazené role s různými oprávněními:
- **Admin** (1x)
- **Moderátor** (Nx)
- **Uživatel** (Nx)

Aplikace umožňuje:

- Vkládání peněz
- Odesílání a přijímání plateb
- Přehled transakcí
- Správu členů v rámci účtu

---

## Architektura

- **Backend**: PHP 8+  
  *(rád bych vyzkoušel Laravel hlavně kvůli Eloquent ORM)*
- **Databáze**: MariaDB
- **Frontend**: HTML + JS + CSS  
  *(využiji Bootstrap nebo TailwindCSS)*
