# Call Registration

Дуудлага бүртгэх, инженер/ажилтанд хуваарилах, шийдвэрлэлтийг хянах Laravel веб систем.

## Шаардлагатай програмууд

- Git
- PHP 8.2 буюу түүнээс дээш
- Composer 2
- Node.js 20.19+ эсвэл 22.12+ болон npm
- PHP extension-үүд: `pdo_sqlite`, `sqlite3`, `mbstring`, `openssl`, `fileinfo`, `dom`, `ctype`, `tokenizer`, `xml`

Анхдагч тохиргоо нь SQLite ашигладаг тул MySQL суулгах шаардлагагүй.

## Clone хийж ажиллуулах

### 1. Төслийг татах

```bash
git clone https://github.com/sumiyabazar0904-oss/call-registration.git
cd call-registration
```

### 2. Анхны тохиргоог хийх

```bash
composer run setup
```

Энэ команд дараах ажлуудыг автоматаар хийнэ:

- PHP package-уудыг суулгана;
- `.env` файл үүсгэж, application key тохируулна;
- SQLite database үүсгэнэ;
- migration болон demo seed ажиллуулна;
- frontend package-уудыг суулгаж, asset-уудыг build хийнэ.

Хавсаргасан зураг харагддаг болгохын тулд storage холбоосыг нэг удаа үүсгэнэ:

```bash
php artisan storage:link
```

Windows дээр symbolic link-ийн permission алдаа гарвал PowerShell/Terminal-аа **Run as administrator** горимоор нээгээд дээрх командыг дахин ажиллуулна.

### 3. Development server асаах

```bash
composer run dev
```

Дараа нь хөтөч дээр [http://127.0.0.1:8000](http://127.0.0.1:8000) хаягийг нээнэ. Энэ команд Laravel server, queue worker, log viewer болон Vite-ийг хамтад нь ажиллуулна. Зогсоохдоо `Ctrl+C` дарна.

## Demo хэрэглэгчдийн нэвтрэх мэдээлэл

| Эрх | Нэвтрэх нэр | Нууц үг |
| --- | --- | --- |
| Админ | `admin` | `password123` |
| Оператор | `operator` | `password123` |
| Инженер | `engineer` | `password123` |
| Инженер | `agent0` | `password123` |
| Инженер | `agent1` | `password123` |
| Инженер | `agent2` | `password123` |
| Инженер | `agent3` | `password123` |
| Инженер | `agent4` | `password123` |
| Инженер | `agent5` | `password123` |
| Инженер | `agent6` | `password123` |
| Инженер | `agent7` | `password123` |
| Инженер | `agent8` | `password123` |
| Инженер | `agent9` | `password123` |
| Инженер | `agent10` | `password123` |
| Харилцагч | `customer` | `password123` |
| Харилцагч | `borjigin` | `password123` |
| Харилцагч | `baturnukh` | `password123` |
| Харилцагч | `sumiya` | `password123` |

Нэвтрэх хуудас: [http://127.0.0.1:8000/nevtreh](http://127.0.0.1:8000/nevtreh)

## Гараар тохируулах хувилбар

`composer run setup` ашиглахгүй бол дараах командуудыг дарааллаар ажиллуулна.

### Windows PowerShell

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File database/database.sqlite -Force | Out-Null
php artisan migrate --seed
npm.cmd install
npm.cmd run build
php artisan storage:link
composer run dev
```

### macOS / Linux

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
composer run dev
```

## Тест ажиллуулах

```bash
composer run test
```

## Database-ийг шинээр эхлүүлэх

Доорх команд бүх хүснэгтийн өгөгдлийг устгаад migration болон demo seed-ийг дахин үүсгэнэ:

```bash
php artisan migrate:fresh --seed
```

## Түгээмэл алдаа

### `php` эсвэл `composer` танигдахгүй байх

PHP болон Composer суусан эсэх, мөн тэдгээрийн зам системийн `PATH` хувьсагчид орсон эсэхийг шалгана. Windows дээр Laragon ашиглаж байгаа бол Laragon-ийн Terminal-оос командуудаа ажиллуулж болно.

### PowerShell дээр `npm.ps1 cannot be loaded` алдаа гарах

PowerShell execution policy-г өөрчлөх шаардлагагүй. `npm`-ийн оронд Windows command wrapper ашиглана:

```powershell
npm.cmd install
npm.cmd run build
```

### SQLite extension дутуу байх

`php.ini` файлд дараах extension-үүд идэвхтэй эсэхийг шалгаад terminal/server-ээ дахин асаана:

```ini
extension=pdo_sqlite
extension=sqlite3
```

### Тохиргоо өөрчилсний дараа хуучин утга ашиглагдах

```bash
php artisan optimize:clear
```
