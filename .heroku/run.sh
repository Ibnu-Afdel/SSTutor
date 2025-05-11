#!/bin/bash

echo "Running custom build script"

# Install Node.js dependencies
npm ci

# Build assets
npm run build

echo "Build script completed" 