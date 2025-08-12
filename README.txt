MASAR - Final Sync Package (XAMPP-ready)

Instructions:
1. Extract this folder to C:\xampp\htdocs\masar
2. Start Apache & MySQL in XAMPP control panel
3. Import db.sql via http://localhost/phpmyadmin (Import tab)
4. Open http://localhost/masar/ in browser
5. Register, login, add products to cart, update quantities with +/-, checkout

Notes:
- All AJAX endpoints use absolute paths (http://localhost/masar/api/...)
- If you change project folder name, update AJAX URLs accordingly.
- For debugging, check Apache/PHP logs and browser DevTools network tab.
