# 🚀 Deploy Now Workshop

> **A hands-on deployment workshop.** You'll go from "just forked this repo" to "live URL in a browser" in under 50 minutes — then learn how Docker makes that deployment reproducible and portable.

---

## 📋 Why This Workshop Exists

Deployment is one of the most important skills in software development, but it's often skipped or saved for "later." This workshop puts deployment **first**:

1. You get a **real, live URL** early
2. You make a change and watch it **auto-redeploy**
3. You learn how **Docker** makes deployments consistent and reproducible

---

## 🎯 What You'll Accomplish

| Phase | Time | Goal |
|-------|------|------|
| **Part 1 – Fast Deploy** | First 30 min of class | Your app is live at a public URL |
| **Part 2 – Iteration** | Last 20 min of class | You make a change, it redeploys automatically |
| **Part 3 – Docker Path** | Lab (1h 50m) | You run the app in Docker locally and understand how production works |

---

## 🗺️ Choose Your Path

```
┌─────────────────────────────────────────────────────────────────┐
│  PART 1 + 2 (Required — In-Class, 50 min)                       │
│  Fork → Deploy to Render → Edit welcome page → Push → See live  │
├─────────────────────────────────────────────────────────────────┤
│  PART 3 (Advanced — Lab, 1h 50m)                                │
│  Run with Docker locally → Understand Dockerfile → Connect       │
│  the container story to your real project                        │
└─────────────────────────────────────────────────────────────────┘
```

