# 🚀 Fast Path — Parts 1 & 2

> **This is the required in-class path.** Complete Parts 1 and 2 during the 50-minute class session.
>
> Goal: Your app is live at a public URL AND you've made a visible change that auto-redeployed.

---

## Part 1 — Get Your App Live

### Step 1 — Fork This Repo

1. Make sure you're signed in to GitHub
2. Click the **Fork** button at the top right of the repo page
3. Accept defaults and click **Create fork**

You now have your own copy at `https://github.com/YOUR_USERNAME/deploy-now-workshop`

---

### Step 2 — Create a Render Account

1. Go to [render.com](https://render.com)
2. Click **Get Started for Free**
3. Sign up using **GitHub** (easiest — no separate email verification)

> **Tip:** If you already have a Render account, just sign in.

---

### Step 3 — Create a New Web Service

1. In the Render dashboard, click **New +** → **Web Service**
2. On the "Connect a repository" screen, click **+ Connect account** if GitHub isn't connected
3. Find and select your forked `deploy-now-workshop` repo
4. Click **Connect**

---

### Step 4 — Configure Your Web Service

Fill in the settings:

| Setting | Value |
|---------|-------|
| **Name** | `yourname-deploy-workshop` (must be unique) |
| **Region** | Ohio US East (or closest to you) |
| **Branch** | `main` |
| **Runtime** | Docker |
| **Instance Type** | Free |

Then click **Advanced** and add these Environment Variables:

| Key | Value |
|-----|-------|
| `APP_ENV` | `production` |
| `APP_KEY` | *(ask your instructor — it looks like `base64:abc123...==`)* |

> **Why do we need APP_KEY?**
> Laravel uses this key to encrypt session data and cookies. Without it, the app throws a 500 error. It's a one-time setup.

---

### Step 5 — Deploy!

Click **Create Web Service**.

Render will now:
1. Clone your repo
2. Build the Docker image (this takes 2–4 minutes on first deploy)
3. Start the container
4. Give you a live URL

Watch the build log scroll by. When you see something like:

```
==> Your service is live 🎉
```

Click the URL at the top of the page (it looks like `https://yourname-deploy-workshop.onrender.com`).

---

### ✅ Part 1 Checkpoint

**You should see:** The "Deploy Now Workshop" welcome page in your browser.

If you see it — you've successfully deployed a web application! 🎉

**Share your URL with your neighbor and the instructor.**

---

## Part 2 — Make a Change and Watch It Redeploy

### Step 1 — Find the Edit Zone

Open `resources/views/welcome.blade.php` in your editor or on GitHub.

Scroll to near the top of the `<body>` and look for this comment block:

```blade
{{-- ============================================================
     👋 STUDENT EDIT ZONE — CHANGE SOMETHING HERE!
     This is the section you should edit to see your change live.
     ============================================================ --}}
```

This section has:
- Your name (change it!)
- A message (change it!)
- A colored banner you can see immediately when the page loads

---

### Step 2 — Make Your Change

Edit the text inside the student edit zone. Keep it simple:

```html
<!-- Change "Your Name Here" to your actual name -->
<span id="student-name">Ada Lovelace</span>

<!-- Change this message to anything you want -->
<p class="student-message">I deployed something today! 🚀</p>
```

You can also:
- Change the emoji
- Change the background color of the banner (`background: #3b82f6` → any hex color)
- Add your own HTML

---

### Step 3 — Commit and Push

**If editing on GitHub:**
1. Scroll to the bottom of the editor
2. Add a commit message like: `feat: update my welcome message`
3. Click **Commit changes**

**If editing locally:**
```bash
git add resources/views/welcome.blade.php
git commit -m "feat: update my welcome message"
git push origin main
```

---

### Step 4 — Watch the Auto-Redeploy

1. Go to your Render dashboard
2. Click on your web service
3. You'll see a new deploy started automatically (triggered by your push)
4. Wait for it to complete (1–3 minutes)
5. Refresh your app URL

---

### ✅ Part 2 Checkpoint

**You should see:** Your updated name or message on the live page.

**Key takeaway:** Every `git push` triggers an automatic redeploy. This is the core of modern deployment.

---

## What Just Happened?

Here's the deployment loop you just experienced:

```
You edited a file
    ↓
You pushed to GitHub
    ↓
Render detected the push (via webhook)
    ↓
Render built a new Docker image from your Dockerfile
    ↓
Render replaced the old container with the new one
    ↓
Your change is live at a public URL
```

This same pattern is used in real production systems at companies of all sizes.

---

## Next Steps

- **Done early?** Try the [Extension Ideas](../README.md#-extension-ideas) in the README
- **Lab time?** Continue to [Part 3 — Docker Path](DOCKER_PATH.md)

---

## Troubleshooting

| Symptom | Fix |
|---------|-----|
| Build fails with "No APP_KEY" | Add `APP_KEY` env var in Render settings |
| Page shows 500 error | Check Deploy Logs in Render for the real error |
| Push didn't trigger a deploy | Check that your Render service is connected to the right branch |
| URL shows "This site can't be reached" | Build is still in progress — wait and refresh |
| Page loads but looks broken | Normal — compiled CSS/JS not included; the content is still there |
