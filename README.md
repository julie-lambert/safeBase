# 🛡️ SafeBase – Plateforme de sauvegarde et restauration de bases de données

SafeBase est une application Laravel sécurisée permettant de **sauvegarder** et **restaurer** des bases de données **MySQL** et **PostgreSQL**, via une interface web intuitive. Elle prend en charge l’**authentification**, les **rôles utilisateurs**, la **gestion des connexions**, et un historique détaillé des actions.

---

## 🚀 Fonctionnalités principales

- 🔐 Authentification avec rôles (admin / utilisateur)
- 💾 Sauvegarde des bases MySQL/PostgreSQL (via `mysqldump` / `pg_dump`)
- 🔁 Restauration d’un fichier de sauvegarde
- 📁 Historique des sauvegardes par base de données
- ⏱️ Tests automatisés (PHPUnit)
- 🐳 Docker et CI/CD via GitHub Actions

---

## 📦 Installation

### Prérequis

- Docker + Docker Compose
- Git
- (facultatif pour dev local) PHP 8.2, Composer, Node.js

### 📁 Cloner le dépôt

```bash
git clone https://github.com/julie-lambert/safebase.git
cd safebase
```

### 🐳 Lancer avec Docker

```bash
docker-compose up --build -d
```

Cela démarre :
- Laravel (port `8010`)
- MySQL (port `3307`)
- PhpMyAdmin (port `8081`)

### 🔑 Accès à l’application

- Interface : http://localhost:8010  
- PhpMyAdmin : http://localhost:8081  
  (Hôte : `mysql`, Utilisateur : `root`, Mot de passe : vide)

---

## 🧪 Exécuter les tests

```bash
docker exec -it safebase-app php artisan test
```

Ou, en local :

```bash
php artisan test
```

---

## 🛠️ Sauvegarder une base de données

1. Connectez-vous à SafeBase
2. Allez dans **"Connexions"** et ajoutez une nouvelle base (MySQL/PostgreSQL)
3. Cliquez sur **"Sauvegarder"** pour lancer un dump
4. Téléchargez le fichier SQL généré depuis la section **"Historique"**

---

## ♻️ Restaurer une base de données

1. Cliquez sur une sauvegarde dans l’historique
2. Cliquez sur **"Restaurer"**
3. Le fichier SQL sera injecté automatiquement dans la base sélectionnée

ℹ️ Fonctionne uniquement pour MySQL actuellement

---

## 🔄 CI/CD – Intégration continue

SafeBase est équipé d’un pipeline GitHub Actions :

- **Tests automatiques** à chaque push
- **Build des assets**
- **Création d’une image Docker**
- **Push vers GitHub Container Registry (GHCR)**

📦 Pull de l’image :

```bash
docker pull ghcr.io/julie-lambert/safebase:latest
```

---

## 🔐 Sécurité

- Clé d’application (`APP_KEY`) générée automatiquement
- Fichiers `.env` non versionnés
- Dumps stockés dans `storage/app/backups` (hors `public`)
- Accès aux fichiers contrôlé via Policies Laravel

---

## 👤 Accès admin par défaut

| Email                | Mot de passe |
|---------------------|--------------|
| admin@safebase.app  | password     |

---

## 📁 Structure du projet

```
├── app/
├── database/
├── public/
├── routes/
├── tests/
├── Dockerfile
├── docker-compose.yml
└── .github/workflows/ci-cd.yml
```

---

## 📜 Licence

Ce projet est sous licence MIT.  
© Julie Lambert – 2025.

---

## 💬 Contact

Pour toute suggestion ou bug, merci de créer une **issue** sur [le dépôt GitHub](https://github.com/julie-lambert/safebase/issues).
