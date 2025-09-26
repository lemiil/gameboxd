# Gameboxd

**Gameboxd** is a social platform for gamers — inspired by Letterboxd, but for video games. Users can track the games they’ve played, write reviews, create collections, follow friends, and maintain a personal gaming journal.

---

## Tech Stack

- Backend: Laravel
- Frontend: Vue + Inertia
- Database: PostgreSQL
- Cache: Redis
- API: REST
- Auth: OAuth

---

## Features

- Add and log games
- Rate and review titles
- Create lists and collections
- Follow other users
- Discover new games
- Profile pages and stats

---

## Installation (Docker)

1. Clone the repository:

```bash
git clone https://github.com/lemiil/gameboxd.git
cd gameboxd
```

2. Set up the env. file

3. Build and start the containers
```
docker-compose up --build -d
```
4. Run
```
docker-compose exec app composer install

docker-compose exec app php artisan migrate --seed
```
