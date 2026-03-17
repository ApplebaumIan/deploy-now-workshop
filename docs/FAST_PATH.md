# 🚀 Fast Path — Parts 1 & 2

> **This is the required in-class path.** Complete both parts during the 50-minute class session.
>
> **Goal:** Your app is live at a public URL AND you've made a visible change that auto-redeployed.

---

## Part 1 — Get Your App Live

### Step 1 — Fork This Repo

1. Make sure you're signed in to GitHub
2. Click the **Fork** button at the top right of the repo page
3. Accept all defaults and click **Create fork**

You now have your own copy at `https://github.com/YOUR_USERNAME/deploy-now-workshop`

---

### Step 2 — Create a Render Account

1. Go to [render.com](https://render.com)
2. Click **Get Started for Free**
3. Sign up using **GitHub** (recommended — Render will not send you a separate verification email beyond what GitHub requires)

> Already have an account? Just sign in.

---

### Step 3 — Create a New Web Service

1. In the Render dashboard, click **New +** → **Web Service**
2. Click **+ Connect account** if GitHub isn't linked yet, then authorize it
3. Find your forked `deploy-now-workshop` repo and click **Connect**

---

### Step 4 — Configure the Web Service

Fill in the form:

| Setting | Value |
|---------|-------|
| **Name** | `yourname-deploy-workshop` (must be globally unique) |
| **Region** | Ohio US East (or your closest region) |
| **Branch** | `main` |
| **Runtime** | **Docker** |
| **Instance Type** | **Free** |

> **No environment variables needed!** The Node.js app works with Render's defaults.
> Render automatically sets the `PORT` variable, and `server.js` reads it.

---

### Step 5 — Deploy

Click **Create Web Service**.

Render will now:
1. Clone your repo
2. Build the Docker image from the `Dockerfile` (2–4 min on first deploy)
3. Start the container
4. Give you a live URL like `yourname-deploy-workshop.onrender.com`

Watch the build log. When you see:

```
==> Your service is live 🎉
```

Click the URL at the top of the page.

---

### ✅ Part 1 Checkpoint

**You should see:** The "Deploy Now Workshop" welcome page in your browser.

**You deployed a real web app.** Share your URL with a neighbor!

---

## Part 2 — Make a Change and Watch It Redeploy

### Step 1 — Open `public/index.html`

This is the ONLY file you need to edit. Open it in your editor or directly on GitHub.

On GitHub: navigate to `public/index.html` and click the **pencil icon** (Edit file).

Look for this comment block — it's near the top of `<body>`:

```html
<!-- ================================================================
     👋 STUDENT EDIT ZONE — CHANGE THIS SECTION!
     ...
     ================================================================ -->
```

---

### Step 2 — Change Your Name and Message

Inside the STUDENT EDIT ZONE, find these two lines:

```html
<h1>👋 Hello from <strong>Your Name Here</strong>!</h1>
<p class="tagline">✏️ Edit public/index.html and push to make this yours.</p>
```

Change them to something personal:

```html
<h1>👋 Hello from <strong>Ada Lovelace</strong>!</h1>
<p class="tagline">I just deployed something real! 🚀</p>
```

**Bonus:** Also change the banner color. Find this comment in the `<style>` block:

```css
/* ✏️ CHANGE THIS COLOR to personalize your deployment! */
background: #3b82f6;
```

Try: `#e11d48` (red), `#16a34a` (green), `#9333ea` (purple), or any hex color.

---

### Step 3 — Commit and Push

**On GitHub (web editor):**
1. Scroll to the bottom of the editor
2. Write a commit message: `feat: update my welcome message`
3. Click **Commit changes**

**Locally:**
```bash
git add public/index.html
git commit -m "feat: update my welcome message"
git push origin main
```

---

### Step 4 — Watch Render Redeploy

1. Go to your Render dashboard
2. Click on your web service
3. You'll see a new deploy start automatically (Render watches for pushes)
4. Wait ~1–2 minutes for the build to complete
5. Refresh your app URL

---

### ✅ Part 2 Checkpoint

**You should see:** Your updated name and message on the live page.

**Key takeaway:** Every `git push` to `main` triggers an automatic redeploy. This is the core deployment loop.

---

## What Just Happened?

```
You edited public/index.html
           ↓
You pushed to GitHub
           ↓
Render detected the push (GitHub webhook)
           ↓
Render built a new Docker image from your Dockerfile
           ↓
Render stopped the old container, started the new one
           ↓
Your change is live at your public URL
```

This exact same pattern — push code, platform rebuilds and redeploys — is used by companies of all sizes.

---

## Next Steps

- **Finished early?** See [Extension Ideas](../README.md#-extension-ideas) in the README
- **Lab time?** Continue to [Part 3 — Docker Path](DOCKER_PATH.md)

---

## Troubleshooting

| Symptom | Most Likely Fix |
|---------|-----------------|
| Build fails | Check the Render Deploy Log for the error line |
| URL shows "This site can't be reached" | Build is still in progress — wait and try again |
| I pushed but no new deploy started | Check that the Render service has Auto-Deploy enabled |
| My change isn't showing | Hard-refresh: `Ctrl+Shift+R` / `Cmd+Shift+R` |
| Render free tier loads slowly | Free tier sleeps after 15 min idle — first request wakes it up (30–60 sec) |
