# 👩‍🏫 Instructor Notes

> These notes are for instructors running the Deploy Now Workshop. Students don't need to read this file.

---

## Workshop Overview

This workshop teaches deployment using a progressive complexity model:

1. **Part 1 (Fast Deploy, ~30 min):** Students fork and deploy to Render — just a Dockerfile and HTML
2. **Part 2 (Iteration, ~20 min):** Students edit `public/index.html` and watch it auto-redeploy
3. **Part 3 (Docker, lab 1h50m):** Students run the app locally in Docker and understand containers

**The core pedagogy:** Get students a live URL *first*. Then explain *why* things work the way they do.

---

## The App

The app is intentionally minimal:

- `server.js` — ~10 lines of Express code; serves the `public/` directory
- `public/index.html` — the only student-facing file; pure HTML
- `Dockerfile` — Node.js alpine, 7 instructions, heavily commented
- `compose.local.yml` — 2 services, commented

Students don't need to understand Node.js or Express to complete Parts 1 and 2. They just edit HTML.

---

## Pre-Class Setup Checklist

### Required

- [ ] **Do a full dry run** — fork, deploy to Render, confirm the URL works
  - Render's UI changes occasionally; doing this catches surprises before class
  - Takes ~5 minutes total

- [ ] **Have your demo URL open** on the projector before class starts
  - Shows students what the end state looks like immediately

- [ ] **Have a Render account ready to demo live**
  - The "connect GitHub → create web service" flow is what students most need to see

### Recommended

- [ ] Test the Docker local path: `docker compose -f compose.local.yml up --build`
- [ ] Prepare a screenshot of the Render dashboard in case you have connectivity issues
- [ ] Know the Render free-tier behavior: services sleep after 15 min idle, cold start ~30–60 sec

---

## Timing Guide

### In-Class (50 minutes)

| Time | Instructor Action | Student Action |
|------|-------------------|----------------|
| 0:00 – 0:05 | Frame the workshop: "deployment first, everything else second" | Listen |
| 0:05 – 0:10 | **Live demo:** fork this repo on screen | Students fork simultaneously |
| 0:10 – 0:20 | **Live demo:** Render account → New Web Service → connect repo → settings | Students follow along |
| 0:20 – 0:30 | Circulate and help; the instructor's deploy should be finishing | Waiting for first deploy |
| 0:30 – 0:35 | Ask 2–3 students to share their URLs aloud | Students open their live URL |
| 0:35 – 0:40 | Show `public/index.html` and point at the STUDENT EDIT ZONE | Students find the file |
| 0:40 – 0:48 | Circulate; point students to Render dashboard to watch the build | Students edit, push, watch redeploy |
| 0:48 – 0:50 | Debrief: "what just happened?" — frame the deployment loop | Students confirm change is live |

### Lab (1 hour 50 minutes)

| Time | Instructor Action | Student Action |
|------|-------------------|----------------|
| 0:00 – 0:15 | Explain the deployment loop in more depth: web servers, containers, routing | Students discuss and ask questions |
| 0:15 – 0:20 | Brief intro to Docker: images vs containers, why consistency matters | Listen |
| 0:20 – 0:50 | Circulate while students run Docker locally | `docker compose -f compose.local.yml up --build` |
| 0:50 – 1:10 | Facilitate Dockerfile reading in pairs — ask questions | Students read Dockerfile with a partner |
| 1:10 – 1:40 | Extension challenges — guide students toward their own projects | Pick a challenge from DOCKER_PATH.md |
| 1:40 – 1:50 | Wrap-up: what is consistent about all deployments? What varies? | Questions and reflection |

---

## Common Student Problems

### "Docker build fails on Render"

**Symptom:** Render Deploy Log shows a build error

**Most common causes:**
1. Render free tier occasionally has a slow first build — tell students to wait 5+ minutes
2. A student pushed a syntax error to `server.js` — check the log for the exact line

---

### "No redeploy after pushing"

