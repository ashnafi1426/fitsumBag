# Supabase PostgreSQL Migration

This directory has been configured to use **Supabase PostgreSQL** instead of MySQL.

## 🚀 Quick Start

### Automated Setup (Recommended)

**Windows:**
```bash
setup-supabase.bat
```

**Linux/Mac:**
```bash
chmod +x setup-supabase.sh
./setup-supabase.sh
```

### Manual Setup

1. **Check your environment:**
   ```bash
   php check-pgsql.php
   ```

2. **Update `.env` with your Supabase credentials:**
   ```env
   DB_CONNECTION=pgsql
   DB_HOST=db.xxxxxxxxxxxxx.supabase.co
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres
   DB_PASSWORD=your-password
   DB_SSLMODE=require
   ```

3. **Clear cache and migrate:**
   ```bash
   php artisan config:clear
   php artisan migrate:fresh --seed
   ```

## 📚 Documentation

| File | Description |
|------|-------------|
| `../MIGRATION_SUMMARY.md` | Overview of changes made |
| `../SUPABASE_SETUP.md` | Complete Supabase setup guide |
| `../DATABASE_MIGRATION.md` | Data migration instructions |
| `../SUPABASE_QUICK_REFERENCE.md` | Quick reference card |

## 🛠️ Tools

| File | Purpose |
|------|---------|
| `check-pgsql.php` | Check PostgreSQL configuration |
| `setup-supabase.bat` | Automated setup (Windows) |
| `setup-supabase.sh` | Automated setup (Linux/Mac) |

## ✅ What Changed

- `.env` → PostgreSQL configuration
- `.env.production` → PostgreSQL configuration
- `.env.example` → PostgreSQL template

**No code changes needed!** Laravel handles MySQL → PostgreSQL differences automatically.

## 🆘 Troubleshooting

**Connection issues?**
```bash
php check-pgsql.php
```

**Migration issues?**
```bash
php artisan migrate:status
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

## 📖 Learn More

- [Supabase Documentation](https://supabase.com/docs)
- [Laravel Database Docs](https://laravel.com/docs/database)

---

**Need help?** Check the documentation files in the parent directory.
