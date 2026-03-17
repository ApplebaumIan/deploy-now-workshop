# 🐳 Docker Path — Part 3

> **This is the advanced lab section.** You should have already completed Parts 1 and 2 (deployed to Render and made a visible change).
>
> Goal: Run the app locally in Docker, understand how containers work, and connect this to your own projects.

---

## Why Docker?

You've already deployed this app to Render. Render used your `Dockerfile` to build and run the app.

But what if you want to:
- Run it on your laptop for development?
- Deploy to a different platform (AWS, GCP, DigitalOcean)?
- Share an exact copy of the app with someone else?
- Test changes before pushing to production?

Docker solves all of these by packaging the app and its dependencies into a **container** — a portable, reproducible environment.

**Key idea:** The same `Dockerfile` that Render used to build your production app is also used here to run it locally. Consistency across environments.

---

## Prerequisites

### Install Docker Desktop

- **Mac:** [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)
- **Windows:** [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop) (requires WSL2)
- **Linux:** `sudo apt-get install docker.io docker-compose-plugin`

Verify it's installed:
```bash
docker --version
docker compose version
```

### Option: Use GitHub Codespaces

If you'd rather not install Docker locally, open your fork in GitHub Codespaces:
1. Click the **Code** button on your repo
2. Click **Codespaces** → **Create codespace on main**
3. The `.devcontainer` config sets up PHP and Node for you

---

## Step 1 — Clone Your Fork (if not already done)

```bash
git clone https://github.com/YOUR_USERNAME/deploy-now-workshop.git
cd deploy-now-workshop
```

---

## Step 2 — Understand the Files

Before running anything, look at the two key files:

### `Dockerfile` (the recipe)

```
Open: Dockerfile
```

Notice the **two-stage build pattern**:

```dockerfile
# Stage 1: BUILD
# - Install PHP, Composer, and all dependencies
# - Run artisan commands to optimize the app
FROM php:8.2-cli AS builder
...

# Stage 2: RUNTIME
# - Start fresh with a minimal base image
# - Copy ONLY the built app from Stage 1
# - This keeps the final image small and clean
FROM php:8.2-cli
...
```

**Why two stages?**
- The build stage needs lots of tools (Composer, dev dependencies)
- The runtime stage only needs what's necessary to run the app
- This results in a smaller, more secure production image

### `compose.local.yml` (the local runner)

```
Open: compose.local.yml
```

This file tells Docker Compose:
- Build the image from the local `Dockerfile`
- Map port `8080` on your laptop to port `8080` in the container
- Restart automatically if the container crashes

---

## Step 3 — Build and Run

```bash
docker compose -f compose.local.yml up --build
```

What happens:
1. Docker reads the `Dockerfile` and builds an image (~2–4 minutes first time)
2. Docker starts a container from that image
3. The app starts serving on port 8080

You'll see output like:
```
[+] Building 45.2s (15/15) FINISHED
[+] Running 1/1
 ✔ Container deploy-now-workshop-app-1  Started
```

---

## Step 4 — Open the App

