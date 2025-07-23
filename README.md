# 🪙 ForMeatFactory

A simple api based on DDD Architecture

---

## 📦 Tech Stack

- **Backend**: PHP 8.2 (Symfony)
- **Database**: PostgreSQL 15
- **Cache**: Redis
- **Orchestration**: Docker + Docker Compose

---

## 🚀 Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/AcidWalker123/ForMeatFactory.git
cd ForMeatFactory
```

### 2. Install dependencies

```bash
composer install
```

### 2. Generate private keys, for secret phrase use "doggo"

```bash
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

### 3. Create env.local

```bash
cp .env .env.local
```

### 4. Paste passphrase in env.local

```bash
JWT_PASSPHRASE=doggo
```

### 5. Run databases

```bash
docker-compose up -d
```

### 5. Run Migrations

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Seed database with premade test data

```bash
php bin/console app:create-products
```

### 6. Start project (to download symfony cli view https://symfony.com/download)
```bash
symfony server:start
```

### 7. Api works at http://localhost:8000 
