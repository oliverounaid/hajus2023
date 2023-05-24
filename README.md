# Kirjeldus
Kõik ülesanded võib lahendada eraldi või ühe tervikliku projektina.
Valmis töö(d) tuleb laadida zone'i ja kood githubi/gitlabi vms.

# Template
Kes soovib teha kõiki ülesandeid ühe projektina võib kasutada selle repo template'i.
See on tühi Laravel + vue app koos inertia ja vite'ga (Autentimine on juba küljes).

## Template kasutamine
* Tee projektist fork
* `composer install`
* `npm install`
* Seadista .env fail
* `php artisan key:generate`
* `php artisan config:clear`
* `php artisan migrate`

* `php artisan serve` - käivitab laravel'i
* `npx vite` - käivitab vite

Deployment
* Seadista enda zone'i serveri järgi deploy.php fail.
* `./dep deploy` Kui tuleb kiri et, permission denied siis `chmod +x dep` ja proovi uuesti


# Hajusrakenduse ülesanded
## 1. Vabalt valitud ilma API
* Ühendada API-ga ja saada info (näiteks formaat JSON)
* Andmed cache-ida
* Väljastada info (võimalusel koos pildiga)
* Näiteks: https://openweathermap.org/price

## 2. Google Maps API
* Luua tabel markers (id, name, latitude, longitude, description, added, edited)
* CRUD markerile. (name, latitude, longitude, description)
* Luua google map
* Lisada markerid map-ile
* Click mapil võimaldab salvestada markerit
* Tutorial: https://developers.google.com/maps/documentation/javascript/tutorial

## 3. Laravel - projekti loomine Uudised. Kommentaaride CRUD
* Luua project Blog
* Luua autentimine
* Luua migration create_blog_table (title, description)
* CRUD blog-ile
* Võimalus lisada kommentaare. Ning saab kustutada (admin).

## 4. Pood - luua ostukorv
* Luua toote valiku leht (vähemalt 9 toodet, saab valida kogust)
* Luua ostukorv. Saab muuta koguseid ja kustutada tooteid
* Luua maksmise leht (eesnimi, perenimi, email, telefon), lingid mine maksma
* Peale makse sooritamist, vastavalt  tulemusele kas tühjendada ostukorv või mitte.

## 5. Vabalt valida lemmik teema ja luua sellele API
* Luua tabel my_favorite_subject (id, title, image, description, +2 teema kohast välja)
* Luua ankeet (title, description, image, +2 teema kohast välja)
* Luua väljund sisestatud teemades. (JSON) Väljund peab sisaldame vähemalt ühte parameetrit (limit)
* Luua leht, mis loeb kaas õpilaste teemasid.
* Cache iga teema kohta

