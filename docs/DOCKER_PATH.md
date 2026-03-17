# 🐳 Docker Path — Part 3

> **This is the advanced lab section.** Complete Parts 1 and 2 first (deploy to Render, make a change).
>
> **Goal:** Run the app locally in Docker, understand how containers work, and connect this knowledge to your own projects.

---

## Why Docker?

You already deployed this app to Render. Render used your `Dockerfile` to build and run it.

But what if you want to:
- Run the app on your laptop during development?
- Deploy to a different platform (AWS, GCP, DigitalOcean, Fly.io)?
- Share an exact working copy of the app with a teammate?
- Test changes before pushing to production?

Docker solves all of these by packaging the app and its dependencies into a **container** — a portable, reproducible environment that runs the same everywhere.

**Key idea:** The same `Dockerfile` that Render used to build your production app is what you'll use here to run it locally. This is *environment consistency* — the whole point of containers.

---

## Prerequisites

### Install Docker Desktop

- **Mac:** [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop)
- **Windows:** [docker.com/products/docker-desktop](https://www.docker.com/products/docker-desktop) (requires WSL2)
- **Linux:** `sudo apt-get install docker.io docker-compose-plugin`

Verify the install:
```bash
docker --version
docker compose version
```

### Alternative: GitHub Codespaces

Don't want to install Docker locally? Open your fork in Codespaces:
1. Click the **Code** button on your repo page
2. Click **Codespaces → Create codespace on main**
3. The `.devcontainer` config already includes Docker-in-Docker support

---

## Step 1 — Clone Your Fork

```bash
git clone https://github.com/YOUR_USERNAME/deploy-now-workshop.git
cd deploy-now-workshop
```

---

## Step 2 — Understand the Files Before Running

Open these two files and read the comments before running anything.

### `Dockerfile`

This is the **recipe** for building the container image. Notice:

**Layer caching (the key optimization):**
```dockerfile
# Copy package files FIRST
COPY package*.json ./
RUN npm ci --omit=dev

# THEN copy the rest of the app
COPY . .
```
Why this order? Docker caches each layer. If `package.json` hasn't changed, Docker skips the `npm ci` step entirely on the next build. If you copied everything first, ANY file change would bust the npm cache and re-run install.

**The final command:**
```dockerfile
CMD ["npm", "start"]
```
This is what runs when the container starts. It maps to `node server.js` via the `start` script in `package.json`.

### `compose.local.yml`

This tells Docker Compose how to run the image locally:
- `build: .` — build from the local Dockerfile
- `ports: "8080:8080"` — forward your laptop's port 8080 to the container's port 8080
- `environment: PORT=8080` — tell the app which port to listen on

---

## Step 3 — Build and Run

```bash
docker compose -f compose.local.yml up --build
```

What happens:
1. Docker reads the `Dockerfile` and builds an image (~1–2 min, faster on rebuilds)
2. Docker starts a container from that image
3. The app starts serving on port 8080

You'll see output ending with:
```
app-1  | Workshop app running → http://localhost:8080
```

---

## Step 4 — Open the App

Visit: **[http://localhost:8080](http://localhost:8080)**

You should see the same welcome page you deployed to Render. **Same app. Same container. Different environment.**

---

## Step 5 — Make a Change and See It Locally

1. Edit `public/index.html` (change the name or color)
2. Stop the running containers: `Ctrl+C`
3. Rebuild and restart:
   ```bash
   docker compose -f compose.local.yml up --build
   ```
4. Refresh [http://localhost:8080](http://localhost:8080)

> **Why do you have to rebuild?** The files are copied into the image at build time. To see changes, you need a new image. (In a real dev setup you'd mount a volume so changes appear live — see Challenge 2 below.)

---

## Step 6 — Explore the Running Container

While the app is running, open a **new terminal tab** and try:

```bash
# List all running containers
docker ps

# Open a shell inside the container
docker exec -it deploy-now-workshop-app-1 sh
ls /app              # your app files are here
cat /app/server.js   # view server.js from inside the container
exit

# View the container's logs
docker compose -f compose.local.yml logs -f

# Stop all containers defined in this file
docker compose -f compose.local.yml down
```

---

## Step 7 — The Full Deployment Picture

Here's how your local Docker setup maps to Render:

```
Your Laptop                            Render (Production)
───────────────                        ───────────────────────
docker compose up --build              git push → Render webhook fires
        ↓                                      ↓
Reads Dockerfile                       Reads same Dockerfile
        ↓                                      ↓
npm ci --omit=dev                      npm ci --omit=dev
        ↓                                      ↓
Starts container                       Starts container
        ↓                                      ↓
http://localhost:8080                  https://yourapp.onrender.com
```

**Same Dockerfile. Same process. Different infrastructure.** That's why containers matter.

---

## Key Concepts

### Image vs Container

| Concept | Analogy | In practice |
|---------|---------|-------------|
| **Image** | A recipe / blueprint | Built from Dockerfile, stored on disk |
| **Container** | A running meal / result | A live process from the image |

You can run many containers from one image.

### Layer Caching

Every `RUN`, `COPY`, and `ADD` in a Dockerfile creates a cached layer. Docker only re-runs a layer when that layer (or something above it) changes. This is why ordering matters.

### Port Mapping

```
8080:8080
 │      └── port INSIDE the container (what server.js listens on)
 └───────── port on YOUR LAPTOP (what you visit in the browser)
```

Change the left number to use a different local port.

### Environment Variables

The app reads `PORT` from the environment:
```javascript
const PORT = process.env.PORT || 8080;
```
Render sets `PORT` automatically. Docker Compose sets it in `compose.local.yml`. The code doesn't care — it just reads the variable.

---

## Extension Challenges

### Challenge 1 — Volume Mount (Live Reload)

Add a volume mount so HTML changes appear without rebuilding:

```yaml
# In compose.local.yml, under the app service:
volumes:
  - ./public:/app/public
```

Now edit `public/index.html` and refresh the browser — no rebuild needed. Why does this work for HTML but not for `server.js`?

### Challenge 2 — Smaller Image

The current image uses `node:20-alpine`. Alpine Linux is already tiny (~5 MB base). But can you reduce the image size further by removing something that isn't needed at runtime?

Check the current size:
```bash
docker images deploy-now-workshop
```

### Challenge 3 — Add an API Endpoint

Edit `server.js` to add a new route:
```javascript
app.get('/api/hello', (req, res) => {
  res.json({ message: 'Hello from the API!', timestamp: new Date() });
});
```

Rebuild and visit [http://localhost:8080/api/hello](http://localhost:8080/api/hello). How would you display this data on the HTML page?

### Challenge 4 — Apply to Your Own Project

Think about your current class project:
1. What language/runtime does it use?
2. What base image would you use? ([hub.docker.com](https://hub.docker.com) — search for your language)
3. What commands install its dependencies?
4. What command starts the server?
5. What port does it use?

Draft a `Dockerfile` for it using the same structure as this one.

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| `docker: command not found` | Docker Desktop is not installed or not running |
| `Cannot connect to the Docker daemon` | Open Docker Desktop and wait for it to show "running" |
| Port 8080 already in use | Change `8080:8080` → `8081:8080` in `compose.local.yml`, visit `localhost:8081` |
| Container exits immediately | Run `docker compose -f compose.local.yml logs` to see the error |
| Changes not appearing | Rebuild: `docker compose -f compose.local.yml up --build` |
| Build is very slow | First build downloads the base image — subsequent builds use the cache |