Visit: [http://localhost:8080](http://localhost:8080)

You should see the same welcome page you deployed to Render. **Same app. Same container. Different environment.**

---

## Step 5 — Make a Change and Rebuild

1. Edit `resources/views/welcome.blade.php` (change something in the STUDENT EDIT ZONE)
2. Stop the running containers: `Ctrl+C`
3. Rebuild and restart:

```bash
docker compose -f compose.local.yml up --build
```

4. Refresh [http://localhost:8080](http://localhost:8080) — your change is reflected

> **Notice:** You have to rebuild to see changes. This is because the files are copied into the image at build time, not mounted from your local filesystem. In a real development setup, you'd typically mount a volume so changes appear live without rebuilding.

---

## Step 6 — Explore the Running Container

While the app is running (in a separate terminal tab):

**List running containers:**
```bash
docker ps
```

**Look inside the container:**
```bash
docker exec -it deploy-now-workshop-app-1 /bin/bash
# Now you're inside the container!
ls /app          # your app files are here
exit             # back to your terminal
```

**View container logs:**
```bash
docker compose -f compose.local.yml logs
```

**Stop everything:**
```bash
docker compose -f compose.local.yml down
```

---

## Step 7 — Understand the Deployment Story

Here's how the local Docker setup connects to your Render deployment:

```
Local (your laptop)                    Production (Render)
──────────────────                     ─────────────────────
docker compose up --build              git push → Render builds
        ↓                                      ↓
Reads Dockerfile                       Reads same Dockerfile
        ↓                                      ↓
Builds image                           Builds image
        ↓                                      ↓
Runs container                         Runs container
        ↓                                      ↓
http://localhost:8080                  https://yourapp.onrender.com
```

**Same Dockerfile. Same process. Different infrastructure.**

This is the power of containers — you know the app works locally because it will work the same way in production.

---

## Step 8 — Understanding Environment Variables

Notice how the app behavior changes based on environment variables:

| Variable | Development | Production |
|----------|-------------|------------|
| `APP_ENV` | `local` | `production` |
| `APP_DEBUG` | `true` | `false` |
| `APP_KEY` | Generated at build time | Set in Render env vars |

In the `Dockerfile`, the app key is generated during build:
```dockerfile
RUN php artisan key:generate --ansi
```

In Render (production), you set `APP_KEY` explicitly as an environment variable. This is a security best practice — secrets should not be baked into images.

---

## 🔑 Key Concepts to Understand

### Docker Image vs Container

- **Image**: The recipe/blueprint (built from Dockerfile, stored on disk)
- **Container**: A running instance of an image (like a process)
- You can run many containers from one image

### Multi-Stage Builds

- Stage 1 ("builder"): Install everything needed to compile/build the app
- Stage 2 ("runtime"): Copy only the final output into a clean image
- Result: Smaller, faster, more secure production image

### Port Mapping

```
Port 8080 on your laptop → Port 8080 inside the container
   (host port)                   (container port)
```

Change `8080:8080` in `compose.local.yml` to use a different host port.

### Environment Variables

- Never hardcode secrets in your Dockerfile
- Use environment variables for: API keys, database passwords, app keys
- Render, AWS, and all major platforms support env vars

---

## Extension Challenges

### Challenge 1 — Smaller Image

The current Dockerfile's runtime stage still installs `unzip libonig-dev pkg-config`. Can you remove unnecessary packages from the runtime stage and verify the app still runs?

### Challenge 2 — Volume Mounting

Instead of rebuilding every time you change a file, add a volume mount in `compose.local.yml`:

```yaml
volumes:
  - ./resources:/app/resources
```

Now changes to view files appear without rebuilding. (Note: PHP files are served directly, but a cache clear may be needed.)

### Challenge 3 — Multi-Container

Add a database service to `compose.local.yml`. SQLite works for this workshop, but real apps need PostgreSQL or MySQL. What would that `compose.local.yml` look like?

### Challenge 4 — Apply to Your Own Project

Think about your current class project:
1. What language/runtime does it use?
2. What base Docker image would you use? (Check [hub.docker.com](https://hub.docker.com))
3. What commands does it need to install dependencies and start the server?
4. What environment variables does it need?

Draft a `Dockerfile` for your own project.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| `docker: command not found` | Docker not installed or not in PATH |
| Port 8080 already in use | Run `lsof -i :8080` to find what's using it, or change the port in compose.local.yml |
| Build fails with permission error | Try `sudo docker compose up` (Linux) or restart Docker Desktop |
| App shows 500 error locally | Check that `APP_KEY` is set; the Dockerfile generates one at build time |
| Container exits immediately | Run `docker compose -f compose.local.yml logs` to see the error |
| Changes not appearing | Make sure you rebuilt: `docker compose -f compose.local.yml up --build` |
