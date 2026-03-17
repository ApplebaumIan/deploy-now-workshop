# 👩‍🏫 Instructor Notes

> These notes are for instructors running the Deploy Now Workshop. Students don't need to read this file.

---

## Workshop Overview

This workshop teaches deployment using a progressive complexity model:

1. **Part 1 (Fast Deploy):** Students fork and deploy to Render in the first 30 minutes of class
2. **Part 2 (Iteration):** Students edit a file and watch it auto-redeploy in the last 20 minutes
3. **Part 3 (Docker):** Students run the app locally with Docker during lab

**The pedagogical core:** Students get a real URL *first*, then learn *why* things work the way they do.

---

## Pre-Class Setup Checklist

### Required

- [ ] **Test your own deployment** from scratch using a fresh fork before class
  - This reveals any platform issues (Render changes its UI occasionally)
  - Gives you a reference deployment to demo

- [ ] **Generate a shareable APP_KEY** — students need this to deploy:
  ```bash
  php artisan key:generate --show
  # Output: base64:XXXX...=
  ```
  OR generate manually:
  ```bash
  echo "base64:$(openssl rand -base64 32)"
  ```
  Write this on the board or paste in Slack/Discord at the start of class.

- [ ] **Pre-build a Render deployment** you can demo on the projector

- [ ] **Have this URL ready to share:** `https://render.com/docs/docker` (in case students ask how Render handles Docker)

### Recommended

- [ ] Test the Docker local path yourself (`docker compose -f compose.local.yml up --build`)
- [ ] Prepare 2–3 screenshots of Render UI in case your live demo has connectivity issues
- [ ] Have a backup APP_KEY in case the first one doesn't work

---

## Timing Guide

### In-Class (50 minutes)

| Time | Instructor Action | Student Action |
|------|-------------------|----------------|
| 0:00 – 0:05 | 2-minute framing: "Why deploy first?" Explain the workshop structure | Listen |
| 0:05 – 0:10 | Live demo: fork the repo on screen | Students fork simultaneously |
| 0:10 – 0:20 | Live demo: create Render account, connect repo, fill settings | Students do the same |
| 0:20 – 0:30 | Circulate and help stuck students | Waiting for first deploy |
| 0:30 – 0:35 | Ask 2–3 students to share their URLs | Students open their live URL |
| 0:35 – 0:40 | Show the STUDENT EDIT ZONE in welcome.blade.php | Students find and edit the file |
| 0:40 – 0:48 | Circulate and help; point at Render redeploy log | Students push and watch redeploy |
| 0:48 – 0:50 | Closing: explain what they just did and preview Docker lab | Students confirm their change is live |

### Lab (1 hour 50 minutes)

| Time | Instructor Action | Student Action |
|------|-------------------|----------------|
| 0:00 – 0:15 | 10-min debrief: what is a web server? what did Render do? | Students discuss, ask questions |
| 0:15 – 0:20 | Brief Docker intro: why containers? show the Dockerfile | Listen |
| 0:20 – 0:50 | Circulate while students run Docker locally | `docker compose -f compose.local.yml up --build` |
| 0:50 – 1:10 | Facilitate Dockerfile reading — ask questions, don't just lecture | Students read Dockerfile with partner |
| 1:10 – 1:40 | Extension challenges — circulate and guide | Apply Docker concepts to own project |
| 1:40 – 1:50 | Wrap-up: what did we learn? What's next? | Questions and reflection |

---

## Common Student Problems and Fixes

### APP_KEY Issues

**Symptom:** App shows "No application encryption key has been specified" or 500 error

**Fix:** Make sure `APP_KEY` is set in Render environment variables. The key must start with `base64:`.

**Prevention:** Write the APP_KEY on the board at the start of class. Tell students to copy it exactly.

---

### Wrong Branch

**Symptom:** Push doesn't trigger a Render redeploy

**Fix:** Check that the student's default branch matches what's configured in Render:
1. In GitHub, click the branch dropdown — is it `main` or `master`?
2. In Render, check Settings → Repository → Branch

---

### Render Account Issues

**Symptom:** Student can't sign up or Render is asking for a credit card

