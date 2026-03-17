<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deploy Now Workshop</title>
    <style>
        /* Base styles — you don't need to edit this section */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ============================================================
           STUDENT EDIT ZONE styles
           These styles control the appearance of your editable banner.
           You can change colors, padding, font-size, etc.
           ============================================================ */
        .student-banner {
            width: 100%;
            padding: 2rem;
            text-align: center;

            /* ✏️ CHANGE THIS COLOR to personalize your deployment! */
            background: #3b82f6;

            color: white;
        }

        .student-banner h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .student-banner .student-message {
            font-size: 1.25rem;
            opacity: 0.9;
        }

        /* Workshop info section — don't need to edit this */
        .workshop-info {
            max-width: 720px;
            width: 100%;
            padding: 2rem;
        }

        .card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .card h2 {
            font-size: 1.1rem;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.75rem;
        }

        .card p, .card li {
            color: #cbd5e1;
            line-height: 1.6;
        }

        .card ul { padding-left: 1.25rem; }
        .card li { margin-bottom: 0.25rem; }

        .badge {
            display: inline-block;
            background: #166534;
            color: #bbf7d0;
            border: 1px solid #16a34a;
            border-radius: 9999px;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .edit-hint {
            background: #1c1917;
            border: 2px dashed #78716c;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: #a8a29e;
            margin-top: 1rem;
        }

        .edit-hint code {
            background: #292524;
            padding: 0.1rem 0.35rem;
            border-radius: 0.25rem;
            font-family: monospace;
            color: #fbbf24;
        }
    </style>
</head>
<body>

    {{-- ============================================================
         👋 STUDENT EDIT ZONE — CHANGE SOMETHING HERE!
         This is the section you should edit to see your change live.

         HOW TO EDIT:
         1. Change "Your Name Here" to your actual name
         2. Change the message below it to something personal
         3. Optionally change the banner background color in the <style> block above
         4. Save, commit, and push — then watch Render auto-redeploy!

         FILE LOCATION: resources/views/welcome.blade.php
         ============================================================ --}}

    <div class="student-banner">
        <h1>👋 Hello from <span id="student-name">Your Name Here</span>!</h1>
        <p class="student-message">✏️ Edit this file to make this page yours — then push to see it live.</p>
    </div>

    {{-- ============================================================
         END OF STUDENT EDIT ZONE
         You don't need to edit anything below this line for Parts 1 & 2.
         ============================================================ --}}

    <div class="workshop-info">

        <div class="card">
            <span class="badge">✅ Deployed Successfully</span>
            <h2>Your App is Live</h2>
            <p>If you're reading this, your deployment worked! This page is being served from a Docker container running PHP/Laravel.</p>
            <div class="edit-hint">
                💡 <strong>Next step:</strong> Edit <code>resources/views/welcome.blade.php</code> and change the name above. Then push to GitHub and watch it redeploy automatically.
            </div>
        </div>

        <div class="card">
            <h2>What Just Happened?</h2>
            <ul>
                <li>You forked a GitHub repo with a Dockerfile</li>
                <li>Render cloned your repo and built the Docker image</li>
                <li>Render started the container and exposed it at your URL</li>
                <li>Every future <code>git push</code> triggers a new build and deploy</li>
            </ul>
        </div>

        <div class="card">
            <h2>Workshop Phases</h2>
            <ul>
                <li><strong>Part 1 ✅</strong> — Fast Deploy: You're here!</li>
                <li><strong>Part 2</strong> — Iteration: Edit this page and push</li>
                <li><strong>Part 3 (Lab)</strong> — Docker: Run this locally with <code>docker compose</code></li>
            </ul>
        </div>

    </div>

</body>
</html>