> **Stuck? See [Common Issues](#-common-issues) below or ask the instructor.**

---

## ⏱️ Class Schedule

### In-Class (50 minutes)

| Time | Activity |
|------|----------|
| 0:00 – 0:05 | Overview: what is deployment? why does it matter? |
| 0:05 – 0:15 | Fork this repo, create Render account |
| 0:15 – 0:30 | Connect repo to Render, trigger first deploy |
| 0:30 – 0:35 | ✅ Checkpoint: everyone opens their live URL |
| 0:35 – 0:45 | Edit `welcome.blade.php`, push, watch auto-redeploy |
| 0:45 – 0:50 | ✅ Checkpoint: everyone sees their change live |

### Lab (1 hour 50 minutes)

| Time | Activity |
|------|----------|
| 0:00 – 0:15 | Review: what just happened? What is a web server? |
| 0:15 – 0:45 | Part 3: Run the app locally with Docker |
| 0:45 – 1:10 | Read the Dockerfile — understand layers, builds, ENV |
| 1:10 – 1:40 | Extension: apply Docker thinking to your own project |
| 1:40 – 1:50 | Wrap-up and Q&A |

---

## ✅ Part 1 — Fast Deploy (Required)

> **Goal:** Get your app live at a public URL before the 50-minute class ends.
>
> **For detailed step-by-step instructions, see [docs/FAST_PATH.md](docs/FAST_PATH.md)**

### Quick Steps

**Step 1 — Fork this repo**

Click the **Fork** button at the top right of this page. This creates your own copy of the repo under your GitHub account.

**Step 2 — Create a Render account**

Go to [render.com](https://render.com) and sign up with your GitHub account (free tier is fine).

**Step 3 — Connect your repo to Render**

1. In Render dashboard, click **New → Web Service**
2. Connect your GitHub account if prompted
3. Select your forked repo
4. Render will detect the `Dockerfile` automatically

**Step 4 — Configure the web service**

Use these settings:

| Setting | Value |
|---------|-------|
| Name | `your-name-deploy-workshop` (anything unique) |
| Region | Ohio (or closest to you) |
| Branch | `main` |
| Runtime | **Docker** |
| Instance Type | **Free** |

Add these environment variables (click **Advanced → Add Environment Variable**):

| Key | Value |
|-----|-------|
| `APP_ENV` | `production` |
| `APP_KEY` | Ask your instructor for a pre-generated key |

**Step 5 — Deploy**

Click **Create Web Service**. Render will build and deploy your app.

Watch the deploy log. When you see **"Your service is live"**, click the URL at the top.

### ✅ Part 1 Success Checkpoint

> Your app is live. You can see the **Deploy Now Workshop** welcome page at `https://your-app.onrender.com`

---

## 🔄 Part 2 — Iteration (Required)

> **Goal:** Make a visible change and watch it auto-redeploy.

**Step 1 — Open the student welcome file**

Open `resources/views/welcome.blade.php` in your editor (or directly on GitHub).

Look for this clearly marked section near the top:

```
👋 STUDENT EDIT ZONE — CHANGE SOMETHING HERE!
```

**Step 2 — Make a visible change**

Change the name, message, or color in the marked section. For example:

```html
<p class="student-message">Hello from Ada Lovelace! 🚀</p>
```

**Step 3 — Push your change**

```bash
git add .
git commit -m "feat: update welcome message"
git push
```

Or edit directly on GitHub (click the pencil icon on the file).

**Step 4 — Watch Render redeploy**

Go to your Render dashboard. You'll see a new deploy start automatically. When it finishes, refresh your URL.

### ✅ Part 2 Success Checkpoint

> You see **your change live** at your Render URL. The auto-deploy loop works.

---

## 🐳 Part 3 — Advanced Docker Path (Lab)

> **Goal:** Run the app locally in Docker, understand how the Dockerfile works, and connect this to your own projects.
>
> **For detailed step-by-step instructions, see [docs/DOCKER_PATH.md](docs/DOCKER_PATH.md)**

### Prerequisites

- Docker Desktop installed: [docker.com/get-started](https://www.docker.com/get-started)
- Git cloned locally (or use GitHub Codespaces — a `.devcontainer` is included)

### Quick Steps

**Step 1 — Clone your fork locally**

```bash
git clone https://github.com/YOUR_USERNAME/deploy-now-workshop.git
cd deploy-now-workshop
```

**Step 2 — Run with Docker Compose**

```bash
docker compose -f compose.local.yml up --build
```

This builds the Docker image and starts the container.

**Step 3 — Open the app**

Visit [http://localhost:8080](http://localhost:8080) — the same app you deployed to Render is now running on your machine.

**Step 4 — Read the Dockerfile**

Open `Dockerfile` and read the comments. Notice:

- The **build stage** installs PHP and Composer dependencies
- The **runtime stage** copies only what's needed to run
- Environment variables control behavior (`APP_ENV`, `APP_KEY`)
- The app is served on port `8080`

**Step 5 — Make a change and rebuild**

Edit `resources/views/welcome.blade.php`, then rebuild:

```bash
docker compose -f compose.local.yml up --build
```

### ✅ Part 3 Success Checkpoint

> Your app runs locally in Docker. You understand the two-stage build. You can explain why Docker helps with deployment consistency.

---

## 🔧 Common Issues

### "My deploy failed on Render"

1. Check the **Deploy Logs** in Render dashboard for the exact error
2. Most common fix: make sure `APP_KEY` is set in your environment variables
3. If you see a 500 error, check that `APP_ENV=production` is set

### "I can't see my change after pushing"

1. Check Render dashboard — is a new deploy running or queued?
2. Make sure you pushed to the `main` branch (not a different branch)
3. Hard-refresh your browser: `Ctrl+Shift+R` or `Cmd+Shift+R`

### "Docker compose fails locally"

1. Make sure Docker Desktop is running
2. Try: `docker compose -f compose.local.yml down` first, then `up --build`
3. Check that port 8080 is not in use: `lsof -i :8080`

### "I get 'No application encryption key has been specified'"

- Set `APP_KEY` in Render environment variables
- The key format is: `base64:` followed by a base64-encoded 32-byte string
- Ask your instructor for a pre-generated key

### "The app loads but shows a blank page"

- This is usually a JavaScript/Vite asset issue — safe to ignore for this workshop
- The important content (the welcome page) loads without the compiled assets

### "Render free tier is slow"

- Free tier instances sleep after 15 minutes of inactivity
- First request after sleep takes 30–60 seconds — this is expected
- Upgrade to Starter tier if you need always-on behavior

---

## 👩‍🏫 Instructor Notes

> **See [docs/INSTRUCTOR_NOTES.md](docs/INSTRUCTOR_NOTES.md) for full setup and facilitation guide.**

### Pre-Class Checklist

- [ ] Fork this repo (or use as template)
- [ ] Have a pre-generated `APP_KEY` ready to share with students
- [ ] Create a demo Render deployment to walk through live on the projector
- [ ] Know what URL pattern students will see (`*.onrender.com`)

### Suggested Demo Flow (First 15 minutes)

1. Show the repo structure (2 min)
2. Fork live on the projector (1 min)
3. Create Render account and web service (5 min)
4. Point out the "STUDENT EDIT ZONE" in `welcome.blade.php` (2 min)
5. Let students take over while you circulate (rest of class)

---

## 🧩 Extension Ideas

For students who finish early or want more challenge:

- **Add a new route**: Create a new page in `routes/web.php` and a new view
- **Environment variables**: Add a custom env var and display it on the page
- **CI/CD with GitHub Actions**: Add a `.github/workflows/deploy.yml` to automate testing on push
- **Multi-stage Docker optimization**: Reduce image size by removing build-time deps
- **Apply to your own project**: Adapt the Dockerfile for a Node.js, Python, or other language project

---

## 📁 Repository Structure

```
deploy-now-workshop/
├── README.md                    # This file — workshop guide
├── Dockerfile                   # Production Docker image (annotated for teaching)
├── compose.local.yml            # Local development with Docker Compose
├── docs/
│   ├── FAST_PATH.md             # Detailed Part 1 & 2 instructions
│   ├── DOCKER_PATH.md           # Detailed Part 3 Docker instructions
│   └── INSTRUCTOR_NOTES.md     # Full instructor facilitation guide
├── resources/
│   └── views/
│       └── welcome.blade.php    # STUDENTS EDIT THIS FILE FIRST
├── routes/
│   └── web.php                  # URL routing (simple for this workshop)
└── ...                          # Standard Laravel structure (framework files)
```

---

*This workshop is designed to be reused semester after semester. If you are an instructor adapting it, see [docs/INSTRUCTOR_NOTES.md](docs/INSTRUCTOR_NOTES.md).*