**Fix:** Use GitHub OAuth signup (no credit card required for free tier). If they still have issues, they can pair with a neighbor for Part 1 and use Docker locally for lab.

---

### Docker Not Running

**Symptom:** `docker compose` fails with "Cannot connect to the Docker daemon"

**Fix:** Docker Desktop isn't running. Have them open the Docker Desktop app first, wait for it to show "running" in the taskbar, then try again.

---

### Port 8080 Already in Use

**Symptom:** `docker compose up` fails with "address already in use: 8080"

**Fix:** Something else is using port 8080. Quick fix — change the port in `compose.local.yml`:
```yaml
ports:
  - "8081:8080"  # changed left side from 8080 to 8081
```
Then visit [http://localhost:8081](http://localhost:8081) instead.

---

### Student Edited the Wrong File

**Symptom:** Student pushed a change but the welcome page didn't update

**Fix:** Make sure they edited `resources/views/welcome.blade.php`, not a compiled/cached file. Also confirm they committed and pushed the right file.

---

## Frequently Asked Questions from Students

**"Why do we need APP_KEY? What is it?"**
> Laravel uses this key to encrypt session cookies and other sensitive data. It's like a password for the encryption algorithm. Every app has a unique key. Without it, Laravel refuses to run (a security feature).

**"What's the difference between the Dockerfile and compose.local.yml?"**
> The Dockerfile is the recipe for building the image — it installs everything needed to run the app. `compose.local.yml` is the instructions for running that image locally — which ports to open, what environment to use, etc.

**"Why does Render re-deploy automatically when I push?"**
> Render connects to GitHub via a webhook. When you push, GitHub sends a notification to Render, and Render starts a new build. This is called CI/CD (Continuous Integration / Continuous Deployment).

**"Why is the first deploy slow?"**
> Free tier Render instances need time to build your Docker image from scratch. Subsequent deploys reuse the Docker layer cache and are faster. Also, free tier instances "sleep" after 15 minutes of inactivity — the first request after sleeping takes 30–60 seconds to "wake up."

**"Do I need to know PHP/Laravel for this workshop?"**
> No! You only need to edit one file (`welcome.blade.php`) and the edit is in a clearly marked section of HTML. The PHP/Laravel parts are handled by the framework.

---

## Workshop Customization Tips

### Changing the Deployment Target

This workshop uses Render. If you want to switch:

- **Railway:** Similar free tier, Docker support, slightly simpler UI
- **Fly.io:** More powerful, free tier available, needs `fly.toml` config file
- **DigitalOcean App Platform:** Supports Docker, more reliable for large classes
- **Heroku:** Well-known, but Docker support requires Container Registry setup

### Adjusting Difficulty

**Too easy for your students?**
- Have them add a new route and view (requires editing `routes/web.php`)
- Have them add an environment variable that shows on the page
- Have them build a GitHub Actions workflow for CI/CD

**Too hard for your students?**
- Skip the Render setup and pre-deploy for them; have students just edit files and push
- Focus only on Part 2 (the edit → push → see live loop)
- Use a simpler static site (HTML/CSS only) instead of Laravel

### Reusing Across Semesters

To reset for a new semester:
1. The repo itself doesn't change — students always fork fresh
2. Generate a new APP_KEY each semester
3. Update any semester-specific references (workshop name, date)
4. Consider tagging the current state: `git tag semester-2025-fall`

---

## Notes on the Repo Design

### Why Laravel?

Laravel is used as the app framework because:
1. It mirrors what students often use in class projects
2. The Dockerfile is a realistic, non-trivial example
3. It teaches environment variable concepts (APP_KEY, APP_ENV)
4. The route/view model is simple enough to explain quickly

### Why Render?

Render is used as the platform because:
1. Free tier with no credit card required
2. Native Docker support with automatic GitHub detection
3. Simple dashboard UI students can understand
4. Each student gets their own unique URL automatically
5. No custom domain configuration needed for fast path

### Why the Two-Stage Dockerfile?

The multi-stage build teaches two important concepts:
1. Build dependencies vs runtime dependencies
2. Image size matters for production (smaller = faster, cheaper)

The pattern is directly applicable to Node.js, Python, Go, and other languages.
