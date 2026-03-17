# 🚀 Deploy Now Workshop

> **A hands-on deployment workshop.** You'll go from "just forked this repo" to a live URL in a browser in under 50 minutes — then learn how Docker makes that deployment reproducible and portable.

---

## 📋 Why This Workshop Exists

Deployment is one of the most important skills in software development, but it's often skipped or saved for "later." This workshop puts deployment **first**:

1. You get a **real, live URL** early
2. You make a change and watch it **auto-redeploy**
3. You learn how **Docker** makes deployments consistent across environments

The app itself is a tiny Node.js + Express server that serves a single HTML page.
You'll never need to understand Express to complete Parts 1 and 2 — just edit HTML.

---

## 🎯 What You'll Accomplish

| Phase | Time | Goal |
|-------|------|------|
| **Part 1 – Fast Deploy** | First 30 min of class | Your app is live at a public URL |
| **Part 2 – Iteration** | Last 20 min of class | You edit a file and watch it auto-redeploy |
| **Part 3 – Docker Path** | Lab (1h 50m) | You run the app locally in Docker and understand how containers work |

---

## 🗺️ Choose Your Path

```
┌────────────────────────────────────────────────────────────────────┐
│  PART 1 + 2  (Required — In-Class, 50 min)                         │
│  Fork → Deploy to Render → Edit public/index.html → Push → See live│
├────────────────────────────────────────────────────────────────────┤
│  PART 3  (Advanced — Lab, 1h 50m)                                  │
│  Run with Docker locally → Read the Dockerfile → Apply to your     │
│  own project                                                        │
└────────────────────────────────────────────────────────────────────┘
```

