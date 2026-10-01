# Vercel deployment

Төсөл нь Vercel-ийн санал болгодог `vercel-php` community runtime ашиглана. Vercel-ийн filesystem нь request хооронд хадгалагддаггүй тул production өгөгдлийн сан заавал гаднын persistent database байна.

## 1. Database үүсгэх

Vercel Marketplace-аас Neon Postgres зэрэг database холбоно. Integration нь `DATABASE_URL`, `POSTGRES_URL`, эсвэл `DB_URL` үүсгэсэн байхад апп URL-ийн scheme-ээс driver-ийг автоматаар сонгоно. Тусдаа утгаар тохируулах бол:

```text
DB_CONNECTION=pgsql
DB_URL=postgresql://USER:PASSWORD@HOST:5432/DATABASE?sslmode=require
```

MySQL URL мөн дэмжигдэнэ (`mysql://...`). SQLite-ийг зөвхөн local development-д ашиглана.

## 2. Vercel environment variables

Project Settings → Environment Variables хэсэгт дор хаяж дараах утгуудыг нэмнэ:

```text
APP_NAME=Call System
APP_KEY=base64:...
```

`APP_KEY`-г local PHP орчинд нэг удаа үүсгэнэ:

```bash
php artisan key:generate --show
```

Database integration URL дээрх гурван нэрийн аль нэгээр автоматаар орж ирээгүй бол `DB_URL`-г мөн нэмнэ. `vercel.json` нь production-д тохирсон cookie session, memory cache, stderr log, sync queue болон `/tmp` cache/view замуудыг аль хэдийн тохируулсан.

## 3. Migration ажиллуулах

Анхны deploy-оос өмнө эсвэл дараа нь production database дээр migration ажиллуулна. Vercel-ийн env-ийг local руу татаж ажиллуулах хамгийн найдвартай хувилбар:

```bash
npx vercel link
npx vercel env pull .env.vercel.local
php artisan migrate --force --env=vercel.local
```

Demo хэрэглэгчид хэрэгтэй бол зөвхөн тест/demo database дээр:

```bash
php artisan db:seed --force --env=vercel.local
```

Seeder-ийн нууц үгүүд нийтэд ил байгаа тул бодит production орчинд seed хийхгүй.

## 4. Import ба deploy

GitHub repository-оо Vercel-ийн **Add New → Project** хэсгээс import хийнэ. Framework Preset-ийг **Other**, Root Directory-г repository root хэвээр үлдээгээд Deploy дарна. `vercel-php` Composer dependency-г, Composer-ийн `vercel` script frontend asset-ийг build хийнэ.

CLI ашиглавал:

```bash
npx vercel
npx vercel --prod
```

## Serverless хязгаарлалт

- `storage/` доторх local upload тогтвортой хадгалагдахгүй. Зураг upload-ыг production-д ашиглах бол S3-compatible object storage болон Laravel Flysystem S3 adapter нэмэх шаардлагатай.
- Queue нь `sync`, session нь encrypted cookie, cache нь request-local memory ашиглана.
- PDF/CSV export нь request timeout-аас урт ажиллаж болохгүй.