**Symptom:** Student pushed but Render didn't start a new build

**Fix:**
1. In Render → service → **Settings** → confirm **Auto-Deploy: Yes**
2. Confirm the push went to `main` (not another branch): `git branch` locally or check GitHub

---

### "App URL loads very slowly"

**Expected behavior on free tier.** Free tier services sleep after 15 minutes idle. First request after sleeping takes 30–60 seconds. Tell students in advance so they don't think their deployment broke.

---

### "I can't find the STUDENT EDIT ZONE"

Point them to `public/index.html` — NOT `server.js`, NOT `README.md`.
The comment block in the HTML is near line 80, inside `<body>`.

---

### "Docker Desktop won't start on Windows"

Common cause: WSL2 not enabled.
Fix: Run `wsl --install` in PowerShell (admin), restart, then try Docker Desktop again.

---

### "Port 8080 already in use"

Another app is using port 8080. Quick fix: edit `compose.local.yml`, change `8080:8080` to `8081:8080`, then visit `localhost:8081`.

---

## Frequently Asked Student Questions

**"What's server.js doing? Do I need to learn Node.js?"**
> For Parts 1 and 2, no. `server.js` is just 10 lines that say "serve the files in the /public folder." You only need to edit `public/index.html`. Node.js and Express become interesting in the extension challenges.

**"What's a Docker image vs a container?"**
> Think of an image like a recipe and a container like the meal you cooked from it. The recipe (image) doesn't change. You can make many meals (containers) from the same recipe.

**"Why does Render redeploy automatically when I push?"**
> Render connects to GitHub via a webhook. When you push, GitHub sends a signal to Render. Render starts a new build. This is called CI/CD — Continuous Integration / Continuous Deployment.

**"Why is the first Render deploy so slow?"**
> Free tier builds your Docker image from scratch the first time. Render caches Docker layers, so subsequent builds for the same repo are much faster (usually under a minute).

**"Do I need this exact app for my own project?"**
> No — the Dockerfile pattern works for any language. Node.js, Python, Ruby, Go, Java — all have official base images on Docker Hub. The structure (install deps → copy code → set CMD) is universal.

---

## Workshop Customization

### Changing the Deployment Platform

This workshop uses Render. If you need to switch:

| Platform | Notes |
|----------|-------|
| **Railway** | Similar free tier, very similar UI, easy Docker support |
| **Fly.io** | More powerful, needs a `fly.toml` config file |
| **DigitalOcean App Platform** | More reliable for large classes, has a student discount |
| **Heroku** | Familiar name, Docker support via Container Registry (more setup) |

### Making It Harder

- Have students add a second HTML page and a `<nav>` linking between them
- Have students add a `/api/data` route in `server.js` that returns JSON
- Have students fetch that JSON in `public/index.html` with `fetch()` and display it
- Have students write a `Dockerfile` for their own project

### Making It Easier

- Skip Docker entirely; have Render use the Node.js native runtime instead of Docker
  - In Render: Runtime → Node, Build Command: `npm install`, Start Command: `npm start`
- Pre-deploy the instructor's copy and have students just edit the HTML file (skip the fork/deploy step)

### Reusing Across Semesters

No cleanup required between semesters — students always fork fresh.
Optional: tag the current repo state each semester: `git tag semester-2025-fall`

---

## Notes on Design Decisions

**Why Node.js + Express instead of a static site?**
A static site would be even simpler, but using Express teaches that:
1. There's a server running behind every URL
2. `server.js` is exactly 10 lines — easy to show and explain
3. Students can add routes as an extension
4. The Dockerfile pattern applies to all languages, not just static sites

**Why Docker instead of Render's native Node.js runtime?**
The Docker path is intentional — it teaches the most transferable skill. Students can apply the same Dockerfile pattern to any language for their own projects.

**Why Render?**
Free tier available, native Docker support, automatic GitHub detection, and each student gets their own unique URL. The dashboard is simple enough to demo live.
