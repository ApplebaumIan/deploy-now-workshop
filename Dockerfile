# =============================================================================
# DEPLOY NOW WORKSHOP — Dockerfile
# =============================================================================
#
# This file tells Docker how to package and run the app.
# It is used by:
#   - Render (production deployment)  — same image, runs in the cloud
#   - Docker Compose (local dev)      — same image, runs on your laptop
#
# That consistency is the whole point: build once, run anywhere.
#
# HOW TO READ THIS FILE:
#   Each instruction (FROM, WORKDIR, COPY, RUN, CMD) is one "layer."
#   Docker caches layers. If nothing changed in a layer, it reuses the cache.
#   That's why we COPY package*.json and npm install BEFORE copying the rest
#   of the app — so npm install is only re-run when dependencies actually change.
# =============================================================================

# Start from the official Node.js 20 image on Alpine Linux.
# Alpine is a tiny Linux distro (~5 MB) — keeps the image small.
# Node 20 is the current LTS (Long-Term Support) release.
FROM node:20-alpine

# Set the working directory inside the container.
# All subsequent commands run from here, and the app files live here.
WORKDIR /app

# Copy ONLY the package files first.
# This is a Docker caching best practice:
#   If package.json hasn't changed, Docker reuses the cached npm install layer.
#   If you copied everything first, any file change would bust the npm cache.
COPY package*.json ./

# Install only production dependencies (no dev tools like nodemon, jest, etc.)
RUN npm ci --omit=dev

# Now copy the rest of the app files.
# This happens after npm install so changing source code doesn't re-run npm install.
COPY . .

# Document that the app listens on port 8080.
# This does NOT open the port — Docker Compose or Render handles that.
EXPOSE 8080

# The command that runs when the container starts.
# "npm start" runs the "start" script in package.json → "node server.js"
CMD ["npm", "start"]