> **Stuck?** See [Common Issues](#-common-issues) below or ask the instructor.

---

## ⏱️ Class Schedule

### In-Class (50 minutes)

| Time | Activity |
|------|----------|
| 0:00 – 0:05 | Overview: what is deployment? why does it matter? |
| 0:05 – 0:15 | Fork this repo, create a Render account |
| 0:15 – 0:30 | Connect repo to Render, trigger first deploy |
| 0:30 – 0:35 | ✅ Checkpoint: everyone opens their live URL |
| 0:35 – 0:45 | Edit `public/index.html`, push, watch auto-redeploy |
| 0:45 – 0:50 | ✅ Checkpoint: everyone sees their change live |

### Lab (1 hour 50 minutes)

| Time | Activity |
|------|----------|
| 0:00 – 0:15 | Review: what just happened? What is a web server? |
| 0:15 – 0:45 | Part 3: Run the app locally with Docker |
| 0:45 – 1:10 | Read the Dockerfile — understand layers, images, containers |
| 1:10 – 1:40 | Extension: apply Docker thinking to your own project |
| 1:40 – 1:50 | Wrap-up and Q&A |

---

## ✅ Part 1 — Fast Deploy (Required)

> **Goal:** Get your app live at a public URL before the 50-minute class ends.
>
> 📄 Full step-by-step: [docs/FAST_PATH.md](docs/FAST_PATH.md)

### Quick Steps

**1 — Fork this repo**

Click the **Fork** button (top-right of this page). This creates your own copy.

**2 — Create a Render account**

Go to [render.com](https://render.com) and sign up with GitHub (free tier available — check render.com for current account requirements).

**3 — Create a New Web Service**

In the Render dashboard: **New + → Web Service → connect your fork**

**4 — Configure the service**

| Setting | Value |
|---------|-------|
| Runtime | **Docker** |
| Branch | `main` |
| Instance Type | **Free** |

No environment variables are required for this app. Click **Create Web Service**.

**5 — Wait for the deploy**

Watch the build log. When you see "Your service is live 🎉", click the URL.

### ✅ Part 1 Checkpoint

> Your app is live at `https://your-app.onrender.com`. You can see the welcome page.

---

## 🔄 Part 2 — Iteration (Required)

> **Goal:** Make a visible change and watch it auto-redeploy.

**1 — Open `public/index.html`**

This is the only file you need to edit. Open it in your editor or directly on GitHub.

Find the clearly marked section:

```html
<!-- ================================================================
     👋 STUDENT EDIT ZONE — CHANGE THIS SECTION!
     ...
     ================================================================ -->
```

**2 — Change your name and tagline**

```html
<h1>👋 Hello from <strong>Ada Lovelace</strong>!</h1>
<p class="tagline">I just deployed something! 🚀</p>
```

You can also change the banner color — look for `/* ✏️ CHANGE THIS COLOR */` in the `<style>` block.

**3 — Commit and push**

```bash
git add public/index.html
git commit -m "feat: update welcome message"
git push
```

Or use the GitHub web editor (pencil icon on the file page).

**4 — Watch Render redeploy**

Render detects the push automatically and starts a new build. When it finishes (~1–2 min), refresh your URL.

### ✅ Part 2 Checkpoint

> Your name is live at your Render URL. Auto-deploy works.

---

## �� Part 3 — Advanced Docker Path (Lab)

> **Goal:** Run the app locally in Docker, understand the Dockerfile, connect this to your own projects.
>
> 📄 Full step-by-step: [docs/DOCKER_PATH.md](docs/DOCKER_PATH.md)

### Prerequisites

- Docker Desktop: [docker.com/get-started](https://www.docker.com/get-started)
- Or use GitHub Codespaces (`.devcontainer` is already configured)

### Quick Steps

```bash
# Clone your fork
git clone https://github.com/YOUR_USERNAME/deploy-now-workshop.git
cd deploy-now-workshop

# Build and start the container
docker compose -f compose.local.yml up --build

# Open in browser
open http://localhost:8080
```

Read the `Dockerfile` — it's heavily commented and explains every decision.

### ✅ Part 3 Checkpoint

> Your app runs in Docker locally. You understand the two key ideas:
> 1. The same Dockerfile runs everywhere (local, Render, AWS, etc.)
> 2. Docker caches layers — changing code doesn't re-install dependencies

---

## 🔧 Common Issues

### "Deploy failed on Render"

Check the **Deploy Logs** in Render for the specific error. Common causes:
- Dockerfile syntax error (look for the failing line in the log)
- Port mismatch (server.js uses `PORT` env var, Render sets it automatically)

### "I pushed but Render didn't redeploy"

- Make sure you pushed to the `main` branch
- Check Render dashboard → your service → "Auto-Deploy" is enabled
- Hard-refresh: `Ctrl+Shift+R` / `Cmd+Shift+R`

### "My change isn't showing up"

- Did you edit `public/index.html` (not another file)?
- Did you commit and push? (`git status` should show nothing pending)
- Is the Render deploy complete? (Check the dashboard)

### "Docker Compose fails locally"

- Make sure Docker Desktop is running (check your taskbar/menu bar)
- Run `docker compose -f compose.local.yml down` first, then `up --build`
- Port conflict: change `8080:8080` to `8081:8080` in `compose.local.yml`

### "Render free tier is very slow"

- Free tier instances sleep after 15 minutes of inactivity
- First request after sleep takes 30–60 seconds — that's normal
- Tell students to expect this; it's not broken

---

## 👩‍🏫 Instructor Notes

> Full guide: [docs/INSTRUCTOR_NOTES.md](docs/INSTRUCTOR_NOTES.md)

**Pre-class essentials:**
- [ ] Do a dry run — fork, deploy to Render, confirm it works
- [ ] Have the live URL open on the projector at the start of class
- [ ] No secrets or pre-assigned values needed — each student deploys independently

**The key demo move:** Open `public/index.html` on GitHub, edit it in the browser, commit, then switch to the Render dashboard and show the build log starting. Students find this very satisfying.

---

## 🧩 Extension Ideas

For students who finish early:

- **New page:** Add `public/about.html` and link to it from `index.html`
- **New route:** Edit `server.js` to add an API endpoint (e.g., `/api/hello`)
- **Environment variable:** Add a `GREETING` env var in Render and display it on the page
- **GitHub Actions:** The included `.github/workflows/ci.yml` is already set up — push something and watch it run
- **Adapt for your project:** Draft a `Dockerfile` for your own class project

---

## 📁 Repository Structure

```
deploy-now-workshop/
├── README.md                    # This file — workshop overview
├── server.js                    # Tiny Express server (serves /public)
├── package.json                 # Node.js dependencies (just Express)
├── Dockerfile                   # Container image definition (annotated)
├── compose.local.yml            # Local Docker Compose config (annotated)
├── render.yaml                  # Render deployment blueprint (optional)
├── public/
│   └── index.html               # ← STUDENTS EDIT THIS FILE FIRST
├── docs/
│   ├── FAST_PATH.md             # Detailed Part 1 & 2 walkthrough
│   ├── DOCKER_PATH.md           # Detailed Part 3 Docker walkthrough
│   └── INSTRUCTOR_NOTES.md     # Facilitator guide
└── .github/
    └── workflows/
        └── ci.yml               # CI pipeline (advanced extension)
```

---

*Designed for reuse semester after semester. See [docs/INSTRUCTOR_NOTES.md](docs/INSTRUCTOR_NOTES.md) for customization tips.*
